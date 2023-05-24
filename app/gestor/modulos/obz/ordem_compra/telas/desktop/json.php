<?php
if($url[5] == "escola") {
	echo json_encode($linhaObj->RetornarPoloInstituicao(intval($_GET["idsindicato"])));
} else if($url[5] == "centrocusto") {
	echo json_encode($linhaObj->RetornarCentroDeCustoInstituicao(intval($_GET["idsindicato"])));
} else if($url[5] == "categoria") {
	echo json_encode($linhaObj->RetornarCategoriaInstituicao(intval($_GET["idsindicato"]) , intval($_GET["idcentro_custo"])));
} else if($url[5] == "subcategoria") {
	echo json_encode($linhaObj->RetornarSubCategoriaInstituicao(intval($_GET["idsindicato"]),intval($_GET["idcategoria"]),intval($_GET["idcentro_custo"])));
} else if($url[5] == "resposta_automatica") {
	echo $linhaObj->listarRespostasAutomaticas($_POST['id']);
} else if($url[5] == "excluir_arquivo") {
	echo $linhaObj->ExcluirArquivo1($_POST["idarquvio"], "atendimentos","arquivos");
}
?>
