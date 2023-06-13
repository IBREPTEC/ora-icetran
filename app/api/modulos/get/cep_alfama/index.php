<?php
//$ch = curl_init();

// informar URL e outras funções ao CURL
//curl_setopt($ch, CURLOPT_URL, "http://cep.construtor.alfamaweb.com.br/cep.php");
//curl_setopt($ch, CURLOPT_URL, "https://construtor.de/vendas/cep.php");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Faz um POST
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

// Acessar a URL e retornar a saída
//$output = curl_exec($ch);
// liberar
//curl_close($ch);

// Imprimir a saída
//echo $output;



$url = 'https://construtor.de/vendas/cep.php?env=' . $config["database"];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $_POST
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Imprimir a saída
echo $response;