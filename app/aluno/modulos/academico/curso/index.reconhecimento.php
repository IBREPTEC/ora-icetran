<?php
$reconhecimentoObj = new Reconhecimento();

switch ($_POST['acao']) {
    case 'cadastrarFoto':
		if($_POST['saindoSistema'] == 'S'){
        require '../classes/reconhecimento/Net/URL2.php';
        require '../classes/reconhecimento/HTTP/Request2.php';

            $reconhecimentoObj->compararFotos();
            break;
        }
	
        $reconhecimentoObj->registrarImagemPrincipal();
        break;

    case 'compararFoto':
        require '../classes/reconhecimento/Net/URL2.php';
        require '../classes/reconhecimento/HTTP/Request2.php';
        // $boolDV = $url[5] != "avaliacoes" ? true : false;
        $reconhecimentoObj->compararFotos();
        break;
    
    default:
        # code...
        break;
}