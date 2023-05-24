<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Matricula.php';
$funcoesComuns = new \OrIO\FuncoesComuns();

$funcoesComuns->adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

$token = $funcoesComuns->getHeaderToken();

if (!isset($token)) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '401', 'mensagem' => $idioma['erro_token_nao_informado']];
    echo json_encode($retorno);
    exit;
}

if (empty($_POST['iddisciplina']) || empty($_POST['idprova_presencial']) || empty($_POST['idmatricula'])) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '401', 'mensagem' => $idioma['param_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$matriculaObj = new Matricula($funcoesComuns);
$transacoesObj = new Transacoes();

define('INTERFACE_SOLICITAR_PROVA', retornarInterface('matricula_solicitar_prova')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_SOLICITAR_PROVA, 'E');

try {
    $funcoesComuns->adicionarCabecalhoJson();

    $retorno = [];
    $retorno['codigo'] = 200;
    $aluno = $funcoesComuns->autenticarPessoaPorToken($token);

    $matriculaObj->idmatricula = $_POST['idmatricula'];
    $matriculaObj->idpessoa = $aluno['idpessoa'];
    $_POST['disciplinas'] = is_array($_POST['iddisciplina']) ? $_POST['iddisciplina'] : [$_POST['iddisciplina']];
    $matriculaObj->post = $_POST;

    $salvar = $matriculaObj->salvarSolicitacaoProvaPresencial();

    if ($salvar) {
        $retorno['dados'] = ['codigo' => '200', 'mensagem' => $idioma['prova_solicitar_sucesso']];
    }

    $transacoesObj->finalizaTransacao(null, 2);
    $funcoesComuns->adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    $transacoesObj->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
    $funcoesComuns->adicionarCabecalhoJson($retorno['codigo']);
    echo json_encode($retorno);
}
