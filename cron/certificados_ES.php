<?php
$caminho = dirname(__DIR__);


require_once $caminho.'/../app/includes/config.php';
require_once $caminho.'/../app/includes/funcoes.php';
require_once $caminho.'/../detran/includes/funcoes.php';
require_once $caminho.'/../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../app/classes/core.class.php';
ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_ES_LIBERACAO', retornarInterface('detran_es_liberacao')['id']);
error_reporting(1);
ini_set('display_errors', 1);

require_once $caminho . '/../app/classes/matriculas.class.php';
$matriculaObj = new Matriculas();
$siglaEstado = 'ES';
$codTransacao = 427; //Cadastro certificado

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idEspirito = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.idsituacao,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        e.detran_codigo,
        c.idcurso,
        c.carga_horaria_total,
        m.cod_ticket,
        m.detran_numero,
        m.idoferta,
        m.idcurso,
        m.idescola,
        m.codigo_diploma,
        e.idcidade,
        p.cnh,
        frdm.idfolha_matricula,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "detran_situacao" AND mh.para = "LI"
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
        e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.' 
        AND e.idestado = ' . $idEspirito . ' 
        AND m.ativo = "S"
        and m.idmatricula = 18071
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
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
            'ChaveEAD'=> $config['detran'][$siglaEstado]['chave']
        );
        
        $conexaoSOAP = new SoapClient($config['detran'][$siglaEstado]['urlWsl'], $opcoesSOAP);
        $header = new SoapHeader('http://tempuri.org/', 'EADSoapHeader', $auth);
        $conexaoSOAP->__setSoapHeaders($header);
        
        $dataInicio = new \DateTime($linha['data_inicio_curso']);
        $dataConclusao = new \DateTime($linha['data_conclusao']);
        $cargaHoraria = !empty($linha['carga_horaria_total']) ? $linha['carga_horaria_total'] : 30;
        
        $dadosSOAP = [
            'EnviarDadosCurso' => [
                'login' => $config['detran'][$siglaEstado]['login'],
                'numRegistroCNHAluno' => str_pad($linha['cnh'], 11),
                'codigoCurso' => 20,
                'numeroCertificado' => $linha['idfolha_matricula'],
                'dataInicioCurso' => $dataInicio->format('Y-m-d'),
                'dataFimCurso' => $dataConclusao->format('Y-m-d'),
                'cargaHoraria' => $cargaHoraria,
                'cpfInstrutor' => $config['detran'][$siglaEstado]['cpf_instrutor']
                ]
            ];
            
        // Enviar dados do curso
        $respostaSoap = $conexaoSOAP->__soapCall('EnviarDadosCurso', $dadosSOAP);
        $stringEnvio = json_encode($dadosSOAP);
        $retornoSOAP = $respostaSoap->EnviarDadosCursoResult->codRetorno . ' - ' . $respostaSoap->EnviarDadosCursoResult->descricao;
        echo "<br>Matrícula: ".$linha['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($dadosSOAP));
        echo "<br>Retorno:<br>";
        var_dump(json_encode($retornoSOAP));
        echo "<br><br>";
        $matriculaObj->executaSql('BEGIN');

        if ($respostaSoap->EnviarDadosCursoResult->codRetorno == 1) {
            $situacaoConcluida = $matriculaObj->retornarSituacaoConcluida();
            $update = "UPDATE matriculas 
                    SET detran_certificado = 'S',
                        idsituacao = " . $situacaoConcluida['idsituacao'] . " 
                    WHERE idmatricula = " . $linha['idmatricula'];
            $retorno = $matriculaObj->executaSql($update);
            $matriculaObj->executaSql($update);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'situacao', 'modificou', $linha['idsituacao'], $situacaoConcluida['idsituacao'], null);
        } 

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retornoSOAP, $stringEnvio);
        $matriculaObj->executaSql('COMMIT');

    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

