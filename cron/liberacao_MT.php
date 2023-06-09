<?php
$caminho = __DIR__;
require_once $caminho.'/../../app/includes/config.php';
require_once $caminho.'/../../app/includes/funcoes.php';
require_once $caminho.'/../../detran/includes/funcoes.php';
require_once $caminho.'/../../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../../app/classes/core.class.php';
require_once $caminho.'/../../app/classes/matriculas.class.php';
require_once $caminho.'/../../app/especifico/inc/config.detran.php';
require_once $caminho.'/../../app/especifico/inc/config.banco.php';
require_once $caminho.'/../../app/especifico/inc/config.especifico.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_MT_LIBERACAO', retornarInterface('detran_mt_liberacao')['id']);
$matriculaObj = new Matriculas();
$transacoes = new Transacoes();
$siglaEstado = 'MT';
$codTransacao = 431; //Consulta Processo do aluno

$sql = 'SELECT
        m.idmatricula,
        m.idcurso,
        e.detran_codigo,
        p.documento,
        p.cnh,
        p.data_nasc,
        m.renach,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        m.idmatricula in (17603) and
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND
        e.idestado = ' . $estadosDetran[$siglaEstado] . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "AL" AND
        cw.fim = "N" AND
        cw.inativa = "N" AND
        cw.cancelada = "N"
    ORDER BY data_ultimo_historico ASC
    limit 10';
    echo $sql;
    echo "<br><br>";

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    try {
        $opcoesSOAP = array(
            'trace' => 1,
            'exceptions' => true
        );

        $conexaoSOAP = new SoapClient(
            $config['detran'][$siglaEstado]['urlWsl'],
            $opcoesSOAP
        );

        $dadosXML = '
            <ConsultaAluno>
                <Integrador>' . $config['detran'][$siglaEstado]['integrador'] . '</Integrador>
                <ChaveUnica>' . $config['detran'][$siglaEstado]['chave'] . '</ChaveUnica>
                <CPF>' . $linha['documento'] . '</CPF>
                <NumeroRenach>' . $linha['renach'] . '</NumeroRenach>
                <CodigoCurso>' . $detran_tipo_aula[$siglaEstado][$linha['idcurso']] . '</CodigoCurso>
            </ConsultaAluno>
        ';
        $any = new SoapVar($dadosXML, XSD_ANYXML);

        $dadosSOAP = [
            'ConsultaAluno' => [
                'xmlEntrada' => [
                    'any' => $any
                ]
            ]
        ];
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_MT_LIBERACAO, 'E', $dadosSOAP);
        $result = $conexaoSOAP->__SoapCall('ConsultaAluno', $dadosSOAP);
        $ConsultaCredenciadoResult = json_decode(json_encode(simplexml_load_string($result->ConsultaAlunoResult->any)), TRUE)['NewDataSet']['Table'];
        $stringEnvio = json_encode($dadosSOAP);
        $retorno = json_encode(['codigo' => $ConsultaCredenciadoResult['CodigoRetorno'], 'mensagem' => $ConsultaCredenciadoResult['MensagemRetorno']]);
        echo "<br>Matrícula: ".$linha['idmatricula'];
            var_dump($retorno);
            echo "<br><br>";


        if ($ConsultaCredenciadoResult['CodigoRetorno'] == 0) {
            $conexaoSOAP = new SoapClient(
                $config['detran'][$siglaEstado]['urlWsl'],
                $opcoesSOAP
            );
            $dadosXML = '
            <ConsultaCredenciado>
                <Integrador>' . $config['detran'][$siglaEstado]['integrador'] . '</Integrador>
                <ChaveUnica>' . $config['detran'][$siglaEstado]['chave'] . '</ChaveUnica>
                <CPF>' . $linha['documento'] . '</CPF>
                <NumeroRenach>' . $linha['renach'] . '</NumeroRenach>
                <CodigoCurso>' . $detran_tipo_aula[$siglaEstado][$linha['idcurso']] . '</CodigoCurso>
                <CPFInstrutor>' . $config['detran'][$siglaEstado]['cpf_instrutor'] . '</CPFInstrutor>
                <CNPJEntidade>' . $config['detran'][$siglaEstado]['cnpj_entidade'] . '</CNPJEntidade>
            </ConsultaCredenciado>';

            $any = new SoapVar($dadosXML, XSD_ANYXML);

            $dadosSOAP = [
                'ConsultaCredenciado' => [
                    'xmlEntrada' => [
                        'any' => $any
                    ]
                ]
            ];
            $result = $conexaoSOAP->__SoapCall('ConsultaCredenciado', $dadosSOAP);
            $ConsultaCredenciadoResult = json_decode(json_encode(simplexml_load_string($result->ConsultaCredenciadoResult->any)), TRUE)['NewDataSet']['Table'];
            $stringEnvio = json_encode($dadosSOAP);
            $retorno = json_encode(['codigo' => $ConsultaCredenciadoResult['CodigoRetorno'], 'mensagem' => $ConsultaCredenciadoResult['MensagemRetorno']]);
            echo "<br>Matrícula: ".$linha['idmatricula'];
            echo "<br>Envio:<br>";
            var_dump(json_encode($dadosSOAP));
            echo "<br>Retorno:<br>";
            var_dump($retorno);
            echo "<br><br>";
            salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $stringEnvio);
            if ($ConsultaCredenciadoResult['CodigoRetorno'] == 0) {
                $matriculaObj->executaSql('BEGIN');
                $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                $transacoes->finalizaTransacao(null, 2, null,$retorno);
            }
        } else {
            $matriculaObj->executaSql('BEGIN');
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
            $transacoes->set('json', $retorno);
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $stringEnvio);
        $matriculaObj->executaSql('COMMIT');


    } catch (Exception $ex) {
        $transacoes->finalizaTransacao(
            null,
            3,
            json_encode(['codigo' => $ex->getCode(), 'mensagem' => $ex->getMessage()])
        );
        echo $ex->getMessage();
    }
}

