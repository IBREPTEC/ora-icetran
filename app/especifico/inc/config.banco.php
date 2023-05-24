<?php
//$config["host"]     = "localhost";
//$config["usuario"]  = "root";
//$config["senha"]    = "root";
//$config["database"] = "oraculo_icetran";
// $config["database"] = "oraculo_icetran";

//$config["host"]     = "177.47.183.71";
//$config["usuario"] = "tech";
//$config["senha"] = 'Wsj9b67@3';
//$config["database"] = "ibreptran_oraculo";

$config["host"]  = "localhost";
$config["usuario"] = "root";
$config["senha"] = "root";
$config["database"] = "oraculo_ibreptran";


//$config["host"]     = "177.47.183.17";
//$config["usuario"] = "devtransito";
//$config["senha"]    = "xny50^V08";
//$config["database"] = "devtransito";
$config["driver"] = "pdo_mysql";



@mysql_connect($config["host"],$config["usuario"],$config["senha"]);
@mysql_select_db($config["database"]);

// Usado para a codificação UTF-8
// Referência: http://phpbrasil.com/artigo/11qDFvxJBUXI/lidando-com-utf-8-com-o-php-e-mysql
@mysql_query("SET NAMES 'utf8'");
@mysql_query('SET character_set_connection=utf8');
@mysql_query('SET character_set_client=utf8');
@mysql_query('SET character_set_results=utf8');

//Horário Normal
//@mysql_query('SET time_zone = "SYSTEM"');
/*
if(date('Ymd') >= 20151018 && date('Ymd') < 20160221){
   @mysql_query('SET time_zone = "-2:00"');
}else{
   @mysql_query('SET time_zone = "SYSTEM"');
}
*/

//Horário de Verão
//@mysql_query('SET time_zone = "-2:00"');