<?php

include("../classes/sindicatos.class.php");
$sindicatosObj = new Sindicatos();
$sindicatosObj->Set("pagina", 1);
$sindicatosObj->Set("ordem", "desc");
$sindicatosObj->Set("limite", -1);
$sindicatosObj->Set("ordem_campo", "idsindicato");
$sindicatosObj->Set("campos", "i.*, m.nome_fantasia as mantenedora");
$sindicatosArray = $sindicatosObj->retornarSindicatosUsuario($retornarRegiao = false);




include("../classes/obz.orcamentosgastos.class.php");
include("config.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Orcamentos_Gastos_OBZ();
$linhaObj->Set("idusuario", $usuario["idusuario"]);
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

//    if(!$permissaoInstituicao){
//       $salvar["erros"][] = "sem_premissao_instituicao";
//    }elseif(!$permissaoCentroCusto){
//       $salvar["erros"][] = "sem_premissao_centro_custo";
//       $centrosCustos = null;
//    }elseif(!$permissaoCategoria){
//        $salvar["erros"][] = "sem_premissao_categoria";
//        $categorias = null;
//    }elseif(!$permissaoSubCategoria){
//       $salvar["erros"][] = "sem_premissao_subcategoria";
//       $subCategorias = null;
//    }

    if(empty($centrosCustos)){
        $categorias = null;
    }

    if(empty($categorias)){
       $subCategorias = null;
    }
}

if ($_POST["acao"] == "salvar") {
    //print_r2($_POST);exit;
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    if (!empty($_GET["idsindicato"]) && !empty($_GET["idcentro_custo"]) && !empty($_GET["idcategoria"]) && !empty($_GET["idsubcategoria"]) && !empty($_GET["ano"])) {
        if($linhaObj->verificarPeriodoInstituicao($_GET["idsindicato"])){
            $linhaObj->Set("post", $_POST);
            $salvar = $linhaObj->cadastrarModificar();
            if ($salvar["sucesso"]) {
                $linhaObj->Set("pro_mensagem_idioma", "cadastrar_modificar_sucesso");
                $get = "?idsindicato=" . $_GET["idsindicato"] . "&ano=" . $_GET["ano"] . "&idcentro_custo=" . $_GET["idcentro_custo"] . "&idcategoria=" . $_GET["idcategoria"] . "&idsubcategoria=" . $_GET["idsubcategoria"];
                $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . $get);
                $linhaObj->Processando();
            }
        }else{
            $salvar["erro"] = true;
            $salvar["erros"][] = "instituicao_fora_periodo";
        }
    }else{
            $salvar["erro"] = true;
            $salvar["erros"][] = "campos_vazios";
        }
    }

$meses = array("mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12");

if ($getValido) {

    $linhaObj->set("idsindicato", $_GET["idsindicato"]);
    $linhaObj->set("idcentro_custo", $_GET["idcentro_custo"]);
    $linhaObj->set("idcategoria", $_GET["idcategoria"]);
    $linhaObj->set("idsubcategoria", $_GET["idsubcategoria"]);
    $linhaObj->set("ano", $_GET["ano"]);
        $dadosArray = $linhaObj->retornarPolos();

        if($dadosArray){
            foreach($dadosArray as $key => $dado){
                foreach($meses as $mes){
                      $dadosArray["totalmes"][$mes] += $dado[$mes];
                      $totalMes += $dado[$mes];
                }
                foreach($dado as $index => $value){
                    if(in_array($index, $meses)){
                       $dadosArray[$key]["total"] += $value;
                       $total += $value;
                    }
                }
            }
            $totalGeral = ($total + $totalMes) / 2;
        }

}

if ($getValido) {
    include '../classes/escola.class.php';
    $poloObj = new escolas();
    $poloObj->set("campos", "e.nome_fantasia,e.idescola");
    $poloObj->set("idsindicato", $_GET["idsindicato"]);
    $_GET["q"]['1|e.ativo_painel'] = "S";
        $polos = $poloObj->ListarTodas();


}

if(!empty($dadosArray[0]["idsindicato"]) && !empty($dadosArray[0]["idcentro_custo"]) && !empty($dadosArray[0]["idcategoria"]) && !empty($dadosArray[0]["idsubcategoria"])){
        $categorias = $linhaObj->retornarCategorias((int)$dadosArray[0]["idsindicato"], (int)$dadosArray[0]["idcentro_custo"]);
        $subCategorias = $linhaObj->retornarSubCategorias((int)$dadosArray[0]["idsindicato"], (int)$dadosArray[0]["idcentro_custo"], (int)$dadosArray[0]["idcategoria"]);

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
}

include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");
