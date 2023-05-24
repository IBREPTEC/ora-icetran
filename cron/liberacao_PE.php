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
$coreObj = new Core;
$siglaEstado = 'PE';
$codTransacao = 424;//Envio de créditos de aula
$valorResultado = "CursoDistanciaIniciarResult";

$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();

$sql = 'SELECT
        m.idmatricula, p.documento,  p.data_nasc, e.detran_codigo, o.idoferta, c.idcurso, e.idescola,
        (
            SELECT
                COUNT(d.iddisciplina)
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = "S")
            WHERE
                oce.idoferta = o.idoferta AND
                oce.idcurso = c.idcurso AND
                oce.idescola = e.idescola AND
                d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
        ) AS total_disciplinas,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mh.para = ' . $situacaoEmCurso['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        m.idmatricula in (25780) and
        c.idcurso IN (' . implode(',', array_keys($GLOBALS['detran_tipo_aula'][$siglaEstado])) . ') AND
        e.idestado = ' . $estadosDetran[$siglaEstado] . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "AL" AND
        m.detran_creditos = "N" AND
        e.detran_codigo IS NOT NULL AND
        cw.ativa = "S" AND
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mw.ativa = "S"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) IS NOT NULL
    ORDER BY data_ultimo_historico ASC
    limit 10
';
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
while ($linha = mysql_fetch_assoc($query)) {
    try {
        $arrayEnvio = [
            'Cpf' => $linha['documento'],
            'Nascimento' => $linha['data_nasc'],
            'Curso' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]['codigo'],
            'Modulo' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]['modulo'],
            'Cnpj' => $config['detran'][$siglaEstado]['registro_empresa'],
            'CpfInstrutor' => $config['detran'][$siglaEstado]['registro_instrutor'],
            // 'Inicio' => (new \DateTime($linha['data_inicio_curso']))->format('Y-m-d'),
            'Inicio' => $arrData[$linha['idmatricula']]['dt_inicio'],
            // 'InicioModulo' => (new \DateTime($linha['data_inicio_curso']))->format('Y-m-d'),
            'InicioModulo' => $arrData[$linha['idmatricula']]['dt_inicio']
        ];
        $_POST = json_encode($arrayEnvio);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['detran'][$siglaEstado]['urlJSON'] . '/CursoDistanciaIniciar');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );

        $chResult = curl_exec($ch);
        curl_close($ch);
        
        echo "<br>Matrícula: ".$linha['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($arrayEnvio));

        if ($chResult === false) {
            echo "<br>Não respondeu<br><br>";
            if ($linha['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            return;
        }

        $retorno = json_decode(json_decode($chResult, true)[$valorResultado], true);

        echo "<br>Retorno:<br>";
        var_dump(json_encode($retorno));
        var_dump($chResult);
        echo "<br><br>";
        $matriculaObj->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        switch ($retorno[0]['nErro']) {
            case 0:

                $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                break;
            case 1:
            default:

                $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
                break;
        }
        
        
        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $mensagem, $_POST);
        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}
