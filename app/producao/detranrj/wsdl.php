<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$wsdl = "http://leao:8080/wsstack/services/ENSINODI.wsdl";

$client = new SoapClient($wsdl,array(	
	'cache_wsdl' 		=> WSDL_CACHE_NONE,
	'trace' 			=> 1,
	'exceptions' 		=> 1,
));

$auth = array('exx-natural-security'=>'TRUE',
			'exx-natural-library'=>'LIBBRK',
			'exx-rpc-userID'=> '<USUARIO>', 
			'exx-rpc-password'=> '<SENHA>',
		); 
$authvar = new SoapVar($auth, SOAP_ENC_OBJECT ); 

$header=new SoapHeader('urn:com.softwareag.entirex.xml.rt','EntireX',$authvar);
$client->__setSoapHeaders($header);

echo 'Funcoes <br>';
exibeFuncoes();


/*
* preparando parametros para chamar GTR
*/
$fixa['sequencial'] = '000001';
$fixa['cod-transacao'] = '999';
$fixa['modalidade'] = '1';
$fixa['cliente'] = str_pad('CLIENTE',11); // variavel livre
$fixa['uf-transa'] = 'RJ';
$fixa['uf-origem'] = 'RJ';
$fixa['uf-destino'] = 'RJ';
$fixa['tipo'] = '1';
$fixa['tamanho'] = '0000';
$fixa['retorno'] = '00';
$fixa['juliano'] = str_pad(date('z')+1, 3, '0', STR_PAD_LEFT);	


$arr_variavel['placa'] = str_pad('ABC1234', 7);
$arr_variavel['chassi'] = str_pad('', 24);	
$arr_variavel['outro_parametro'] = '1';

$string_tripa = str_pad(implode('',$arr_variavel), 3237);
for($i=0; $i<13; $i++){
	$indice = $i * 249;
	$variavel[$i] = substr($string_tripa, $indice, 249);
}


$parteFixa = implode('',$fixa);
$parteVariavel = $variavel;

$parametros['PARTE-FIXA'] = $parteFixa;
$parametros['PARTE-VARIAVEL'] = $parteVariavel;

$retorno = $client->GTRN0003($parametros);	
print_r($retorno);
	



function exibeFuncoes()
{
	global $client;
	
	$funcs = $client->__getfunctions();

	$total = count($funcs);
	foreach($funcs as $k => $sub)
	{	
		if($k == $total/2)
			break;
		$out[] = $sub;
	}
	sort($out);
	foreach($out as $k => $sub)
	{	
		$arr = explode('Response', $sub);
		echo $arr[0] . '<br>';
	}
}











exit();

//echo file_get_contents("http://10.200.180.71:8080/wsstack/services/ENSINODI.wsdl?WSDL");
//echo file_get_contents("http://10.250.104.10:8080/wsstack/services/ENSINODI.wsdl?WSDL");

echo time();
echo '<br>';

$url = "http://10.250.104.15:8080/wsstack/services/ENSINODI.wsdl"; // ?WSDL

echo $url;
echo '<br>';

$cr = curl_init();   
curl_setopt($cr, CURLOPT_URL, $url);   
curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($cr, CURLOPT_POST, TRUE);   
curl_setopt($cr, CURLOPT_POSTFIELDS, "teste=1");   
$retorno = curl_exec($cr);   
curl_close($cr); 
echo $retorno;

$client = new SoapClient($url);
var_dump($client->__getFunctions());