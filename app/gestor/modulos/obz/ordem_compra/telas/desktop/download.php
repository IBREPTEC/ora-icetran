<?php
header("Content-type: ".$arquivo["tipo"]);
header('Content-Disposition: attachment; filename="'.basename($arquivo["nome"]).'"');
header('Content-Length: '.$arquivo["tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/obz_ordemcompra_arquivos/".$arquivo["servidor"]);
?>