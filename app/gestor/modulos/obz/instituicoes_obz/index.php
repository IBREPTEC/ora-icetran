<?php 
include('../classes/obz.sindicatos.class.php');
include('config.php');
include('config.formulario.php');
include('config.listagem.php');
	
//Incluimos o arquivo com variaveis padrão do sistema.
include('idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php');
	
$linhaObj = new Sindicatos_OBZ();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');
	
$linhaObj->Set('idusuario',$usuario['idusuario']);
$linhaObj->Set('monitora_onde',$config['monitoramento']['onde']);


if ($_POST['acao'] == 'salvar') {
	$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');

	if($_FILES) {
		foreach($_FILES as $ind => $val) {
			$_POST[$ind] = $val;
		}
	}

	$linhaObj->Set('post',$_POST);
	if ($_POST[$config['banco']['primaria']] && $linhaObj->verificaExistencia($url[3])) {
		$salvar = $linhaObj->Modificar();
	} else {
		unset($linhaObj->config['banco']['primaria']);
		$salvar = $linhaObj->Cadastrar();
	}

	if ($salvar['sucesso']) {
		$linhaObj->Set('pro_mensagem_idioma','modificar_sucesso');
		$linhaObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4]);
		$linhaObj->Processando();
	}
}

if (isset($url[4])) {
	$linhaObj->Set('id', (int) $url[3]);
	$linhaObj->Set('campos', 'i.idsindicato,
							i.nome,
							i.nome_abreviado,
							oi.periodo_de,
							oi.periodo_ate');
	$linha = $linhaObj->Retornar();

	if($linha) {
		switch ($url[4]) {
			case 'editar':
				$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
				include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
				include('telas/'.$config['tela_padrao'].'/formulario.php');
				break;
			case 'opcoes':
				include('idiomas/'.$config['idioma_padrao'].'/opcoes.php');
				include('telas/'.$config['tela_padrao'].'/opcoes.php');
				break;
			default:
				header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
				exit();
		}	
	} else {
		header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
		exit();
	}			
} else {
	$linhaObj->Set('pagina',$_GET['pag']);
	$linhaObj->Set('campos','i.*,
							m.nome_fantasia as mantenedora');
    $linhaObj->Set('ordem_campo','i.ativo_painel');
    $linhaObj->Set('ordem','asc');
    $linhaObj->Set('limite',($_GET['qtd']) ? $_GET['qtd'] : 30);
    $dadosArray = $linhaObj->ListarTodas();

	include('idiomas/'.$config['idioma_padrao'].'/index.php');
	include('telas/'.$config['tela_padrao'].'/index.php');
}