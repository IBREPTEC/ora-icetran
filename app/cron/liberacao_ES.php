<?php
$caminho = __DIR__;
require_once $caminho.'/../../app/includes/config.php';
require_once $caminho.'/../../app/includes/funcoes.php';
require_once $caminho.'/../includes/funcoes.php';
require_once $caminho.'/../../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../../app/classes/core.class.php';
require_once $caminho.'/../../app/classes/matriculas.class.php';
require_once $caminho.'/../../app/classes/orio/Transacoes.php';

ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_ES_LIBERACAO', retornarInterface('detran_es_liberacao')['id']);
$matriculaObj = new Matriculas();
$transacoes = new Transacoes();
$siglaEstado = 'ES';
$codTransacao = '20';
$sql = 'SELECT
        m.idmatricula,
        p.documento,
        e.detran_codigo,
        p.documento,
        p.cnh,
        p.data_nasc,
        m.idcurso,
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
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND
        e.idestado = ' . $estadosDetran[$siglaEstado] . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "AL" AND
        cw.fim = "N" AND
        cw.inativa = "N" AND
        cw.cancelada = "N"
        and m.idmatricula = 18071
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
        $auth = array(
            'ChaveEAD' => $config['detran'][$siglaEstado]['chave']
        );
        $conexaoSOAP = new SoapClient($config['detran'][$siglaEstado]['urlWsl'], $opcoesSOAP);
    
        $header = new SoapHeader('http://tempuri.org/', 'EADSoapHeader', $auth);
        $conexaoSOAP->__setSoapHeaders($header);
        $dadosSOAP = [
            'AbrirCursoSemCFC' => [
                'login' => $config['detran'][$siglaEstado]['login'],
                'numRegistroCNHAluno' => str_pad($linha['cnh'], 11),
                'codigoCurso' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]
            ]
        ];
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_ES_LIBERACAO, 'E', $dadosSOAP);
        // Abrir Curso Sem CFC
        $respostaSoap = $conexaoSOAP->__soapCall('AbrirCursoSemCFC', $dadosSOAP);

        // print_r($dadosSOAP);
        // echo '<br/><br/>Request : <br/><xmp>', $conexaoSOAP->__getLastRequest(), '</xmp><br/><br/>';

        $stringEnvio = json_encode($dadosSOAP);
        $retorno = json_encode(
            ['codigo' => $respostaSoap->AbrirCursoSemCFCResult->codRetorno,
                'mensagem' => $respostaSoap->AbrirCursoSemCFCResult->descricao],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
        );
        echo "<br>Matrícula: ".$linha['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($dadosSOAP));
        echo "<br>Retorno:<br>";
        var_dump(json_encode($retorno));
        echo "<br><br>";
        $matriculaObj->executaSql('BEGIN');
        if ($respostaSoap->AbrirCursoSemCFCResult->codRetorno == 1) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
            $transacoes->set("json", $retorno);
            $transacoes->finalizaTransacao(null, 2);
            $transacoes->set("json", null);
        } else {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
            $transacoes->set("json", $retorno);
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set("json", null);
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $stringEnvio);
        $matriculaObj->executaSql('COMMIT');

    } catch (Exception $ex) {
        $transacoes->set("json", json_encode(['codigo' => $ex->getCode(), 'mensagem' => $ex->getMessage()]));
        $transacoes->finalizaTransacao(
            null,
            3
        );
        $transacoes->set("json", null);
        echo '<br/><br/> Mensagem de erro : <br/><br/>',
        $ex->getMessage(),
        '<br/><br/>Request : <br/><xmp>',
        $conexaoSOAP->__getLastRequest(),
        '</xmp><br/><br/> Request Headers : <br/><xmp>',
        $conexaoSOAP->__getLastRequestHeaders(),
        '</xmp><br/><br/> Response : <br/><xmp>',
        $conexaoSOAP->__getLastResponse(),
        '</xmp><br/><br/> Response Headers : <br/><xmp>',
        $conexaoSOAP->__getLastResponseHeaders(),
        '</xmp>';
        return $ex;
    }
}

