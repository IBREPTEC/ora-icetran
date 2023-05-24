<?php
if($_SERVER['HTTP_EMAIL'] == 'timesistemas@alfamaweb.com.br' and $_SERVER['HTTP_SENHA'] == '@sis@lph@'){
    /* Informa o nível dos erros que serão exibidos */
    error_reporting(E_ALL);

    /* Habilita a exibição de erros */
    ini_set("display_errors", 1);
}
$diretorio = dirname(__FILE__);
require_once DIR_APP . '/classes/core.class.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/Matricula.php';

adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_SERVER['HTTP_EMAIL']) || !isset($_SERVER['HTTP_SENHA'])) {
    adicionarCabecalhoJson();
    $retorno = ['codigo' => '400', 'mensagem' => $idioma['erro_parametros_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$cabecalho = $_SERVER;

$matriculaObj = new Matricula(new Core());

$email_escape = addslashes(strtolower($_SERVER['HTTP_EMAIL']));
$senha = senhaSegura($_SERVER['HTTP_SENHA'], $config['chaveLogin']);

try {
    $usuario = $matriculaObj->autenticar($email_escape, $senha);
    $matriculaObj->idusuario = $usuario['idusuario'];
    $matriculaObj->config =  $config;
    $dadosMatricula = json_decode(file_get_contents('php://input'), true);

    $retorno = $matriculaObj->cadastrar($dadosMatricula);

    adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    $codHeader = $e->getCode();
    adicionarCabecalhoJson($codHeader);
    echo json_encode($retorno);
}
