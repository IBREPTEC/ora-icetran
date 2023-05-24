<?php

## Rota De Aprendizagem
$rotaDeAprendizagem = $matriculaObj->retornarRotaDeAprendizagem($ava['idava']);
## Rota De Aprendizagem

## Ultimo objeto contabilizado
//if($ava['pre_requisito'] == 'S')
	$ultimo = $matriculaObj->retornarUltimoObjetoContabilizado($ava['idava']);
## Ultimo objeto contabilizado

$downloadsEbooksFeitos = $matriculaObj->verificarTodosDownloadsEbooksFeitos($matricula['idmatricula'], $ava['idava']);

## Para Verificar Porcetagem mínima da Oferta/curso
$matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);

## Informações para a lib topo_curso
$informacoesTopoCurso['link_pagina_anterior'] = '/'.$url[0].'/academico/curso/'.$url[3];
$informacoesTopoCurso['pagina_anterior'] = 'ambiente_estudo';
$informacoesTopoCurso['pagina'] = $ava['disciplina'];
$informacoesTopoCurso['matricula'] = $matricula;
$informacoesTopoCurso['ultimo_acesso'] = $usuario['ultimo_acesso'];

/**
 * Valida se os Avas anteriores foram concluídos ou/e se tem avaliações pendentes
 */

$podeAcessarAva = true;

$avas = $matriculaObj->retornarAvas();
foreach ($avas as $a){
    if($a['idava'] == $ava['idava']){
        break;
    }else{
        if(($a['avaliacao_pendente'] == true || empty($a['data_fim'])) ||
        ($a['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'])){
            $podeAcessarAva = false;
            break;
        }
    }
}

if($podeAcessarAva){
    require 'idiomas/'.$config['idioma_padrao'].'/rota.php';
    require 'telas/'.$config['tela_padrao'].'/rota.php';
    exit;
}else{
    echo "<script>
          if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
              $('#acessar_disciplina').removeAttr('hidden')
               }else{
                   alert('Para acessar esta disciplina conclua as anteriores e suas avaliações.');
                    window.location.href = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}\";     
               }         
          </script>";
    exit();
}

