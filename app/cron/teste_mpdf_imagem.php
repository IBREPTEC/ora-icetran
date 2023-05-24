<?php
echo $url = 'https://icetran.alfamaoraculo.com.br/storage/declaracoes_imagens/20201023143801_5f9314f95d32d.png';
echo "<br>";
var_dump(get_headers($url));
var_dump(get_headers($url, true));

echo "<br><br>";
echo $url = 'https://www.grupoalfama.com.br/img/Alfama_Grupo.png';
echo "<br>";
var_dump(get_headers($url));
var_dump(get_headers($url, true));
?>
