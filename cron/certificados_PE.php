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

define('INTERFACE_DETRAN_PE_CERTIFICADO', retornarInterface('detran_pe_certificado')['id']);

$siglaEstado = 'PE';
$codTransacao = 427;//Cadastro certificado
$valorResultado = "CursoDistanciaConcluirResult";

$matriculaObj = new Matriculas();
$detranObj = new Detran();
$transacoes = new Transacoes();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();


if (is_array($detran_tipo_aula[$siglaEstado])) {
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idPernambuco = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula, m.data_matricula, p.documento, p.data_nasc, e.detran_codigo, c.idcurso, frdm.idfolha_matricula,
       m.idoferta, m.idcurso, m.idescola, e.documento as documentoCfc,
        (
            SELECT
                oca.idava
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND
                                                           oca.idcurriculo = oce.idcurriculo AND
                                                           oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina
                                                 AND d.ativo = "S")
            WHERE
                oce.idoferta = o.idoferta AND
                oce.idcurso = c.idcurso AND
                oce.idescola = e.idescola AND
                d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
            LIMIT 1
        ) AS idava,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT
                mh.data_cad
            FROM matriculas_historicos mh
            WHERE
                mh.idmatricula = m.idmatricula AND
                mh.tipo = "situacao" AND mh.para = ' . $situacaoEmCurso['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso,
        (
            SELECT
                mh.data_cad
            FROM matriculas_historicos mh
            WHERE
                mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND
                mh.para = ' . $situacaoConcluido['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_conclusao
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S")
    WHERE
        m.idmatricula in (25780) and 
        e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        AND m.detran_creditos = "S"
        AND m.detran_finalizar = "S"
        AND cw.fim = "S"
        '.$cursosIn.'
        AND e.idestado = ' .$idPernambuco. '
        AND m.ativo = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);

$arrData = [
    5076 => [
        'dt_inicio' => '2021-02-22',
        'dt_fim' => '2021-03-02'
    ], 
    6068 => [
        'dt_inicio' => '2021-03-03',
        'dt_fim' => '2021-05-18'
    ], 
    7045 => [
        'dt_inicio' => '2021-03-11',
        'dt_fim' => '2021-05-19'
    ], 
    7081 => [
        'dt_inicio' => '2021-03-10',
        'dt_fim' => '2021-05-13'
    ], 
    9021 => [
        'dt_inicio' => '2021-04-10',
        'dt_fim' => '2021-05-03'
    ], 
    9178 => [
        'dt_inicio' => '2021-04-06',
        'dt_fim' => '2021-05-12'
    ], 
    9363 => [
        'dt_inicio' => '2021-04-08',
        'dt_fim' => '2021-04-15'
    ], 
    9448 => [
        'dt_inicio' => '2021-04-13',
        'dt_fim' => '2021-04-16'
    ], 
    9449 => [
        'dt_inicio' => '2021-04-19',
        'dt_fim' => '2021-05-18'
    ], 
    10130 => [
        'dt_inicio' => '2021-04-19',
        'dt_fim' => '2021-05-12'
    ], 
    10510 => [
        'dt_inicio' => '2021-04-19',
        'dt_fim' => '2021-04-22'
    ], 
    7405 => [
        'dt_inicio' => '2021-03-12',
        'dt_fim' => '2021-03-17'
    ], 
    9028 => [
        'dt_inicio' => '2021-04-06',
        'dt_fim' => '2021-05-12'
    ], 
    7081 => [
        'dt_inicio' => '2021-03-10',
        'dt_fim' => '2021-05-13'
    ], 
    25780 => [
        'dt_inicio' => '2021-09-13',
        'dt_fim' => '2021-09-13'
    ]
]; 
while ($dados = mysql_fetch_assoc($query)) {

        $matriculaObj->set("id", $dados['idmatricula']);
        $matriculaObj->set("matricula", $dados);
        $matricula["curriculo"] = $matriculaObj->RetornarCurriculo();
        $matricula["disciplinas"] = $matriculaObj->RetornarDisciplinas($matricula["curriculo"]['media']);

        $quantDisciplinas = 0;
        $notas = 0;
        foreach ($matricula["disciplinas"] as $disciplina) {
            if ($disciplina['ignorar_historico'] == "S"
                || $disciplina['contabilizar_media'] == "N") {
                continue;
            }
            $quantDisciplinas++;
            $notas += $disciplina['situacao']['valor'];
        }
        $mediaFinal = (float)($notas / $quantDisciplinas);
        $id_cursos = [57, 55, 59, 58];
        $aula = (!empty($GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']])) ? $GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']] : $GLOBALS['detran_tipo_aula']['PE'][$dados['idcurso']]; //Olhe a regra no card AWDE-1241 para entender o porquê disso.
        $arrayEnvio = [
            'Cpf' => $dados['documento'],
            'Nascimento' => $dados['data_nasc'],
            'Curso' => $aula['codigo'],
            'Modulo' => $aula['modulo'],
            'Cnpj' => $config['detran']['PE']['registro_empresa'],
            'CpfInstrutor' => $config['detran']['PE']['registro_instrutor'],
            // 'Inicio' => (new DateTime($dados['data_inicio_curso']))->format('Y-m-d'),
            'Inicio' => $arrData[$dados['idmatricula']]['dt_inicio'],
            // 'Conclusao' => (new DateTime($dados['data_conclusao']))->format('Y-m-d'),
            'Conclusao' => $arrData[$dados['idmatricula']]['dt_fim'],
            // 'Validade' => (new DateTime($dados['data_conclusao']))->modify("+5 year")->format("Y-m-d"), //+5 anos
            'Validade' => (new DateTime($arrData[$dados['idmatricula']]['dt_fim']))->modify("+5 year")->format("Y-m-d"), //+5 anos
            'Nota' => (float)number_format($mediaFinal, 2, '.', ''),
            'Certificado' => $dados["idfolha_matricula"],
            'CnpjCfc' => in_array($aula['codigo'], $id_cursos) ? $dados['documentoCfc'] : 0
        ];

        $_POST = json_encode($arrayEnvio);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['detran']['PE']['urlJSON'] . '/CursoDistanciaConcluir');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_CERTIFICADO, 'E');
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            return;
        }

        $retorno = json_decode(json_decode($chResult, true)['CursoDistanciaConcluirResult'], true);
        echo "<br>Matrícula: ".$dados['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($arrayEnvio));
        echo "<br>Retorno:<br>";
        var_dump(json_encode($retorno));
        var_dump($chResult);
        echo "<br><br>";

        $detranObj->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg'], 'retorno' => $retorno[0]['sCertificadoRetorno']]);

        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $detranObj->executaSql($sql);

                $matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $transacoes->set("json", $retorno);
                $transacoes->finalizaTransacao(null, 2);
                $transacoes->set("json", null);
                break;
            case 1:
                $transacoes->set("json", $mensagem);
                $transacoes->finalizaTransacao(null, 5);
                $transacoes->set("json", null);
        }

        salvarLogDetran($matriculaObj, 427, $dados['idmatricula'], $mensagem, $_POST);
        $detranObj->executaSql('COMMIT');

}
