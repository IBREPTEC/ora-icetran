<?php
if ($url[5] == "associar_sindicatos") {
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("get", $_GET);
    echo $linhaObj->BuscarSindicatos();
    exit;
}

if ($url[5] == "solicita_centro") {
    
    $linhaObj2->Set("id", intval($url[3]));
    $linhaObj2->Set("get", $_GET);
    echo $linhaObj2->BuscarCentrosDeCusto();
    exit;
}
?>