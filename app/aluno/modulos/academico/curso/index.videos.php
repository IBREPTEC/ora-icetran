<?php

if($url[5]  == "videoteca") {

    $pastas = $matriculaObj->retornarAvaVideo($url[4]);

}
    require 'telas/'.$config['tela_padrao'].'/videoteca.php';
    exit;
