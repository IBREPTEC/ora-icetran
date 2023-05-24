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
<style>
    .container-download{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;


    }
    .download-box{
        border: #fefefe 1px solid;
    }

</style>

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
        <!-- Box -->
        <div class="box-side box-bg">
            <span class="top-box box-amarelo">
                <h1>Videoteca</h1>
                <i class="icon-copy"></i>
            </span>
            <h2 class="ball-icon">&bull;</h2>
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12 abox extra-align">
                    <?php if (count($pastas)) { ?>
                        <a href="javascript:history.back()" class="link_voltar">
                            Voltar para a página anterior...</a> <br><br>
                    <?php } ?>
                    <div class="row-fluid">

                        <?php foreach ($pastas as $pasta) {
                            ?>
                            <div class="col-md-6 " >
                                <div class="span3 box " style="text-align: center;margin-right: 30px;margin-top:10px;margin-left:30px;">
                                    <div class="title-download">
                                        <h1><?php echo $pasta['nome']; ?></h1>
                                    </div>

                                    <div class="container-download " style="text-align: center" >

                                        <div class="archive-donwload">
                                            <p><?php echo $pasta['titulo']; ?></p>
                                            <?php if(!$pasta['link_1'] && !$pasta['link_2']){ ?>
                                            <iframe  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" width="225" height="105" frameborder="0" allowfullscreen src="<?php echo '/storage/videoteca/'.$pasta['arquivo_servidor']; ?>" class="primary" rel="facebox">
                                                <?php }elseif($pasta['link_1']){?>
                                                <iframe  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" width="225" height="105" frameborder="0" allowfullscreen src="<?php echo $pasta['link_1']; ?>" class="primary" rel="facebox">


                                                    <?php } elseif($pasta['link_2']){?>

                                                    <iframe  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" width="225" height="105" frameborder="0" allowfullscreen src="<?php echo $pasta['link_2']; ?>" class="primary" rel="facebox">

                                                        <?php }?>


                                                    </iframe>
                                        </div>

                                    </div>

                                    <div class="clear"></div>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (count($pastas)) { ?>
                        <a href="javascript:history.back()" class="link_voltar">
                            Voltar para a página anterior...</a> <br><br>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>