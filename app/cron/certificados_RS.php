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
define('INTERFACE_DETRAN_RS_CERTIFICADO', retornarInterface('detran_rs_certificado')['id']);
$siglaEstado = 'RS';
$matriculaObj = new Matriculas();
$transacoes = new Transacoes();
$coreObj = new Core;
if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idRioGrandeSul = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        e.detran_codigo,
        c.idcurso,
        c.carga_horaria_total,
        m.cod_ticket,
        m.renach,
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
        m.idmatricula in (20432 ) and
        m.cod_ticket IS NOT NULL
        -- AND m.renach IS NOT NULL
        AND e.detran_codigo IS NOT NULL
        -- AND m.renach <> ""
        AND e.detran_codigo <> ""
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idRioGrandeSul . '
        AND m.ativo = "S"
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);

while ($dados = mysql_fetch_assoc($query)) {

    try {
        $dataInicio = new DateTime($dados['data_inicio_curso']);
        $dataConclusao = new DateTime($dados['data_conclusao']);
    } catch (Exception $e) {
        return $e;
    }
    $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;
    $codCurso = $GLOBALS['detran_tipo_aula']['RS'][$dados['idcurso']];

    # Quando o Codigo do curso for contido em $cursos, o campo $codCertificadoEmpresa tem que substituir os caracteres
    # não numericos
    $cursos = ['019', '044', '014', '015', '016'];
    if (in_array(str_pad($codCurso, 3, '0', STR_PAD_LEFT), $cursos))
        $codCertificadoEmpresa =
            str_pad(
                soNumeros($dados['renach']),
                11,
                '0',
                STR_PAD_LEFT) . 'E' .
            str_pad(
                $codCurso,
                3,
                '0',
                STR_PAD_LEFT
            );
    else
        $codCertificadoEmpresa =
            $dados['renach'] . 'E' . str_pad($codCurso, 3, '0', STR_PAD_LEFT);
    
    // $codCertificadoEmpresa = '00141639032E014'; ///!!!!!!!!!!!!!!!!!!!!!!!!!
    
    $arrayEnvio = [
        'codTicket' => $dados['cod_ticket'],
        'codEmpresa' => $dados['detran_codigo'],
        'codCurso' => $codCurso,
        'cpfProfissional' => $config['detran']['RS']['registro_instrutor'],
        'cpfAluno' => $dados['documento'],
        // 'renach' => $dados['renach'],
        'renach' => '',
        'codCertificadoEmpresa' => $codCertificadoEmpresa,
        // 'dthInicio' => $dataInicio->format('Y-m-d H:i'),
        'dthInicio' => '2021-07-12 15:55',
        // 'dthFim' => $dataConclusao->format('Y-m-d H:i'),
        'dthFim' => '2021-07-15 00:00',
        'cargaHoraria' => $cargaHoraria,
    ];
    $_POST = json_encode($arrayEnvio);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['detran']['RS']['urlJSON'] . '/incluiCertificadoAluno');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'organizacao: ' . $config['detran']['RS']['organizacao'],
            'matricula: ' . $config['detran']['RS']['matricula'],
            'senha:' . $config['detran']['RS']['senha']
        )
    );
    $transacoes->iniciaTransacao(INTERFACE_DETRAN_RS_CERTIFICADO, 'E');

    $chResult = curl_exec($ch);
    $retorno = $chResult;
    $retornoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<br>Matrícula: ".$dados['idmatricula'];
    echo "<br>Envio:<br>";
    var_dump(json_encode($arrayEnvio));
    echo "<br>Retorno:<br>";
    var_dump($retorno);
    echo "<br><br>";

    if ($retorno === false) {
        if ($dados['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
        $transacoes->set("json", $retorno);
        $transacoes->finalizaTransacao(null, 3);
        $transacoes->set("json", null);
        return false;
    }

    $coreObj->executaSql('BEGIN');
    switch ($retornoHttpCode) {
        case 200:
            $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
            $coreObj->executaSql($sql);

            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
            $transacoes->set("json", $retorno);
            $transacoes->finalizaTransacao(null, 3);
            $transacoes->set("json", null);
            break;
        case 400:
            $transacoes->set("json", $retorno);
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set("json", null);

            break;
    }

    salvarLogDetran($matriculaObj, 427, $dados['idmatricula'], $retorno, $_POST);
    $coreObj->executaSql('COMMIT');
}
