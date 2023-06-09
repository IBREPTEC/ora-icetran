<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>
    @media (min-width: 1200px){
            .container, .navbar-fixed-bottom .container, .navbar-fixed-top .container, .navbar-static-top .container, .span12 {
                width: 1272px;
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
        <div class="box-side box-bg span12 offset2">
            <span class="top-box box-amarelo">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-star-empty"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <!-- /Rota Topo -->        
            <div class="clear"></div>
            
            <div class="row-fluid">
                <div class="span12">                
                    <div class="abox extra-align">
                        <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                        <?php 
                        }
                        $contadorFavoritos = 0;
                        foreach($favoritos as $favorito) { 
                            $contadorFavoritos++;
                            if($contadorFavoritos == 1) { ?>
                                <!-- Linha -->
                                <div class="row-fluid">
                            <?php } ?>
                            <div class="span4">                                
                                <div class="box-gray favorite rel-box">
                                    <p class="text-standard"><?php echo $favorito['objeto']['nome']; ?></p>
                                    <div id="closed" class="closed-i" onclick="remover(<?php echo $favorito['idfavorito']; ?>);">x</div>
                                    <hr />
                                    <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/rota/<?php echo $favorito['pagina']; ?>">
                                        <div class="btn btn-azul"><?php echo $idioma['visualizar']; ?></div>
                                    </a>
                                </div>
                            </div>
                            <?php 
                            if($contadorFavoritos == 3) { 
                                $contadorFavoritos = 0; ?>                                   
                                </div>
                                <!-- /Linha -->
                            <?php } 
                        } 
                        if($contadorFavoritos > 0) { ?>   
                            </div>                                            
                            <!-- /Linha -->    
                        <?php } ?>
                        </div>
                    </div>
                    <form name="formRemover" id="formRemover" method="post" action="">
                        <input type="hidden" name="acao" id="acao" value="remover">
                        <input type="hidden" name="idfavorito" id="idfavorito" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript">
    function remover(id) {
        if(confirm('<?php echo $idioma['confirma_remover_favorito']; ?>')) {
            document.getElementById('idfavorito').value = id;
            document.getElementById('formRemover').submit();
        } else
            return false;
    }
</script>
</body>
</html>