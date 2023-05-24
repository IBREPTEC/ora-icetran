<?php

include("../classes/obz.usuarios.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Usuarios_OBZ();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);

if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

    $linhaObj->Set("post", $_POST);
    if ($_POST[$config["banco"]["primaria"]] && $linhaObj->verificaExistencia($url[3])){
        $salvar = $linhaObj->Modificar();
    }else{
        unset($linhaObj->config["banco"]["primaria"]);
        $salvar = $linhaObj->Cadastrar();
    }
    
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        }
        $linhaObj->Processando();
    }
}
     if (isset($url[4])) { 
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "u.nome, u.email, u.idusuario, oua.gestor_centro_custo, oua.comite_obz");
        $linha = $linhaObj->Retornar();
        if (is_array($linha))
            $linha = array_map(stripslashes, $linha);

        if ($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "json":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                default:
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
                    exit();
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
            exit();
        }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = "u.idusuario";
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "u.*, p.nome as perfil");
    $dadosArray = $linhaObj->ListarTodas();

    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}