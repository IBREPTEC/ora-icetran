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

define('INTERFACE_DETRAN_PE_CANCELAMENTO', retornarInterface('detran_pe_cancelamento')['id']);


$siglaEstado = 'PE';
$codTransacao = 430;//Cadastro certificado

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
        m.idmatricula in (25780 ) and 
        e.detran_codigo IS NOT NULL
        AND cw.cancelada = "N"
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
    25780  => [
        'dt_inicio' => '2021-09-13',
        'dt_fim' => '2021-09-13'
    ]
]; 
while ($dados = mysql_fetch_assoc($query)) {


        $aula = (!empty($GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']])) ? $GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']] : $GLOBALS['detran_tipo_aula']['PE'][$dados['idcurso']]; //Olhe a regra no card AWDE-1241 para entender o porquê disso.
        $arrayEnvio = [
            'Cpf' => $dados['documento'],
            'Nascimento' => $dados['data_nasc'],
            'Curso' => $aula['codigo'],
            'Modulo' => $aula['modulo'],
            'Cnpj' => $config['detran']['PE']['registro_empresa'],
            // 'Inicio' => (new DateTime($dados['data_inicio_curso']))->format('Y-m-d')
            'Inicio' => $arrData[$dados['idmatricula']]['dt_inicio'],
        ];

        $_POST = json_encode($arrayEnvio);


        $transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_CANCELAMENTO, 'S', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['detran']['PE']['urlJSON'] . '/CursoDistanciaCancelar');
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

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retorno = json_decode(json_decode($chResult, true)['CursoDistanciaCancelarResult'], true);

        echo "<br>Matrícula: ".$dados['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($arrayEnvio));
        echo "<br>Retorno:<br>";
        var_dump(json_encode($retorno));
        var_dump($chResult);
        echo "<br><br>";

        $detranObj->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $retorno[0]['sMsg'];
        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_cancelamento = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $detranObj->executaSql($sql);

                $matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_cancelamento', 'modificou', 'N', 'S', null);
                $transacoes->set("json", json_encode($retorno));
                $transacoes->finalizaTransacao(null, 2);
                $transacoes->set("json", null);
                break;
            case 1:
                $transacoes->set("json", $mensagem);
                $transacoes->finalizaTransacao(null, 5);
                $transacoes->set("json", null);
                $retornoDetran['erro'] = true;
                break;
        }

        salvarLogDetran($matriculaObj, 430, $dados['idmatricula'], $mensagem, $_POST);
        $detranObj->executaSql('COMMIT');
}
