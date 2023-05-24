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
require_once $caminho . '/../../app/includes/retornos.matogrossodosul.php';

$matriculaObj = new Matriculas();
$siglaEstado = 'MS';
$codTransacao = 431;
$sql = 'SELECT
        m.idmatricula,
        m.idcurso,
        p.documento,
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
        m.idmatricula in (16910, 16964, 17024, 17465, 17151, 17391, 17664) and
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
    $anoAtual = (new \DateTime())->format('Y');
    $mesAtual = (new \DateTime())->format('m');
    $diaAtual = (new \DateTime())->format('d');
    $minutoAtual = (new \DateTime())->format('i');

    $formData = 'cpf=' . str_pad($linha['documento'], 11, '0', STR_PAD_LEFT) .
        '&tpCur=' . $detran_tipo_aula[$siglaEstado][$linha['idcurso']] .
        '&codSeg=' . ((intval($anoAtual) + intval($mesAtual) + intval($diaAtual)) * $minutoAtual);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $config['detran'][$siglaEstado]['urlLiberacao'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $formData,
        CURLOPT_INTERFACE => $config['detran'][$siglaEstado]['ip'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    echo "<br>Matrícula: ".$linha['idmatricula'];
    echo "<br>Envio:<br>";
    var_dump($formData);
    echo "<br>Retorno:<br>";
    echo "<xmp>";
    var_dump($response);
    echo "</xmp>";
    echo "<br><br>";

    $retorno = ($err) ? "cURL Error #:" . $err : $response;

    $arrRetorno = json_decode(json_encode(simplexml_load_string($retorno)),true);

    if ($arrRetorno) {
        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'],
            $arrRetorno['consulta']['condutor']['codRet'] . ' - ' . $retornoLiberacao[$arrRetorno['consulta']['condutor']['codRet']],
            $formData);
        $matriculaObj->executaSql('BEGIN');

        if (in_array($arrRetorno['consulta']['condutor']['codRet'], ['03', '04', '09'])) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);

            $matriculaObj->executaSql('COMMIT');
        } else {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);

            $matriculaObj->executaSql('COMMIT');
        }
    }
}