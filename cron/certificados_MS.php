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

error_reporting(1);
ini_set('display_errors', 1);

define('INTERFACE_DETRAN_MS_CERTIFICADO', retornarInterface('detran_ms_certificado')['id']);

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

require_once $caminho . '/../app/includes/cidades.matogrossodosul.php';

$matriculaObj = new Matriculas();
$transacoes = new Transacoes();
$detranObj = new Detran();

$siglaEstado = 'MS';
$codTransacao = 427; //Cadastro certificado

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idMatoGrossoSul = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.idsituacao,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        p.nome,
        p.data_nasc,
        p.rg,
        p.rg_orgao_emissor,
        p.idcidade,
        e.detran_codigo,
        c.idcurso,
        c.carga_horaria_total,
        m.cod_ticket,
        m.detran_numero,
        m.idoferta,
        m.idcurso,
        m.idescola,
        m.codigo_diploma,
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
        INNER JOIN cidades ci ON (p.idcidade = ci.idcidade)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S")
    WHERE 
        m.idmatricula in (16910, 16964, 17024, 17465, 17151, 17391, 17664) and 
    	e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        AND '.$cursosIn.'  
        AND e.idestado = ' . $idMatoGrossoSul . '
        AND m.ativo = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 20';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    try {
        $dataInicio = (new DateTime($linha['data_inicio_curso']))->format('Ymd');
        $dataNasc = (new DateTime($linha['data_nasc']))->format('Ymd');
        $anoAtual = (new DateTime())->format('Y');
        $diaAtual = (new DateTime())->format('d');

        $_POST = 'cpf=' . $linha['documento'] .
            '&nome=' . urlencode($linha['nome']) .
            '&dtNasc=' . $dataNasc .
            '&rg=' . urlencode($linha['rg'] . ' ' . $linha['rg_orgao_emissor']) .
            '&cdMun=' . str_pad($cidades[$linha['idcidade']]['idmunicipio'], 5, '0', STR_PAD_LEFT) .
            '&dtIni=' . $dataInicio .
            '&cnpjInst=' . $config['detran']['MS']['cnpjInst'] .
            '&codSeg=' . (intval($anoAtual) + intval($diaAtual)) . $config['detran']['MS']['cnpjInst'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $config['detran']['MS']['urlConclusao'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $_POST,
            CURLOPT_INTERFACE => $config['detran']['MS']['ip'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $xml = simplexml_load_string($retorno);

        $codigoRetorno = (string)$xml->registra->condutor->codRet;
        $mensagem = (string)$xml->registra->condutor->msg;

        $retorno = json_encode(['codigo' => $codigoRetorno, 'mensagem' => $mensagem], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        echo "<br>Matrícula: ".$linha['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($_POST));
        echo "<br>Retorno:<br>";
        var_dump($retorno);
        echo "<br><br>";
        if ($codigoRetorno != '000') {

            $detranObj->executaSql('BEGIN');
            if ($linha['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }

            salvarLogDetran($matriculaObj, 427, $linha['idmatricula'], $codigoRetorno . ' - ' . $mensagem,
                $_POST);
            $detranObj->executaSql('COMMIT');

            $transacoes->set('json', json_encode($err ? $err : $retorno));
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
            // return false;
            continue;
        }
        
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_MS_CERTIFICADO, 'E');
        $detranObj->executaSql('BEGIN');
        if ($codigoRetorno == '000') {
            $situacaoConcluida = $matriculaObj->retornarSituacaoConcluida();
            $update = "UPDATE matriculas
                SET detran_certificado = 'S',
                    idsituacao = " . $situacaoConcluida['idsituacao'] . "
                WHERE idmatricula = " . $linha['idmatricula'];
            $detranObj->executaSql($update);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'situacao', 'modificou', $linha['idsituacao'], $situacaoConcluida['idsituacao'], null);
            $transacoes->set('json', $retorno);
            
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
        } else {
            $transacoes->set('json', json_encode($err ? $err : $retorno));
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
        }
        salvarLogDetran($matriculaObj, 427, $linha['idmatricula'], $codigoRetorno . ' - ' . $mensagem,
            $_POST);
        $detranObj->executaSql('COMMIT');

    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}