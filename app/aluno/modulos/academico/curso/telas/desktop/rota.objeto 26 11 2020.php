<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario);
    if (!$idioma[$informacoes['pagina_anterior']]) {
        $idioma[$informacoes['pagina_anterior']] = "Ambiente de Estudo";
    } ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
     <style type="text/css">
    .book-box p {
        margin: 10px;
        margin-left: 30px;
        margin-bottom: 4px;
    }

    .visualizacao-pagina {
        line-height: 3.9rem;
        font-size: 13px;
        margin-left: 2.5rem;
    }

    .icone_contabilizado {
        font-size: 20px;
    }
    .visualizacao-pagina {
        margin-left: 5.5rem;
    }
    @media (max-width: 530px){
        .visualizacao-pagina {
            line-height: 5.9rem;
            margin-left: -12.5rem;
        }
    }
  </style>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->
        <!-- Ambientação -->
        <div class="box-side box-bg half-side-box">
            <div class="top-box box-verde">
                <h1><?php echo $idioma['conteudo']." / ".$ava['disciplina']; ?></h1>
                <?php // if($favorito) { ?>
                    <!-- <i id="icone_favorito" class="icon-heart" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } else { ?>
                    <!-- <i id="icone_favorito" class="icon-heart-empty" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } ?>
                <span class="visualizacao-pagina">VISUALIZAÇÃO DA PÁGINA</span>
                <?php if($contabilizado) { ?>
                    <i id="icone_contabilizado" class="icon-ok-sign cone_contabilizado icone_contabilizado" style="float: initial;"></i>
                <?php } else { ?>
                    <i id="icone_contabilizado" class="icon-ok-circle cone_contabilizado icone_contabilizado" style="float: initial;"></i>
                <?php } ?>
            </div>
            <i class="set-icon icon-caret-up"></i>
            <div class="clear"></div>
            <?php
            if(!$retornoAcesso['erro']){
            ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="abox m-box">
                        <div class="nav-breadcrumb">
                            <div class="nav-breadcrumb-item first-nav">
                                <?php if($objeto['objeto_anterior']['idobjeto']) { ?>
                                    <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1); ?>#conteudo">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['anterior']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <?php
                                     $anterior = $ava['idava'];
                                     foreach ($idAvas as $ind => $idAva) {
                                         if ($anterior == $idAva && $ind > 0) {
                                             $anterior = $idAvas[$ind - 1];
                                             break;
                                         }
                                     }
                                ?>
                                <?php if($anterior == $ava['idava'] )   {?>
                                    <a  href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula']?>">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['inicio']; ?></p>
                                    </a>

                                   <?php }else{ ?>
                                         <a  href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'. $anterior.'/rota/1#conteudo';?>">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['anterior']; ?></p>
                                    </a>

                                   <?php }?>
                                <?php } ?>
                            </div>
                            <?php if($objeto['objeto_anterior']['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_anterior_anterior']['idobjeto']) { ?>
                                    <a class="" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] -2).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_anterior_anterior']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php if($objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                            <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_anterior']['idobjeto'] && $objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                    <a class="" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] -1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_anterior']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php if($objeto['tipo'] != 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item"  style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                    <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.$url[6].'#conteudo'; ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto']['nome']; ?></p>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if($objeto['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <?php if($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <p><?php echo $objeto['objeto_proximo']['objeto']['nome']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <?php if($objeto['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_proximo_proximo']['idobjeto']) { ?>
                                    <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 2).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_proximo_proximo']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } else { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <?php if($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <?php
                                            if($objeto['objeto_proximo'] && $objeto['objeto_proximo']['tipo'] == 'reconhecimento') {
                                                $referencia = 'objeto_proximo_proximo';
                                            } else {
                                                $referencia = 'objeto_proximo';
                                            }
                                            ?>
                                            <p><?php echo $objeto[$referencia]['objeto']['nome']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                    <?php } ?>
                                </div>
                            <?php }?>
                            <div class="nav-breadcrumb-item last-nav">
                                <?php

                                 if($objeto['objeto_proximo']['idobjeto']) { ?>
                                         <?if(!$objeto['objeto_proximo_proximo'] && (!$downloadsEbooksFeitos)) {?>
                                <a class="" onclick="alert('<? echo $idioma['sem_download_ebooks']; ?>')" href="#">
                                <? } else { ?>
                                    <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <? } ?>
                                        <hr>
                                        <div><i class="icon-chevron-right"></i></div>
                                        <p><?php echo $idioma['proximo']; ?></p>
                                    </a>
                                 <?php } else { ?>
                                     <?php

                                     $proximo = $ava['idava'];
                                     foreach ($idAvas as $ind => $idAva) {
                                         if ($proximo == $idAva && $ind < count($idAvas)-1) {
                                             $proximo = $idAvas[$ind + 1];
                                             break;
                                         }
                                     } ?>
                                     <?php if ($proximo == $ava['idava']) { ?>
                                         <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                             <hr>
                                             <div><i class="icon-chevron-right"></i></div>
                                             <p><?php echo $idioma['fim_curso']; ?></p>
                                         </a>

                                     <?php } else { ?>
                                         <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                             <hr>
                                             <div><i class="icon-chevron-right"></i></div>
                                             <p><?php echo $idioma['proximo']; ?></p>
                                         </a>

                                     <?php } ?>
                                 <?php } ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <!-- Conteúdo -->
                        <div id="conteudo" class="extra-align contents">
							<?php
							switch ($objeto['tipo']) {
								case 'exercicio':
									require 'rota.objeto.exercicio.php';
                                break;
                                case 'reconhecimento':
                                    $imagemPrincipal = $reconhecimentoObj
                                        ->retornaImagemPrincipal($url[3]);
                                    require 'rota.objeto.reconhecimento.php';
                                    break;
                                case 'enquete':
                                    require 'rota.objeto.enquete.php';
                                break;
                                case 'video':
                                    $html .= '<div class="imagem-item">';

                                    if ('html5' == $objeto['objeto']['variavel'] || 'interno' == $objeto['objeto']['variavel']) {
                                        if($config['videoteca_local']){
                                            $srcVideo = '/storage/videoteca/'. $caminho->caminho.'/'.$objeto['objeto']['idvideo'] .'/'.$objeto['objeto']['arquivo'].'_hd.mp4';
                                            $srcImg = '/storage/videoteca/'. $caminho->caminho.'/'.$objeto['objeto']['idvideo'] .'/'.$objeto['objeto']['imagem'].'.jpg';
                                        }else{
                                            $dominio = $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))];
                                            $srcVideo = $dominio.'/'.$caminho->caminho.'/'.$objeto['objeto']['video_nome'];
                                            $srcImg   = $dominio.'/'.$caminho->caminho.'/'.$objeto['objeto']["video_imagem"];
                                        }

                                        $html .= '<video id="video-'.$objeto['objeto']['idvideo'] .'" width="100%" height="400px"
                                                    controls="controls" preload="none"
                                                    poster="'.$srcImg.'">
                                                    <source src="'.$srcVideo.'" type="video/mp4" ></source>
                                                </video>';
                                    } elseif ('youtube' == $objeto['objeto']['variavel'] || 'vimeo' == $objeto['objeto']['variavel']) {
                                        $html .= '<iframe src="'.$objeto['objeto']['arquivo'].'?quality=540p" webkitallowfullscreen mozallowfullscreen allowfullscreen border="0" width="100%" height="400px" style="border: medium none;"></iframe>';
                                    }

                                    $html .= '</div>'.$objeto['objeto']['conteudo'];

                                    echo $html;
                                break;
                                default:
									if ($objeto["objeto"]["imagem_exibicao_servidor"]) {
					                    echo '<div class="imagem-item">
	                                        <img src="/api/get/imagens/avas_conteudos_imagem_exibicao/x/x/'.$objeto['objeto']["imagem_exibicao_servidor"].'" alt="Imagem">
	                                    </div>';
					                }
									echo $objeto['objeto']['conteudo'];
								break;
							}
							?>
                        </div>
                        <div class="clear"></div>
                        <div class="nav-breadcrumb">
                            <div class="nav-breadcrumb-item first-nav">
                                <?php if($objeto['objeto_anterior']['idobjeto']) { ?>
                                    <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1).'#conteudo'; ?>">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['anterior']; ?></p>
                                    </a>
								<?php } else { ?>
                                    <?php if($anterior == $ava['idava'] )   {?>
                                    <a  href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula']?>">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['inicio']; ?></p>
                                    </a>

                                   <?php }else{ ?>
                                         <a  href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'. $anterior.'/rota/1#conteudo';?>">
                                        <hr>
                                        <div><i class="icon-chevron-left"></i></div>
                                        <p><?php echo $idioma['anterior']; ?></p>
                                    </a>

                                   <?php }?>
                                <?php } ?>
                            </div>
                            <?php if($objeto['objeto_anterior']['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_anterior_anterior']['idobjeto']) { ?>
                                    <a href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] -2).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_anterior_anterior']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php if($objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                            <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_anterior']['idobjeto'] && $objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                    <a href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] -1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_anterior']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php if($objeto['tipo'] != 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item"  style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                    <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.$url[6].'#conteudo'; ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto']['nome']; ?></p>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if($objeto['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <?php if($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <p><?php echo $objeto['objeto_proximo']['objeto']['nome']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if($objeto['tipo'] == 'reconhecimento') { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                <?php if($objeto['objeto_proximo_proximo']['idobjeto']) { ?>
                                    <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 2).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                        <p><?php echo $objeto['objeto_proximo_proximo']['objeto']['nome']; ?></p>
                                    </a>
                                <?php } else { ?>
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } else { ?>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <?php if($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <?php
                                            if($objeto['objeto_proximo'] && $objeto['objeto_proximo']['tipo'] == 'reconhecimento') {
                                                $referencia = 'objeto_proximo_proximo';
                                            } else {
                                                $referencia = 'objeto_proximo';
                                            }
                                            ?>
                                            <p><?php echo $objeto[$referencia]['objeto']['nome']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                    <?php } ?>
                                </div>
                            <?php }?>
                            <div class="nav-breadcrumb-item last-nav">
                                <?php
                                if ($objeto['objeto_proximo']['idobjeto']) {
                                    ?>
                                    <a class="btnProximo" href="<?php if($preRequisito) { echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; } else {echo 'javascript:preRequisito();'; } ?>">
                                        <hr>
                                        <div><i class="icon-chevron-right"></i></div>
                                        <p><?= $idioma['proximo']; ?></p>
                                    </a>
                                    <?php
                                } else { ?>
                                    <?php if ($proximo == $ava['idava']) { ?>
                                        <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                            <hr>
                                            <div><i class="icon-chevron-right"></i></div>
                                            <p><?php echo $idioma['fim_curso']; ?></p>
                                        </a>

                                    <?php } else { ?>
                                        <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                            <hr>
                                            <div><i class="icon-chevron-right"></i></div>
                                            <p><?php echo $idioma['proximo']; ?></p>
                                        </a>

                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- /Conteúdo -->
                    </div>
                </div>
            </div>
            <?php
            } else {
                ?>
                <div class="content" id="acessoBloqueado">
                    <div class="box-bg">
                        <div style="text-align:center;padding: 1em;">
                            <img src="/assets/img/<?php echo $retornoAcesso['erro'][0]?>" alt="Marca" />
                            <div class="description-item">
                                <h1><?php echo $retornoAcesso['erro'][1];?></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!-- /Ambientação -->
        <!-- Anotações -->
        <div class="box-side box-bg min-side-box">
            <span class="top-box box-cinza">
                <h1><?php echo $idioma['anotacoes']; ?></h1>
                <i class="icon-quote-left"></i>
            </span>
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="book-box" id="anotacoes">
                        <?php foreach($anotacoes as $anotacao) { ?>
                            <a href="javascript:cadastrarDeletarAnotacao(<?php echo $anotacao["idanotacao"]; ?>);">x</a>
                            <p><?php echo $anotacao['anotacao']; ?></p>
						<?php } ?>
                    </div>
                    <div class="box-gray center-align extra-align">
                        <form class="no-margin">
                            <textarea name="anotacao" id="anotacao" placeholder="<?php echo $idioma['digite_anotacao']; ?>" class="box-textarea"></textarea>
                            <div class="btn btn-cinza btn-send" onClick="cadastrarDeletarAnotacao(0);"><?php echo $idioma['enviar']; ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Anotações -->
    </div>
    <!-- /Rota de aprendizagem -->
</div>
<!-- /Conteudo -->

<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript" src="/assets/plugins/jwerty/jwerty.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script type="text/javascript">
    <?php
    if( $cargaCompleta && ( $objeto['idobjeto'] == $ultimoConteudoRota ) && $ava['contabilizar_datas'] == 'S' ){
        $updateDataUltimo = "true";
        $contaData = (bool)true;
        $ultimoConteudo = true;
    }else{
        $updateDataUltimo = "false";
        $contaData = (bool)true;
    }
    ?>
    <?php
    if( !$cargaCompleta && ( $objeto['idobjeto'] == $ultimoConteudoRota ) && $ava['contabilizar_datas'] == 'S' ){ ?>
    <?php if( $ava['carga_horaria_min'] > 0 ){ ?>
    alert('<?php echo sprintf($idioma['carga_incompleta'],$ava['carga_horaria_min']); ?>');
    window.history.back();
    <?php } } ?>
    var tempoMinimo = Date.now() + <?php echo tempoEmSegundos($objeto["tempo"]); ?>000;
	<?php if($objeto['objeto_anterior']['idobjeto']) { ?>
		jwerty.key('←', function () {
			window.location = "<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1); ?>";
		});
	<?php } ?>
	<?php if($objeto['objeto_proximo']['idobjeto']) {
		if(!$preRequisito) {
			?>
			function preRequisito() {
				alert('<?php echo $idioma['pre_requisito']; ?>');
			}
		<?php } ?>
        jwerty.key('→', function () {
            <?php if(!$preRequisito) { ?>
                preRequisito();
            <?php } else { ?>
                <?php if (!$contabilizado) { ?>
                if (Date.now() > tempoMinimo) {
                     window.location = "<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1); ?>";
                } else {
                    alert('Você não atingiu o tempo mínimo necessário neste conteúdo. Reveja as informações desta página para destravar a próxima unidade de estudo.');
                }
                <?php } else { ?>
                    window.location = "<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1); ?>";
                <?php } ?>
            <?php } ?>
        });
	<?php } ?>
    <?php
    if( !$cargaCompleta ) {
        $urlIncompleto = "/incompleto";
    }
    ?>
	<?php if(!$contabilizado || !$cargaCompleta || $ultimoConteudo ) {
		$porcentagem_ava = 100;
		if($curriculo['porcentagem_ava'])
			$porcentagem_ava = $curriculo['porcentagem_ava'];
			?>
		setTimeout('contabilizar()', <?php echo tempoEmSegundos($objeto["tempo"]); ?>000);
		function contabilizar() {
			$.post('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/json/contabilizar<?php echo $urlIncompleto; ?>',
				{
                    <?php if( $contaData == true ){ ?>
                    datault: <?php echo $updateDataUltimo; ?>,
                    <?php } ?>
					matricula: <?php echo $matricula['idmatricula']; ?>,
					ava: <?php echo $ava['idava']; ?>,
					objeto: <?php echo $objeto['idobjeto']; ?>,
					idmatricula: '<?php echo senhaSegura($matricula['idmatricula'],$config['chaveLogin']); ?>',
					idava: '<?php echo senhaSegura($ava['idava'],$config['chaveLogin']); ?>',
					idobjeto: '<?php echo senhaSegura($objeto['idobjeto'],$config['chaveLogin']); ?>'
				},
				function(json){
					if(json.sucesso) {
						$('#icone_contabilizado').attr('class','icon-ok-sign');
						$('#barra_porcentagem').css('width', json.porcentagem+'%');

						if(json.porcentagem >= <?php echo $porcentagem_ava; ?>)
							$('#barra_porcentagem').css('background-color', '#228B22');

						$('#porcentagem').html('Andamento do curso: <strong>'+json.porcentagem_formatada+'%</strong>');
					}
				},
				"json"
			);
		}
	<?php } ?>
	function favoritar(){
		$.msg({
			autoUnblock : false,
			clickUnblock : false,
			klass : 'white-on-black',
			content: '<?php echo $idioma["processando"]; ?>',
			afterBlock : function(){
				var self = this;
				jQuery.ajax({
					url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/json/favoritar',
					dataType: 'json',
					type: 'POST',
					data: {
						idmatricula: '<?php echo $matricula['idmatricula']; ?>',
						idava: '<?php echo $ava['idava']; ?>',
						idobjeto: '<?php echo $objeto['idobjeto']; ?>'
					},
					success: function(json){ //Se ocorrer tudo certo
						if(json.sucesso) {
							if(json.favorito == 'S') {
								$('#icone_favorito').attr('class','icon-heart');
							} else {
								$('#icone_favorito').attr('class','icon-heart-empty');
							}
							self.unblock();
						} else if(json.erro_json = 'sem_permissao') {
							alert('<?php echo $idioma["sem_permissao"]; ?>');
							self.unblock();
						} else {
							alert('<?php echo $idioma["json_erro"]; ?>');
							self.unblock();
						}
					}
				});
			}
		});
	}

	function cadastrarDeletarAnotacao(deletar){
		var txtanotacao = $('#anotacao').val();
		var acao = 'cadastrar';
		if(deletar > 0)
			acao = 'deletar';

		$.msg({
			autoUnblock : true,
			clickUnblock : false,
			klass : 'white-on-black',
			content: '<?php echo $idioma['processando']; ?>',
			afterBlock : function(){
				var self = this;
				jQuery.ajax({
					url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/json/anotacao/'+acao,
					dataType: 'json',
					type: 'POST',
					data: {
						idmatricula: '<?php echo $matricula['idmatricula']; ?>',
						idava: '<?php echo $ava['idava']; ?>',
						idobjeto: '<?php echo $objeto['idobjeto']; ?>',
						anotacao: txtanotacao,
						idanotacao: deletar
					},
					success: function(json){ //Se ocorrer tudo certo
						if(json.sucesso) {
							$('#anotacao').val('');
							var anotacoes = '';
							for (var i = 0; i < json.anotacoes.length; i++) {
								anotacoes += '<a href="javascript:cadastrarDeletarAnotacao('+json.anotacoes[i].idanotacao+');">x</a><p>'+json.anotacoes[i].anotacao+'</p>';
							}
							$('#anotacoes').html(anotacoes);
							self.unblock();
						} else if(json.erro_json = 'sem_permissao') {
							alert('<?= $idioma['sem_permissao']; ?>');
							self.unblock();
						} else {
							alert('<?= $idioma['json_erro']; ?>');
							self.unblock();
						}
					}
				});
			}
		});
	}

    function acaoButton(id)
    {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/link/'; ?>" + id;
        $.post(url, function(data){
            console.log('Clicou');
        });
    }

    var redireciona = true;
    <?php if(!empty($objeto['idconteudo'])){ ?>
    function verificaCliques(e, objeto)
    {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/link/'; ?>" + <?= $url[6]; ?> + "/verifica/" + objeto;

        e.preventDefault();

        $.ajax({
            url :  url,
            type: 'GET',
            data: '',
            contentType: false,
            processData: false,
            async: false
        }).done(function(respond){
            if(respond.length > 0){
                alert('Atenção, você precisa clicar nos itens abaixo: \n \n' + respond);
                redireciona = false;
            } else {
                redireciona = true;
            }
        });
    }
    <?php } ?>

     function verificarDias(e)
    {
        e.preventDefault();
        <?php if(! $preRequisitoDias['sucesso']){ ?>
            alert('Opa! Você atingiu a carga horária máxima de estudo online por dia. Você pode continuar a acompanhar o conteúdo a partir de ' + '<?= $preRequisitoDias['data'] ?>!');
            redireciona = false;
        <?php } ?>
    }

    function redirecionaPag()
    {
        if(redireciona == true){
            window.location.href = '<?= '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1).'#conteudo'; ?>';
        }
    }

    function verificaTempoMinimo(e) {
        <?php if ($contabilizado) { ?>
            return true;
        <?php } else { ?>
            if (Date.now() > tempoMinimo) {
                return true;
            } else {
                alert('Você não atingiu o tempo mínimo necessário neste conteúdo. Reveja as informações desta página para destravar a próxima unidade de estudo.');
                e.preventDefault();
                redireciona = false;
                return false;
            }
        <?php } ?>
    }

    $(".btnProximo").on("click contextmenu", function (e) {
        <?php if(!empty($objeto['idconteudo'])){ ?>
        verificaCliques(e, '<?= $objeto['idconteudo'] ?>');
        <?php } ?>
        verificaTempoMinimo(e);
        verificarDias(e);
        redirecionaPag();
    });
</script>
</body>
</html>
