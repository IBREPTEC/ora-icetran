<?php
$caminho = dirname(__DIR__);

require_once $caminho.'/../app/includes/config.php';
require_once $caminho.'/../app/includes/funcoes.php';
require_once $caminho.'/../detran/includes/funcoes.php';
require_once $caminho.'/../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../app/classes/core.class.php';
require_once $caminho.'/../detran/lib/restsecurity/ConnectionFactory.php';

use detran\restsecurity\ConnectionFactory as ConnectionFactory;

$siglaEstado = 'PR';
$codTransacao = 427; //Cadastro certificado

$matriculaObj = new Matriculas();

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idParana = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.cnh,
        p.idpessoa,
        c.idcurso,
        p.data_nasc,
        m.idoferta,
        m.idcurso,
        m.idescola,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
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
        m.idmatricula in (9734) and 
        e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N" 
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI" 
        '.$cursosIn.'  
        AND e.idestado = ' . $idParana . '
        AND m.ativo = "S" 
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($query)) {
    $connection = ConnectionFactory::getConnection(
        $config['detran'][$siglaEstado]['id'],
        $config['detran'][$siglaEstado]['chave']
    );

    $urlEnvio = $config['detran'][$siglaEstado]['urlJSON'] .
    "/rest/servico/reciclagem/ead/certificado/cadastrar";

    $dataNasc = new \DateTime($linha['data_nasc']);
    $dataInicio = new \DateTime($linha['data_inicio_curso']);
    $dataConclusao = new \DateTime($linha['data_conclusao']);

    $dadosEnvio = [
        'numCnpj' => $config['detran'][$siglaEstado]['registro_empresa'],
        'numCpfInstrutor' => $config['detran'][$siglaEstado]['registro_instrutor'],
        'numRegCnh' => $linha['cnh'],
        'numCpfCondutor' => $linha['documento'],
        'dataNascimento' => $dataNasc->format('d/m/Y'),
        // 'dataInicioCurso' => $dataInicio->format('d/m/Y'),
        'dataInicioCurso' => '01/05/2021',
        // 'dataFimCurso' => $dataConclusao->format('d/m/Y')
        'dataFimCurso' => '31/05/2021'
    ];

    foreach ($dadosEnvio as $ind => $val) {
        $connection->addFormParam($ind, $val);
    }

    $result = $connection->post($urlEnvio);
    $connection = null;
    $retorno = $result->getBody();
    echo "<br>Matrícula: ".$linha['idmatricula'];
    echo "<br>Envio:<br>";
    var_dump(json_encode($dadosEnvio));
    echo "<br>Retorno:<br>";
    var_dump(json_encode($retorno));
    echo "<br><br>";
    $retornoHttpCode = $result->getHttpCode();

    if ($retornoHttpCode != 200) {
        $matriculaObj->executaSql('BEGIN');
        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, json_encode($dadosEnvio));
        $matriculaObj->executaSql('COMMIT');
        continue;
    }

    $matriculaObj->executaSql('BEGIN');
    switch ($retornoHttpCode) {
        case 200:
            $json = json_decode($retorno);
            if ($json->validacaoOk) {
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
            }
    }

    salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, json_encode($dadosEnvio));
    $matriculaObj->executaSql('COMMIT');
}
