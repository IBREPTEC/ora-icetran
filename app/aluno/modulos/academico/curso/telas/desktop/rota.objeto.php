<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario);
    if (!$idioma[$informacoes['pagina_anterior']]) {
        $idioma[$informacoes['pagina_anterior']] = "Ambiente de Estudo";
    } ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
    <link media="screen" href="/assets/css/acessibilidade.css" rel="stylesheet" type="text/css">
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=axktJS1w"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/css">
        .book-box p {
            margin: 10px;
            margin-left: 30px;
            margin-bottom: 4px;
        }

        .icone_contabilizado {
            font-size: 20px;
            bottom: 60px;
            position: relative;
            color:green;
        }

        @media (max-width: 530px) {
            .visualizacao-pagina {
                line-height: 5.9rem;
                margin-left: -12.5rem;
            }
            .btn btn-large btn-large btn-success{
                margin-left:30px !important;
            }

        }
        #video {
            z-index: 1;
        }
        .embed-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }
        .embed-container iframe, .embed-container object, .embed-container embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }


        .textparag{
            font-size: 15px;
            /* padding-left: 35px; */
        }
		
		#alert-estudo{
            z-index: 9999;
            width: 100vw;
            top: 50%;
            position:fixed;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        #alerta_tempo_minimo{
            z-index: 9999;
            width: 100vw;
            top: 50%;
            position:fixed;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        #alerta-liberacao-temporaria{
            z-index: 9999;
            width: 100vw;
            top: 50%;
            position:fixed;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        #alerta-carga-horaria-incompleta{
            z-index: 9999;
            width: 100vw;
            top: 50%;
            position:fixed;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        @media  (max-width: 900px) {
            #alert-estudo {
                width: 87vw;
            }
            #alerta_tempo_minimo{
                width: 87vw;
            }
            #alerta-carga-horaria-incompleta{
                width: 87vw;
            }
            #alerta-liberacao-temporaria{
                width: 87vw;
            }
		}

    </style>
</head>
<body>

<div id="div_2"></div>

