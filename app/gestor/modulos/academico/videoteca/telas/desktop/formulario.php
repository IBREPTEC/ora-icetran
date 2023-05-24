<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
    <style type="text/css"> #tags{ display: none } </style>
    <!-- <script type="text/javascript" src="/assets/upload/js/plupload.full.min.js"></script> -->
    <script type="text/javascript" src="/assets/upload/js/moxie.js"></script>
    <script type="text/javascript" src="/assets/upload/js/plupload.dev.js"></script>
    <style type="text/css">
        .selected a{
            border: 1px solid #FF00FF
        }

        li{
            list-style: none;
            margin-bottom: 10px
        }

        #uploadfiles + div{
            display: none;
        }
    </style>
</head>
<body>
<? incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <? if($url[3] == "cadastrar") { ?>
                <li class="active"><?= $idioma["nav_formulario"]; ?></li>
            <? } else { ?>
                <li class="active"><?php echo $linha["titulo"]; ?></li>
            <? } ?>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>


    <div id="container">
        <!--    <a id="pickfiles" style="display: none" href="javascript:;"></a>-->
        <!--    <a id="uploadfiles"  style="display: none" href="javascript:;"></a>-->
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <?php if($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
                <div class="tabbable tabs-left">
                    <?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?php if($url[3] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
                            <? if($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
                            <? if(count($salvar["erros"]) > 0){ ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                                        <br />
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <form method="post" id="form-post" onsubmit="validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                                <input name="acao" type="hidden" value="salvar" />

                                <?php if ('html5' == $linha['variavel']) : ?>
                                    <input name="variavel" type="hidden" value="html5" />
                                <?php endif; ?>
                                <?php if ('youtube' == $linha['variavel']) : ?>
                                    <input name="variavel" type="hidden" value="youtube" />
                                <?php endif; ?>
                                <?php if ('vimeo' == $linha['variavel']) : ?>
                                    <input name="variavel" type="hidden" value="vimeo" />
                                <?php endif; ?>


                                <?php if($url[4] == "editar") {
                                    echo '<input type="hidden" name="'.$config["banco"]["primaria"].'" id="'.$config["banco"]["primaria"].'" value="'.$linha[$config["banco"]["primaria"]].'" />';
                                    echo '<input type="hidden" name="idpasta" id="idpasta" value="'.$linha['idpasta'].'" />';
                                    foreach($config["banco"]["campos_unicos"] as $campoid => $campo) {
                                        ?>
                                        <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                                        <?php
                                    }


                                    if ('youtube' == $linha['variavel'] || 'vimeo' == $linha['variavel']) {

                                        $linhaObj->GerarFormulario("formulario_youtube_editar",$linha,$idioma);

                                        echo '<input class="span6" readonly="readonly" id="idpasta" name="idpasta" type="hidden" value="'.$linha['idpasta'].'">';

                                    } else {

                                        $linhaObj->GerarFormulario("formulario_editar",$linha,$idioma);
                                       
                                    }
                                } else {
                                    $linhaObj->GerarFormulario("formulario",$_POST,$idioma);

                       if($config['videoteca_local']){
                            echo '
                            <div id="filelist"></div>
                                <div id="bar-container" class="progress progress-info active">
                                    <div id="bar" class="bar" style="width: 0%;"></div>
                                    <div id="status" style="width: 100%; float: left; margin-top: -17px; text-align: center;">Aguardando...</div>
                            </div>';
                        }
                                }
                                ?>
                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>


    <script type="text/javascript">
        // Custom example logic
        var regras = new Array();



                        <?php
                        foreach($config["formulario"] as $fieldsetid => $fieldset) {
                        foreach($fieldset["campos"] as $campoid => $campo) {
                        if(is_array($campo["validacao"])){
                        foreach($campo["validacao"] as $tipo => $mensagem) {
                        if($campo["tipo"] == "file"){
                        ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>"))


                        <?php } else { ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                        <?php }
                        }
                        }
                        }
                        }
                        ?>




    </script>


    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>
    <script type="text/javascript">
        var regras = new Array();
        <?php /*
    foreach($config["formulario"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
              if($campo["tipo"] == "file"){
                ?>
                    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                <? } else { ?>
                    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                    <? }
                }
            }
        }
    }*/
        ?>

        // Complete thumbs ;)
        $("#tags").fcbkcomplete({
            width: 450,
            width_options: 400,
            width_dialog: 400,
            addontab: false,
            height: 10,
            maxshownitems: 0,
            cache: false,
            maxitems: 50,
            filter_selected: true,
            filter_case: false,
            firstselected: true,
            complete_text: "",
            addoncomma: true,
            newel: true
        });

        // When page is load, I populate
        // the select with itens of tags
        <?php
        $format = '$("#%s").trigger("addItem", [{"title":"%s", "value": "%s"}]);';
        foreach ($videotecaTags->listTagsForVideoId($url[3]) as $value) {
            printf(
                $format,
                'tags',
                $value->title,
                $value->title
            );
            // print_r($value);
        }
        ?>
        // $("#tags").trigger("addItem",
        // <?php echo json_encode($videotecaTags->listTagsForVideoId(20)); ?>);


        jQuery(function($){
            <?
            foreach($config["formulario"] as $fieldsetid => $fieldset) {
                foreach($fieldset["campos"] as $campoid => $campo) {

                    if ($campo['mascara']) {
                        echo '$("#'.$campo['id'].'").mask("'.$campo['mascara'].'");';
                    }

                    if ($campo['datepicker']) {
                        echo '$("#'.$campo['id'].'" ).datepicker($.datepicker.regional["pt-BR"]);';
                    }

                    if ($campo['numerico']) {
                        echo '$("#'.$campo["id"].'").keypress(isNumber);';
                        echo '$("#'.$campo["id"].'").blur(isNumberCopy);';
                    }

                    if($campo['decimal']) {
                        echo '$("#'.$campo['id'].'").maskMoney({symbol:"R$",decimal:",",thousands:"."});';
                    }
                }
            } ?>
        });
    </script>
</div>
</body>
</html>
