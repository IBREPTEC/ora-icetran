<?php
## Informações para a lib topo_curso
$informacoesTopoCurso['link_pagina_anterior'] = '/'.$url[0].'/academico/curso/'.$url[3];
$informacoesTopoCurso['pagina_anterior'] = 'ambiente_estudo';
$informacoesTopoCurso['pagina'] = 'rota_aprendezagem';
## /Informações para a lib topo_curso

## Rota De Aprendizagem
$rotaDeAprendizagem = $matriculaObj->retornarRotaDeAprendizagem($ava['idava']);
## Rota De Aprendizagem

## Ultimo objeto contabilizado
//if($ava['pre_requisito'] == 'S')
	$ultimo = $matriculaObj->retornarUltimoObjetoContabilizado($ava['idava']);
## Ultimo objeto contabilizado

require 'idiomas/'.$config['idioma_padrao'].'/rota.php';
require 'telas/'.$config['tela_padrao'].'/rota.php';
exit;