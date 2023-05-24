<?php

include("../classes/sindicatos.class.php");
$sindicatoObj = new sindicatos();
$sindicatoObj->Set("pagina", 1);
$sindicatoObj->Set("ordem", "desc");
$sindicatoObj->Set("limite", -1);
$sindicatoObj->Set("ordem_campo", "idsindicato");
$sindicatoObj->Set("campos", "i.*, m.nome_fantasia as mantenedora");
$sindicatoArray = $sindicatoObj->retornarSindicatosUsuario($retornarRegiao = false);

include("../classes/obz.orcamentorealizado.class.php");
include("config.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Orcamento_Realizado();
$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("config", $config);
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$getValido = false;
if(!empty($_GET["idsindicato"])
    && !empty($_GET["idcentro_custo"])
    && !empty($_GET["idcategoria"])
    && !empty($_GET["idsubcategoria"])
    && !empty($_GET["ano"])){

    $getValido = true;
}

if($getValido){
    $permissaoInstituicao = $linhaObj->verificaPermissaoInstituicao($sindicatosArray, (int)$_GET["idsindicato"]);
    $centrosCustos = $linhaObj->retornarCentrosCusto((int)$_GET["idsindicato"], $usuario["idusuario"]);
    $permissaoCentroCusto = $linhaObj->verificaPermissaoCentroCusto($centrosCustos, (int)$_GET["idcentro_custo"], $usuario["idusuario"]);
    $categorias = $linhaObj->retornarCategorias((int)$_GET["idsindicato"], (int)$_GET["idcentro_custo"]);
    $permissaoCategoria = $linhaObj->verificaPermissaoCategorias($categorias, (int)$_GET["idcategoria"]);
    $subCategorias = $linhaObj->retornarSubCategorias((int)$_GET["idsindicato"], (int)$_GET["idcentro_custo"], (int)$_GET["idcategoria"]);
    $permissaoSubCategoria = $linhaObj->verificaPermissaoSubCategorias($subCategorias, (int)$_GET["idsubcategoria"]);



    if(empty($centrosCustos)){
        $categorias = null;
    }

    if(empty($categorias)){
       $subCategorias = null;
    }
}

if(isset($_POST['acao']) and $_POST['acao'] == 'salvarjustificativa' and is_numeric($_POST['idorcamento'])){

	$linhaObj->set("idorcamento", $_POST['idorcamento']);
	$linhaObj->set("post", $_POST);
	$salvar = $linhaObj->salvarJustificativa();
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
		$linhaObj->Set("url",$_POST['urlcaminho']);
		$linhaObj->Processando();
		exit;
	}
}


$meses = array("mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12");

if ($getValido) {

    $linhaObj->Set("idsindicato", intval($_GET["idsindicato"]));
    $linhaObj->Set("idcentro_custo", intval($_GET["idcentro_custo"]));
    $linhaObj->Set("idcategoria", intval($_GET["idcategoria"]));
    $linhaObj->Set("idsubcategoria", intval($_GET["idsubcategoria"]));
    $linhaObj->Set("ano", intval($_GET["ano"]));


        $dadosArray = $linhaObj->ListarTodas();

}


if($url[3] == "json"){
    switch($url[4]){
        case "centros_custo":
            if($_GET["idsindicato"]){
                $linhaObj->set("json", true);
                $linhaObj->retornarCentrosCusto((int)$_GET["idsindicato"], (int)$usuario["idusuario"]);
            }
        exit;
        break;
        case "categorias":

            if($_GET["idcentro_custo"] && $_GET["idsindicato"]){
                $linhaObj->set("json", true);
                $linhaObj->retornarCategorias((int)$_GET["idsindicato"], (int)$_GET["idcentro_custo"]);
            }
        exit;
        break;
        case "subcategorias":
            if($_GET["idcentro_custo"] && $_GET["idsindicato"] && $_GET["idcategoria"]){
                $linhaObj->set("json", true);
                $linhaObj->retornarSubCategorias((int)$_GET["idsindicato"], (int)$_GET["idcentro_custo"], (int)$_GET["idcategoria"]);
            }
        exit;
        break;
    }
}elseif(is_numeric($url[3])){
	 switch($url[4]){
        case "justificativa":
			$linhaObj->set("id", $url[3]);
			$orcamento = $linhaObj->retornarOrcamento();
            include("idiomas/" . $config["idioma_padrao"] . "/justificativa.php");
			include("telas/" . $config["tela_padrao"] . "/justificativa.php");
        	exit ;
		break;
    }
}

include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");