<!-- Topo -->
<?php incluirLib("topo", $config, $informacoesTopoCurso); ?>
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
		<div id="alert-estudo" class="alert alert-info alert-dismissible hidden "  role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="alerta-estudo-completo-2"></div>

        </div>
        <div class="box-side box-bg half-side-box">
            <div class="top-box box-white">
                <h1>CONTEÚDO</h1>
                <?php // if($favorito) { ?>
                <!-- <i id="icone_favorito" class="icon-heart" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } else { ?>
                <!-- <i id="icone_favorito" class="icon-heart-empty" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } ?>
				<br/>
                <div class="acessibilidade">
                    <i class="icon-minus" style="cursor:pointer;" onClick="changeFontSize('-');"
                       title="Diminuir tamanho do texto"></i>
                    <i class="icon-plus" style="cursor:pointer;" onClick="changeFontSize('+');"
                       title="Aumentar tamanho do texto"></i>
                    <i class="icon-adjust" style="cursor:pointer;" onClick="toggleContrast();"
                       title="Alternar contraste"></i>
                    <i class="icon-print" style="cursor: pointer" onclick="imprimirConteudo()" ></i>

                    <?php if($traducao){?>
                        <i id="icon_start"class="fa-solid fa-play" onclick="startAudio()"></i>
                        <i id="icon_pause" class="fa-solid fa-pause hidden" onclick="pauseAudio()"></i>
                        <i id="icon_resume"class="fa-solid fa-play hidden" onclick="resumeAudio()"></i>
                    <?php }?>

                </div>
            </div>
            <i class="set-icon icon-caret-up"></i>
            <div class="clear"></div>
            <?php
            if (!$retornoAcesso['erro']) {
                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="abox m-box">
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav" style="width: 30%">
                                    <?php if ($objeto['objeto_anterior']['idobjeto']) { ?>
                                        <? $conteudo = $objeto['objeto_anterior']['tipo'] == 'reconhecimento' ? '?voltar=1' : '#conteudo'; ?>
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . $conteudo; ?>">
                                            <hr>
                                            <div class="ini"><i class="icon-chevron-left"></i></div>
                                            <p class="textparag"><?php echo $idioma['anterior']; ?></p>
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
                                        <?php if ($anterior == $ava['idava']) { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div class="ini"><i class="icon-chevron-left"></i></div>
                                                <p class="textparag"><?php echo $idioma['inicio']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $anterior . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div class="ini"><i class="icon-chevron-left"></i></div>
                                                <p class="textparag"><?php echo $idioma['anterior']; ?></p>
                                            </a>

                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php if ($objeto['objeto_anterior']['tipo'] == 'reconhecimento') { ?>
                                <?php } ?>
                                <?php if ($objeto['objeto_anterior']['tipo'] != 'reconhecimento') {
                                }
                                if ($objeto['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item"
                                         style="width:40%;background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
										<br>
                                        <?php if ($contabilizado) { ?>
                                            <span  style="font-weight: bold;color:#333333;font-size: 10px">CONTABILIZAÇÃO FEITA COM SUCESSO  </span>

                                            <i class="icon-ok-circle icone_contabilizado"
                                             ></i>
                                        <?php } else { ?>
                                            <span id="contabilizacao"  style="font-weight: bold;color:#333333;font-size: 10px">CONTABILIZAÇÃO DA PÁGINA </span>

                                            <span id="timer"  style="float:initial;font-size: 20px;font-weight: bold;color: red;position: relative;bottom:60px"></span>
                                            <span  class="hidden" style="color:#333333;font-weight: bold;width: 100%;font-size: 10px" id="contabilizacao_sucesso">CONTABILIZAÇÃO FEITA COM SUCESSO  </span>

                                            <i id="icon_contabilizado"  class="icon-ok-circle icone_contabilizado hidden"
                                                style="float: initial;"></i>

                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div style="width: 40%" class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
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

                                <?php if ($objeto['tipo'] == 'reconhecimento') {
                                } else { ?>
                                    <div class="nav-breadcrumb-item no-mobile hidden" style="width: 40%">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <? if (!$objeto['objeto_proximo_proximo'] && (!$downloadsEbooksFeitos)) { ?>
                                        <a class="" onclick="alert('<? echo $idioma['sem_download_ebooks']; ?>')"
                                           href="#">
                                            <? } else { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <? } ?>
                                                <hr>
                                                <div style="width: 30%"><i class="icon-book"></i></div>
                                                <?php
                                                if ($objeto['objeto_proximo'] && $objeto['objeto_proximo']['tipo'] == 'reconhecimento') {
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
                                <?php } ?>
                                <div class="nav-breadcrumb-item last-nav" style="width: 30%">
                                    <?php

                                    if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                    <? if (!$objeto['objeto_proximo_proximo'] && (!$downloadsEbooksFeitos)) { ?>
                                    <a class="" onclick="alert('<? echo $idioma['sem_download_ebooks']; ?>')" href="#">
                                        <? } else { ?>
                                        <a class="btnProximo" href="<?php if ($preRequisito) {
                                            echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                        } else {
                                            echo 'javascript:preRequisito();';
                                        } ?>">
                                            <? } ?>
                                            <hr>
                                            <div class="ini" style="margin-top:10px; position: relative;
                                             z-index: 99; background-color: green;"><i class="icon-chevron-right"></i></div>
                                            <p class="textparag"><?php echo $idioma['proximo']; ?></p>
                                        </a>
                                        <?php } else { ?>
                                            <?php
                                            // Validar pela porcentagem mínima da Oferta/curso
                                            $matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
                                            $proximo = $ava['idava'];
                                            $trava_liberacao_temporaria_datavalid = false;
                                            foreach ($idAvas as $ind => $idAva) {
                                                if ($proximo == $idAva && $ind < count($idAvas) - 1) {
                                                    if ($matricula['liberacao_temporaria_datavalid'] == 'S' && $ind >= 1) $trava_liberacao_temporaria_datavalid = true;
                                                    $proximo = $idAvas[$ind + 1];
                                                    break;
                                                }
                                            } ?>
                                            <?php if ($trava_liberacao_temporaria_datavalid) { ?>
                                                <a href="<?= 'javascript:disparaAlertaLiberacaoTemporariaDataValid(\'' . sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))) . '\')' ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p class="textparag"><?php echo $idioma['fim_curso']; ?></p>
                                                </a>
                                            <?php } elseif ($proximo == $ava['idava']) { ?>
                                                <a href="<?= $ava['avaliacao_pendente'] || $ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? 'javascript:alert(\'' . ($ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? $idioma['impossibilitado_prosseguir_porcentagem'] : $idioma['impossibilitado_prosseguir']) . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p><?php echo $idioma['fim_curso']; ?></p>
                                                </a>

                                            <?php } else { ?>
                                                <a href="<?= $ava['avaliacao_pendente'] || $ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? 'javascript:alert(\'' . ($ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? $idioma['impossibilitado_prosseguir_porcentagem'] : $idioma['impossibilitado_prosseguir']) . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                                    <hr>
                                                    <div class="ini" style="margin-top:10px; position: relative;
                                                    z-index: 99;background-color: green;"><i class="icon-chevron-right"></i></div>
                                                    <p class="textparag"><?php echo $idioma['proximo']; ?></p>
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
                                            ->retornaImagemPrincipal($matricula['idmatricula']);
                                        require 'rota.objeto.reconhecimento.php';
                                        break;
                                    case 'enquete':
                                        require 'rota.objeto.enquete.php';
                                        break;
                                    case 'video':
                                        $html .= '<div class="imagem-item embed-container">';

                                        if ('html5' == $objeto['objeto']['variavel'] || 'interno' == $objeto['objeto']['variavel']) {
                                            if ($config['videoteca_local']) {
                                                $srcVideo = '/storage/videoteca/' . $caminho->caminho . '/' . $objeto['objeto']['idvideo'] . '/' . $objeto['objeto']['arquivo'] . '_hd.mp4';
                                                $srcImg = '/storage/videoteca/' . $caminho->caminho . '/' . $objeto['objeto']['idvideo'] . '/' . $objeto['objeto']['imagem'] . '.jpg';
                                            } else {
                                                $dominio = $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))];
                                                $srcVideo = $dominio . '/' . $caminho->caminho . '/' . $objeto['objeto']['video_nome'];
                                                $srcImg = $dominio . '/' . $caminho->caminho . '/' . $objeto['objeto']["video_imagem"];
                                            }

                                            $html .= '<video id="video-' . $objeto['objeto']['idvideo'] . '" width="100%" height="400px"
                                                    controls="controls" preload="none"
                                                    poster="' . $srcImg . '">
                                                    <source src="' . $srcVideo . '" type="video/mp4" ></source>
                                                </video>';
                                        } elseif ('youtube' == $objeto['objeto']['variavel'] || 'vimeo' == $objeto['objeto']['variavel']) {
                                            $html .= '<iframe class="videoIframe" src="' . $objeto['objeto']['arquivo'] . '?quality=540p" webkitallowfullscreen mozallowfullscreen allowfullscreen border="0" width="100%" height="400px" style="border: medium none;"></iframe>';
                                        }

                                        $html .= '</div>' . $objeto['objeto']['conteudo'];

                                        echo $html;
                                        break;
                                    default:
                                        if (strpos($objeto['objeto']['arquivo_tipo'], 'pdf') !== false) {
                                            echo "<p>" . nl2br($objeto['objeto']['descricao']) . "</p>";
                                            ?>
                                            <div class="clearfix"></div>
                                            <div style="width:50px;float:left;">
                                                <img src="/assets/img/icone_pdf.png" alt="">
                                            </div>
                                            <div style="margin-top:10px;">
                                                VOCÊ ESTÁ VISUALIZANDO O PDF
                                                <u><b><?= $objeto['objeto']['arquivo_nome'] ?></b></u><br>
                                            </div>
                                            <iframe
                                                    id="pdfDocument"
                                                    height="800"
                                                    width="100%"
                                                    src="<?= $_SERVER['SERVER_ADDRESS'] ?>/assets/plugins/pdfjs/web/viewer.html?file=<?= $_SERVER['SERVER_ADDRESS'] ?>/storage/avas_downloads_arquivo/<?= $objeto['objeto']['arquivo_servidor'] ?>"
                                                    frameborder="0">
                                            </iframe>
                                            <?php
                                        } else if ($objeto["objeto"]["imagem_exibicao_servidor"]) {
                                            echo '<div class="imagem-item">
                                           <img src="/api/get/imagens/avas_conteudos_imagem_exibicao/x/x/' . $objeto['objeto']["imagem_exibicao_servidor"] . '" alt="Imagem">
                                       </div>';
                                        }
                                        echo $objeto['objeto']['conteudo'];
                                        break;
                                }
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav" style="width: 30%">
                                    <?php if ($objeto['objeto_anterior']['idobjeto']) { ?>
                                        <? $conteudo = $objeto['objeto_anterior']['tipo'] == 'reconhecimento' ? '?voltar=1' : '#conteudo'; ?>
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . $conteudo ?>">
                                            <hr>
                                            <div class="ini"><i class="icon-chevron-left"></i></div>
                                            <p class="textparag"><?php echo $idioma['anterior']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <?php if ($anterior == $ava['idava']) { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div class="ini"><i class="icon-chevron-left"></i></div>
                                                <p class="textparag" ><?php echo $idioma['inicio']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $anterior . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div class="ini" ><i class="icon-chevron-left"></i></div>
                                                <p class="textparag"><?php echo $idioma['anterior']; ?></p>
                                            </a>

                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php if ($objeto['objeto_anterior']['tipo'] != 'reconhecimento') {
                                }
                                if ($objeto['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item" style="width: 40%">
                                    <br>
                                        <?php if ($contabilizado) { ?>
                                            <span style="color:#333333;font-weight: bold;width: 100%;font-size: 10px" >CONTABILIZAÇÃO FEITA COM SUCESSO  </span>

                                            <i class="icon-ok-circle icone_contabilizado"
                                            ></i>
                                        <?php } else { ?>
                                            <span id="contabilizacao_2"style="color:#333333;font-weight: bold;width: 100%;font-size: 10px" >CONTABILIZAÇÃO DA PÁGINA </span>

                                            <span id="timer_2"  style="float:initial;font-size: 20px;font-weight: bold;color: red;position: relative;bottom:60px"></span>
                                            <span class="hidden" style="color:#333333;font-weight: bold;width: 100%;font-size: 10px" id="contabilizacao_sucesso_2">CONTABILIZAÇÃO FEITA COM SUCESSO  </span>

                                            <i id="icon_contabilizado_2"  class="icon-ok-circle icone_contabilizado hidden"
                                               style="float: initial;"></i>

                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile" style="width: 40%">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
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
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                <?php } ?>
                                <div class="nav-breadcrumb-item last-nav" style="width: 30%">
                                    <?php
                                    if ($objeto['objeto_proximo']['idobjeto']) {
                                        ?>
                                        <a class="btnProximo" href="<?php if ($preRequisito) {
                                            echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                        } else {
                                            echo 'javascript:preRequisito();';
                                        } ?>">
                                            <hr>
                                            <div class="ini" style="margin-top:10px; position: relative;
                                            z-index: 99; background-color: green;"><i class="icon-chevron-right"></i></div>
                                            <p class="textparag"><?= $idioma['proximo']; ?></p>
                                        </a>
                                        <?php
                                    } else { ?>
                                        <?php if ($trava_liberacao_temporaria_datavalid) { ?>
                                            <a href="<?= 'javascript:disparaAlertaLiberacaoTemporariaDataValid(\'' . sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))) . '\')' ?>">
                                                <hr>
                                                <div><i class="icon-chevron-right"></i></div>
                                                <p><?php echo $idioma['fim_curso']; ?></p>
                                            </a>
                                        <?php } elseif ($proximo == $ava['idava']) { ?>
                                            <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div><i class="icon-chevron-right"></i></div>
                                                <p><?php echo $idioma['fim_curso']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div class="ini" style="margin-top:10px; position: relative;
                                                z-index: 99;background-color: green;"><i class="icon-chevron-right"></i></div>
                                                <p class="textparag"><?php echo $idioma['proximo']; ?></p>
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
                            <img src="/assets/img/<?php echo $retornoAcesso['erro'][0] ?>" alt="Marca"/>
                            <div class="description-item">
                                <h1><?php echo $retornoAcesso['erro'][1]; ?></h1>
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
        <div class="box-side min-side-box">
            <p>
                <a class="btn btn-success" data-toggle="collapse" href="#boxAnotacoes" role="button" aria-expanded="false" aria-controls="boxAnotacoes" style="width: 91.3%;">Fazer Anotação</a>
            </p>
        </div>

        <div class="collapse multi-collapse box-anotacoes box-side box-bg min-side-box print conteudo-box" id="boxAnotacoes">
            <span class="top-box box-cinza">
                <h1><?php echo $idioma['anotacoes']; ?></h1>
                <i class="icon-quote-left"></i>
                <i class="icon-print" style="cursor: pointer" onclick="imprimirConteudoAnotacao()"></i>

            </span>
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="book-box" id="anotacoes">
                        <?php foreach ($anotacoes as $anotacao) { ?>
                            <a href="javascript:cadastrarDeletarAnotacao(<?php echo $anotacao["idanotacao"]; ?>);">x</a>
                            <p><?php echo $anotacao['anotacao']  ?></p>

                        <?php } ?>


                    </div>
                    <div class="box-gray center-align extra-align">
                        <form class="no-margin">
                            <textarea name="anotacao" id="anotacao"
                                      placeholder="<?php echo $idioma['digite_anotacao']; ?>"
                                      class="box-textarea"></textarea>
                            <div class="btn btn-cinza btn-send"
                                 onclick="cadastrarDeletarAnotacao(0);"><?php echo $idioma['enviar']; ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Anotações -->
		<!-- imagens -->
        <?php if($imagens){
            ?>
            <div id="box-ibpad" class="box-side box-bg min-side-box" style="box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
      border-radius: 2px;
      padding: 5px;
      width: calc(29% - 10px);
      text-align: center;
      position: sticky;
      top: 5px;">
                <?php foreach ($imagens as $imagem){?>
                    <hr>
                    <a href="<?=$imagem['link']?>" target="_blank">
                        <img id="imagem_<?=$imagem['idimagem']?>"src="<?php echo '/storage/avas_conteudos_imagem_exibicao/'.$imagem['imagem_exibicao_servidor']; ?>"  >
                    </a>
                <?php }?>
            </div>
        <?php }?>

        <!-- /imagens -->
					
    </div>
    <!-- /Rota de aprendizagem -->
</div>
<!-- /Conteudo -->

<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript" src="/assets/plugins/jwerty/jwerty.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        var modal = document.getElementById("modal-model")
        if(modal!=null) {
            GeraBotãoModal();
            var botao = document.getElementById('imprimir_link')
            botao.onclick = function () {
                imprimirConteudoLinks();
            };
        }
        var tempoEmSegundos ='<?php echo tempoEmSegundos($objeto["tempo"]);?>'
        display = document.querySelector('#timer');
        display_2 = document.querySelector('#timer_2');
        if(tempoEmSegundos != 0) {
            startTimer(tempoEmSegundos, display);
			startTimer(tempoEmSegundos, display_2);
        }
    });
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;
            if (--timer <= 0) {
                timer = 0
                $('#timer').hide()
				$('#timer_2').hide()
            }
        }, 1000);
    }
    function GeraBotãoModal(){
        var div =document.getElementById("modal-model").getElementsByClassName("modal-footer")[0];

        var button = document.createElement("BUTTON");
        button.innerHTML = "Imprimir Conteúdo";
        button.className = 'btn btn-default btn-primary'
        button.id ='imprimir_link'


        div.appendChild(button);
    }

    function imprimirConteudoLinks(){
        var conteudo =document.getElementById('modal-model-content').innerHTML

        w = window.open('', 'mywindow', 'status=1,width=750,height=750');

        w.document.write(conteudo);
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');

        w.document.close();
        w.focus();

        return true;

    }
    function imprimirConteudo(){
        var conteudo =document.getElementById('conteudo').innerHTML

        w = window.open('', 'mywindow', 'status=1,width=750,height=750');

        w.document.write(conteudo);
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');

        w.document.close();
        w.focus();

        return true;


    }
    function imprimirConteudoAnotacao(){
        var conteudo =document.getElementById('anotacoes').innerHTML

        w = window.open('', 'mywindow', 'status=1,width=750,height=750');

        w.document.write(conteudo);
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');

        w.document.close();
        w.focus();

        return true;


    }


    var changeCounter = 0;

    function changeFontSize(e) {
        var el = $(".nav-breadcrumb-item a p, #conteudo, #conteudo *"),
            step = 2,
            maxIncrease = 6;

        if (e == '+' && changeCounter < maxIncrease) {
            el.each(function () {
                var actualSize = $(this).css('font-size');
                $(this).css("fontSize", parseInt(actualSize) + step);
            });

            changeCounter++;
        } else if (e == '-' && changeCounter) {
            el.each(function () {
                var actualSize = $(this).css('font-size');
                $(this).css("fontSize", parseInt(actualSize) - step);
            });

            changeCounter--;
        }
    }

    function toggleContrast() {
        var el = $(".nav-breadcrumb, #conteudo");
        el.toggleClass('contrast');
    }
</script>
<script type="text/javascript">
    <?php
    if (!$cargaCompleta && ($objeto['idobjeto'] == $ultimoConteudoRota) && $ava['contabilizar_datas'] == 'S') {
        $ultimoConteudo = true;
    }

    $tempoEmSegundos = tempoEmSegundos($objeto["tempo"]);
    echo "var tempoMinimo = Date.now() + {$tempoEmSegundos}000;";
    $anterior1 = $url[6] - 1;
    if ($objeto['objeto_anterior']['idobjeto']) {
        echo "jwerty.key('←', function () {window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\";});";
    }

    if ($objeto['objeto_proximo']['idobjeto']) {
        if (!$preRequisito) echo "function preRequisito() {alert('{$idioma['pre_requisito']}');}";
        echo "jwerty.key('→', function () {";

        if (!$preRequisito) echo "preRequisito();";
        else if (!$contabilizado) {
            echo "if (Date.now() > tempoMinimo) {
                    window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\";
                } else {
                    document.getElementById('alerta_tempo_minimo').classList.remove('hidden');
                    let texto = 'Você não atingiu o tempo mínimo necessário neste conteúdo. Reveja as informações desta página para destravar a próxima unidade de estudo.'
                    var div = document.getElementById('tempo_minimo_text').innerText = texto;
                }";
        } else {
            echo "window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\"";
        }
        echo "});";
    }

    $urlIncompleto = '';
    if (!$cargaCompleta) {
        $urlIncompleto = "/incompleto";
    }
    if(!$contabilizado || !$cargaCompleta || $ultimoConteudo ) {
    $porcentagem_ava = 100;
    if ($curriculo['porcentagem_ava'])
        $porcentagem_ava = $curriculo['porcentagem_ava'];
    echo "setTimeout('contabilizar()', {$tempoEmSegundos}000);";
    ?>
    function contabilizar() {
        $.post('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/contabilizar<?php echo $urlIncompleto; ?>',
            {
                matricula: <?php echo $matricula['idmatricula']; ?>,
                ava: <?php echo $ava['idava']; ?>,
                objeto: <?php echo $objeto['idobjeto']; ?>,
                idmatricula: '<?php echo senhaSegura($matricula['idmatricula'], $config['chaveLogin']); ?>',
                idava: '<?php echo senhaSegura($ava['idava'], $config['chaveLogin']); ?>',
                idobjeto: '<?php echo senhaSegura($objeto['idobjeto'], $config['chaveLogin']); ?>'
            },
            function (json) {
                if (json.sucesso) {
                    $('#icon_contabilizado').removeClass('hidden');
                    $('#icon_contabilizado_2').removeClass('hidden');

                    $('#contabilizacao_sucesso').removeClass('hidden')
                    $('#contabilizacao_sucesso_2').removeClass('hidden')

                    $('#contabilizacao').addClass('hidden')
                    $('#contabilizacao_2').addClass('hidden')
					
                    $('#barra_porcentagem').css('width', json.porcentagem + '%');

                    if (json.porcentagem >= <?php echo $porcentagem_ava; ?>)
                        $('#barra_porcentagem').css('background-color', '#228B22');

                    $('#porcentagem').html('Andamento do curso: <strong>' + json.porcentagem_formatada + '%</strong>');
                }
            },
            "json"
        );
    }
    <?php } ?>
    function favoritar() {
        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: '<?php echo $idioma["processando"]; ?>',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/favoritar',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        idmatricula: '<?php echo $matricula['idmatricula']; ?>',
                        idava: '<?php echo $ava['idava']; ?>',
                        idobjeto: '<?php echo $objeto['idobjeto']; ?>'
                    },
                    success: function (json) { //Se ocorrer tudo certo
                        if (json.sucesso) {
                            if (json.favorito == 'S') {
                                $('#icone_favorito').attr('class', 'icon-heart');
                            } else {
                                $('#icone_favorito').attr('class', 'icon-heart-empty');
                            }
                            self.unblock();
                        } else if (json.erro_json = 'sem_permissao') {
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

    function cadastrarDeletarAnotacao(deletar) {
        var txtanotacao = $('#anotacao').val();
		var url = '<?php echo $url[6];?>'
        var acao = 'cadastrar';
        if (deletar > 0)
            acao = 'deletar';

        $.msg({
            autoUnblock: true,
            clickUnblock: false,
            klass: 'white-on-black',
            content: '<?= $idioma['processando']; ?>',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/anotacao/' + acao,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        idmatricula: '<?php echo $matricula['idmatricula']; ?>',
                        idava: '<?php echo $ava['idava']; ?>',
                        idobjeto: '<?php echo $objeto['idobjeto']; ?>',
                        anotacao: txtanotacao,
                        disciplina:'<?php echo 0;?>',
                        rota:'<?php echo 0;?>',
                        idanotacao: deletar,
                        url :url,
                        idpessoa:<?php echo $matricula['idpessoa']; ?>
                    },
                    success: function (json) { //Se ocorrer tudo certo
                        if (json.sucesso) {
                            $('#anotacao').val('');
                            var anotacoes = '';
                            for (var i = 0; i < json.anotacoes.length; i++) {
                                anotacoes += '<a href="javascript:cadastrarDeletarAnotacao(' + json.anotacoes[i].idanotacao + ');">x</a><p>' + json.anotacoes[i].anotacao + '</p>';
                            }
                            $('#anotacoes').html(anotacoes);
                            self.unblock();
                        } else if (json.erro_json = 'sem_permissao') {
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

    function acaoButton(id) {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/link/'; ?>" + id;
        $.post(url, function (data) {
            console.log('Clicou');
        });
    }

    var redireciona = true;
    <?php if(!empty($objeto['idconteudo'])){ ?>
    function verificaCliques(e, objeto) {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/link/'; ?>" + <?= $url[6]; ?> + "/verifica/" + objeto;

        e.preventDefault();

        $.ajax({
            url: url,
            type: 'GET',
            data: '',
            contentType: false,
            processData: false,
            async: false
        }).done(function (respond) {
            if (respond.length > 0) {
                alert('Atenção, você precisa clicar nos itens abaixo: \n \n' + respond);
                redireciona = false;
            } else {
                redireciona = true;
            }
        });
    }
    <?php } ?>

    function verificarDias(e) {
    e.preventDefault();

    <?php if(!$preRequisitoDias1['sucesso']){ ?>
    if($("#div_2").find("div_2#alert-estudo").length==0){
        $("#div_2").append("<div id='alert-estudo' class='alert alert-warning alert-dismissible'  role='alert'><div id='alerta-estudo-completo-2' style='font-size: 10px;'></div><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");

        let texto =
            "PARABÉNS!\n Você já completou o conteúdo de estudo programado para a data de hoje. Assim como no trânsito, a VELOCIDADE de estudo precisa ser controlada… vamos desacelerar e, a partir de <?= $preRequisitoDias1['data'] ?> você poderá continuar com seus estudos!"

        var div = document.getElementById('alerta-estudo-completo-2').innerText = texto;
    }
    redireciona = false;
    <?php } ?>
}

    var soundPlayer = null;

    function startAudio(){
        $('#icon_pause').removeClass("hidden")
        $('#icon_start').hide()
        var conteudo = document.getElementById('conteudo').innerText
        conteudo.replace(/[^a-zA-Z0-9s]/g, "")

        var isFirefox = typeof InstallTrigger !== 'undefined';
        if(isFirefox){
            soundPlayer = new Audio(responsiveVoice.speak(conteudo,'Portuguese Male'))
            soundPlayer.play()


            if(soundPlayer.paused === true){
                soundPlayer.resume()
                console.log(soundPlayer.paused)
            }

        }else{
			responsiveVoice.cancel()
            responsiveVoice.speak(conteudo,'Brazilian Portuguese Female',1000)
        }


    }

    function pauseAudio(){
        $('#icon_pause').addClass("hidden")
        $('#icon_resume').removeClass("hidden")
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if(isFirefox){
            soundPlayer.pause()
            responsiveVoice.pause()


        }else{
            responsiveVoice.onpause = true
            responsiveVoice.pause()

        }

    }
    function resumeAudio(){
        $('#icon_pause').removeClass("hidden")
        $('#icon_resume').addClass("hidden")

        responsiveVoice.resume()

    }

    function verificarSimulados(e) {
        e.preventDefault();
        var idObjeto = <?php echo $objeto['objeto_proximo']['idobjeto'] ?: 'null'; ?>;
        var ultimoConteudo = <?php echo $ultimoConteudoRota; ?>;
        var simulados = <?php echo $simulados; ?>;
        var simuladosRealizados = <?php echo $simuladosRealizados; ?>;
        if (ultimoConteudo === idObjeto && simulados > 0 && simuladosRealizados === 0) {
            alert('<?php echo $idioma['simulado_obrigatorio']; ?>');
            redireciona = false;
        }
    }

    function redirecionaPag() {
        if (redireciona == true) {
            window.location.href = '<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo'; ?>';
        }
    }

    function verificaTempoMinimo(e) {
        <?php if ($contabilizado) {
        echo "return true;";
    } else { ?>
        if (Date.now() > tempoMinimo) {
            return true;
        } else {
            if($("#div_2").find("div_2#alerta_tempo_minimo").length==0){
                $("#div_2").append("<div id='alerta_tempo_minimo' class='alert alert-warning alert-dismissible' role='alert'> <div id='tempo_minimo_text' style='font-size: 10px;'> </div> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div>");
                let texto = 'Você não atingiu o tempo mínimo necessário neste conteúdo. Reveja as informações desta página para destravar a próxima unidade de estudo.'
                var div = document.getElementById('tempo_minimo_text').innerText = texto;
            }
            e.preventDefault();
            redireciona = false;
            return false;
        }
        <?php } ?>
    }
    <?php if ($_GET['alert'] == 'cargaCompleta') {
        $dataHoje = new DateTime($ava['data_inicio_ava']);
        $horario_liberado = $dataHoje->modify("+  {$ava['carga_horaria_min']} hours");
        $horario_liberado = $horario_liberado->format("d/m/Y H:i:s");
        $alert = sprintf($idioma['carga_incompleta'], $horario_liberado);
        echo "javascript:disparaAlertaCargaIncompleta('{$alert}');";
    }
    ?>
    $(".btnProximo").on("click contextmenu", function (e) {
        <?php if(!empty($objeto['idconteudo'])){ ?>
        verificaCliques(e, '<?= $objeto['idconteudo'] ?>');
        <?php } ?>
        verificaTempoMinimo(e);
        verificarDias(e);
        redirecionaPag();
    });
    <?php
    if (strpos($objeto['objeto']['arquivo_tipo'], 'pdf') !== false) { ?>
    window.pdfDocument.addEventListener('load', function () {
        window.pdfDocument.contentDocument.getElementById('download').style.display = 'none';
        window.pdfDocument.contentDocument.getElementById('print').style.display = 'none';
    });
    <?php
    } ?>
	function disparaAlertaCargaIncompleta(texto){
        if($("#div_2").find("div_2#alerta-carga-horaria-incompleta").length==0){
            $("#div_2").append("<div id='alerta-carga-horaria-incompleta' class='alert alert-warning alert-dismissible '  role='alert'> <div id='alerta-carga-horaria-incompleta-texto' style='font-size: 16px; font-weight: bold;'></div> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div>");


            var div = document.getElementById('alerta-carga-horaria-incompleta-texto').innerText = texto;
        }

    }

    function disparaAlertaLiberacaoTemporariaDataValid(texto){
        if($("#div_2").find("div_2#alerta-liberacao-temporaria").length==0){
            $("#div_2").append("<div id='alerta-liberacao-temporaria' class='alert alert-warning alert-dismissible '  role='alert'> <div id='alerta-liberacao-temporaria-texto' style='font-size: 16px; font-weight: bold;'></div> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div>");


            var div = document.getElementById('alerta-carga-horaria-incompleta-texto').innerText = texto;
        }

    }
</script>
<?php
if (!empty($config['marcaDaguaIframeVideo'] && $objeto['tipo'] == 'video')) {
    ?>
    <script>
        var id;
        var HeightVideo;
        var widthVideo;
        var timeOut;
        <?php
        if(!empty($usuario['documento'])){
        ?>
        var phrase = ["<?= $usuario['nome']; ?>", "<?= $usuario['documento']; ?>", "<?= $usuario['email']; ?>"];
        <?php
        } else {
        ?>
        var phrase = ["<?= $usuario['nome']; ?>", "<?= $usuario['email']; ?>"];
        <?php
        }
        ?>

        //Cria Id
        function makeId() {
            var text = "";
            var possible = "abcdefghijklmnopqrstuvwxyz";

            for (var i = 0; i < 5; i++){
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            return text;
        }

        //Sortea frase da array
        function sortPhrase() {
            return phrase[Math.floor(Math.random()*phrase.length)];
        }

        //Sortea posiÃ§Ã£o Top
        function topPosition() {
            var topMin = 0;
            var topMax = $(".videoIframe").height() - ($('#'+id).height() + 40);

            return Math.floor(Math.random()*(topMax-topMin+1)+topMin);
        }

        //Sortea posiÃ§Ã£o Left
        function leftPosition() {
            var leftMin = 0;
            var leftMax = $(".videoIframe").width() - ($('#'+id).width() + 40);

            return Math.floor(Math.random()*(leftMax-leftMin+1)+leftMin);
        }

        //Posiciona a div
        function position(id){
            var top = topPosition();
            var left = leftPosition();

            if (top > $(".videoIframe").offset().top + $(".videoIframe").height() - $('#'+id).outerHeight()) {
                top = $(".videoIframe").offset().top + $(".videoIframe").height() - $('#'+id).outerHeight();
            }

            if (left > $(".videoIframe").offset().left + $(".videoIframe").width() - $('#'+id).outerWidth()) {
                left = $(".videoIframe").offset().left + $(".videoIframe").width() - $('#'+id).outerWidth();
            }

            $('#'+id).css({
                top: top,
                left: left
            });
        }

        //Cria div da frase
        function createElement(){
            id = makeId();

            if ($(window).width() <= 768 ) {
                $("body").prepend("<div id='"+id+"' style='position: absolute; display:inline-table; color: #000000; font-size: 12px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; z-index: 2147483648;' >"+sortPhrase()+"</div>");
            } else {
                $("body").prepend("<div id='"+id+"' style='position: absolute; display:inline-table; color: #000000; font-size: 20px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; z-index: 2147483648;' >"+sortPhrase()+"</div>");
            }

            $('#'+id).insertAfter( ".videoIframe" );

            position(id);

            timeOut = setTimeout(function(){ removeElement('#'+id); }, 3000); //Tempo do elemento em tela
        }

        //Remove div da frase
        function removeElement(a){
            $(a).remove();
            timeOut = setTimeout(createElement, 5000); //Tempo do intervalo sem o elemento em tela
        }

        createElement();

        //Verifica fullscreen e remove a div da frase
        $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange', function() {
            clearTimeout(timeOut);
            removeElement('#'+id);
        });
    </script>
    <?php
}
?>

<?php
if ($config['bloqueioConteudo']){ ?>
    <script language='JavaScript'>

    function mensagem(){
        alert('Conteúdo bloqueado!');
        return false;
    }

    function bloquearCopia(Event){
        var Event = Event ? Event : window.event;
        var tecla = (Event.keyCode) ? Event.keyCode : Event.which;
        if(tecla == 17){
            mensagem();
        }
    }


        document.onkeypress = bloquearCopia;
        document.onkeydown = bloquearCopia;
        document.oncontextmenu = mensagem;

        //Bloqueia seleção do conteúdo
        $("body").css('user-select', 'none');
        $("body").css('-o-user-select', 'none');
        $("body").css('-moz-user-select', 'none');
        $("body").css('-khtml-user-select', 'none');
        $("body").css('-webkit-user-select', 'none');





    </script>
    <?php
}
?>

</body>
</html>