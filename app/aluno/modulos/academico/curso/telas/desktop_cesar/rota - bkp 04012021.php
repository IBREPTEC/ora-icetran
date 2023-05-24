<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
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
    <!-- Rota de aprendizagem -->
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <div class="box-side box-bg">
            <!-- Rota Topo -->
            <div class="top-box box-verde">
                <h1><?= $idioma['rota_aprendizagem']; ?></h1>
                <i class="icon-sort-by-attributes-alt"></i>            
            </div>
            <h2 class="ball-icon">&bull;</h2> 
            <!-- /Rota Topo -->        
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="abox extra-align">
                    <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong style="font-style: normal;font-size: .8rem;"><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
						<?php } ?>
                        <!-- Assunto -->
                        <?php 
						$contadorDeObjetoDivisor = 0;
                        $contadorReconhecimento = 0;
						$pagina = 0;
						$voceEstaAqui = false;
                        foreach($rotaDeAprendizagem as $objetoRota) {
                            if ($objetoRota['tipo'] == 'reconhecimento') {
                                $contadorReconhecimento++;
                                continue;
                            }
							if ($objetoRota['tipo'] == 'objeto_divisor') { 
								if ($contadorDeObjetoDivisor) {
                                    ?>
                                    </div></div></div>
								    <?php 
								}
								$contadorDeObjetoDivisor++;
								?>
                                <div class="" id="accordion2">
                                    <div>                      
                                        <a class="" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?= $objetoRota['idobjeto']; ?>">
                                            <div class="btn btn-medium btn-mob" style="background: #<?= $objetoRota['objeto']['cor_bg']; ?>;color: #<?= $objetoRota['objeto']['cor_letra']; ?>;"><?= $objetoRota['objeto']['nome']; ?></div>
                                        </a>                      
                                        <div id="collapse<?= $objetoRota['idobjeto']; ?>" class="list-timeline accordion-body collapse <?php if($contadorDeObjetoDivisor == 1) { ?>in<?php } ?>">
                                <?php 
							} elseif($contadorDeObjetoDivisor) {
								$pagina++;
								$contabilizado = $matriculaObj->verificaContabilizado($ava['idava'], 'objeto', $objetoRota['idobjeto']);

                                $onclickExercicio = null;
                                if ($objetoRota['tipo'] == 'exercicio') {
                                    $onclickExercicio = 'onclick="alert(\'' . sprintf($idioma['realizar_exercicio'], $objetoRota['objeto']['nome']) . '\');"';
                                }
								
								$bola = 'amarelo';
								if($contabilizado || $ava['pre_requisito'] == 'N' || $pagina == 1 || $voceEstaAqui) 
									$bola = 'verde';
								
                                    ?>
                                            <div class="item-toggle">
                                                <div><h2 class="color-ball-<?= $bola; ?>">•</h2></div>
                                                <?php
                                                if ($contabilizado || $ava['pre_requisito'] == 'N' || $pagina == 1 || $voceEstaAqui) {
                                                    ?>
                                                    <a
                                                    href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/rota/<?= $pagina + $contadorReconhecimento; ?>#conteudo"
                                                    <?= $onclickExercicio; ?>>
                                                    <?php
                                                }
                                                ?>
                                                <h1><?= $objetoRota['objeto']['nome']; ?></h1>
                                                <?php
                                                if($contabilizado || $ava['pre_requisito'] == 'N' || $pagina == 1 || $voceEstaAqui) {
                                                    ?>
                                                    </a>
                                                    <?php
                                                }

                                                if ($voceEstaAqui) {
                                                    ?>
                                                    <span class="no-mobile">
                                                        <p></p>
                                                        <h4><?= $idioma['voce_esta_aqui']; ?></h4>
                                                    </span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            
					            <?php 
                                if($objetoRota['idobjeto'] == $ultimo['idobjeto'])
                                    $voceEstaAqui = true;
                                else
                                    $voceEstaAqui = false;
                            } 
                        } ?>
                                        </div>
                                    </div>
                                </div>
                        <!-- Assunto -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Rota de aprendizagem -->
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>