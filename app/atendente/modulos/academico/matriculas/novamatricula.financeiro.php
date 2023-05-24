<?php
unset($_SESSION["matricula"]["idvendedor"]);
$_SESSION["matricula"]["idvendedor"] = $usu_vendedor["idvendedor"];

unset($_SESSION["matricula"]["pessoa"]);
$_SESSION["matricula"]["pessoa"] = $_POST;

include("../classes/ofertas.class.php");
$ofertaObj = new Ofertas();
$escola = $ofertaObj->retornarCursoEscola($url[6]);

$turma = $ofertaObj-> retornarDadosTurma($url[7]);

include("../classes/vendedores.class.php");
$vendedorObj = new Vendedores();
$vendedorObj->Set("id",$_SESSION["matricula"]["idvendedor"]);
$vendedorObj->Set("campos","nome");	
$vendedor = $vendedorObj->Retornar();

require("novamatricula.seguranca.php");
include("config.formulario.php");
$matriculaObj->Set("config",$config);

include("idiomas/".$config["idioma_padrao"]."/novamatricula.financeiro.php");
include("telas/".$config["tela_padrao"]."/novamatricula.financeiro.php");
exit;
?>