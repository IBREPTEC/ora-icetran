<?php
$caminho = dirname(__DIR__);

require_once $caminho.'/../app/includes/config.php';
require_once $caminho.'/../app/includes/funcoes.php';
require_once $caminho.'/../detran/includes/funcoes.php';
require_once $caminho.'/../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../app/classes/core.class.php';
$coreObj = new Core;
require_once $caminho . '/../app/classes/matriculas.class.php';
require_once $caminho . '/../app/classes/orio/Transacoes.php';
require_once $caminho . '/../app/classes/detran.class.php';
require_once $caminho.'/../app/especifico/inc/config.detran.php';
require_once $caminho.'/../app/especifico/inc/config.banco.php';
require_once $caminho.'/../app/especifico/inc/config.especifico.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

require_once $caminho . '/../app/includes/cidades.matogrosso.php';
define('INTERFACE_DETRAN_MT_CERTIFICADO', retornarInterface('detran_mt_certificado')['id']);
$matriculaObj = new Matriculas();
$transacoes = new Transacoes();
$siglaEstado = 'MT';
$codTransacao = 427; //Cadastro certificado
$tipo_curso = 2; // EAD

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idMatoGrosso = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.idsituacao,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        p.categoria,
        e.detran_codigo,
        c.idcurso,
        c.carga_horaria_total,
        m.renach,
        m.idcurso,
        frdm.idfolha_matricula,
        e.idcidade,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mw.ativa = "S"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S")
    WHERE
        m.idmatricula in (17603) and 
        e.detran_codigo IS NOT NULL
        AND cw.cancelada = "N"
        AND m.detran_certificado = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idMatoGrosso . '
        AND m.ativo = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);

while ($dados = mysql_fetch_assoc($query)) {
    try {
        $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;

        $opcoesSOAP = array(
            'trace' => 1,
            'exceptions' => true
        );

        $conexaoSOAP = new SoapClient(
            $config['detran']['MT']['urlWsl'],
            $opcoesSOAP
        );

        $dadosXML = '
        <ProcessaAula>
            <Integrador>' . $config['detran']['MT']['integrador'] . '</Integrador>
            <ChaveUnica>' . $config['detran']['MT']['chave'] . '</ChaveUnica>
            <CPF>' . $dados['documento'] . '</CPF>
            <NumeroRenach>' . $dados['renach'] . '</NumeroRenach>
            <CategoriaPretendida>' . $dados['categoria'] . '</CategoriaPretendida>
            <CodigoCurso>' . $GLOBALS['detran_tipo_aula']['MT'][$dados['idcurso']] . '</CodigoCurso>
            <ModalidadeCurso>2</ModalidadeCurso>
            <NumeroCertificado>' . $dados['idfolha_matricula'] . '</NumeroCertificado>
            <DataInicio>' . (new DateTime($dados['data_inicio_curso']))->format('Ymd') . '</DataInicio>
            <DataFim>' . (new DateTime($dados['data_conclusao']))->format('Ymd') . '</DataFim>
            <DataValidade>' . (new DateTime($dados['data_conclusao']))->modify("+5 year")->format("Ymd") . '</DataValidade>
            <CargaHoraria>' . $cargaHoraria . '</CargaHoraria>
            <CNPJEntidade>' . $config['detran']['MT']['cnpj_entidade'] . '</CNPJEntidade>
            <CPFProfissional>' . $config['detran']['MT']['cpf_instrutor'] . '</CPFProfissional>
            <CodigoMunicCurso>' . $cidades[$dados['idcidade']]['idmunicipio'] . '</CodigoMunicCurso>
            <UFMunicipio>MT</UFMunicipio>
        </ProcessaAula>
    ';

        $any = new SoapVar($dadosXML, XSD_ANYXML);
        $dadosSOAP = [
            'ProcessaAula' => [
                'xmlEntrada' => [
                    'any' => $any
                ]
            ]
        ];
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_MT_CERTIFICADO, 'E', $dadosSOAP);
        $result = $conexaoSOAP->__SoapCall('ProcessaAula', $dadosSOAP);
        $ProcessaAulaResult = json_decode(json_encode(simplexml_load_string($result->ProcessaAulaResult->any)), TRUE)['NewDataSet']['Table'];
        $stringEnvio = json_encode($dadosSOAP);
        $retorno = json_encode(['codigo' => $ProcessaAulaResult['CodigoRetorno'], 'mensagem' => $ProcessaAulaResult['MensagemRetorno']]);
        echo "<br>Matrícula: ".$dados['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($dadosSOAP));
        echo "<br>Retorno:<br>";
        var_dump($retorno);
        echo "<br><br>";


        if ($ProcessaAulaResult['CodigoRetorno'] == 1) {

            $coreObj->executaSql('BEGIN');
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            
            salvarLogDetran($matriculaObj, 427, $dados['idmatricula'], $retorno, $stringEnvio);
            $coreObj->executaSql('COMMIT');
            $transacoes->set('json', $retorno);
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
            continue;
        }

        $coreObj->executaSql('BEGIN');
        if ($ProcessaAulaResult['CodigoRetorno'] == 0) {
            $situacaoConcluida = $matriculaObj->retornarSituacaoConcluida();
            $update = "UPDATE matriculas
                SET detran_certificado = 'S',
                    idsituacao = " . $situacaoConcluida['idsituacao'] . "
                WHERE idmatricula = " . $dados['idmatricula'];
            $coreObj->executaSql($update);

            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
            $transacoes->finalizaTransacao(null, 2, null, $retorno);
        } else {
            $transacoes->set('json', $retorno);
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
        }
        salvarLogDetran($matriculaObj, 427, $dados['idmatricula'], $retorno, $stringEnvio);
        $coreObj->executaSql('COMMIT');

    } catch (Exception $ex) {
        $transacoes->finalizaTransacao(
            null,
            3,
            json_encode(['codigo' => $ex->getCode(), 'mensagem' => $ex->getMessage()])
        );
        echo $ex->getMessage();
    }
}
