<?php
$config["host"]     = "localhost";
$config["usuario"]  = "icetran_ora";
$config["senha"]    = 'YN3g9^^w$CI#3zUvdNn';
$config["database"] = "icetran_oraculo";

@mysql_connect($config["host"],$config["usuario"],$config["senha"]);
@mysql_select_db($config["database"]);

// Usado para a codificação UTF-8
// Referência: http://phpbrasil.com/artigo/11qDFvxJBUXI/lidando-com-utf-8-com-o-php-e-mysql
@mysql_query("SET NAMES 'utf8'");
@mysql_query('SET character_set_connection=utf8');
@mysql_query('SET character_set_client=utf8');
@mysql_query('SET character_set_results=utf8');

@mysql_query('SET time_zone = "SYSTEM"');
// $dataParaHorarioVerao = new \DateTime();
// if ($dataParaHorarioVerao->format('Y-m-d') >= '2018-11-04' && $dataParaHorarioVerao->format('Y-m-d') <= '2019-02-17') {
//     @mysql_query('SET time_zone = "-2:00"');
// }
