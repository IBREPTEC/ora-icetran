<?php
if ($url[5] == 'ajax_centro_custos') {
    if ($_REQUEST['idescola']) {
        echo $linhaObj->listarCentrosCustoJson($_REQUEST['idescola']);
    } else {
        echo $linhaObj->listarCentrosCustoJson($url[6]); 
    }
} elseif ($url[5] == 'ajax_categorias') {
    if ($_REQUEST['idescola']) {
        echo $linhaObj->listarCategoriasJson($_REQUEST['idescola']);
    } else {
        echo $linhaObj->listarCategoriasJson($url[6]); 
    }
} elseif ($url[5] == 'ajax_subcategorias') {
    if ($_REQUEST['idcategoria']) {
        echo $linhaObj->listarSubCategoriasJson($_REQUEST['idcategoria']);
    } else {
        echo $linhaObj->listarSubCategoriasJson($url[6]); 
    }
}