<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <link rel="stylesheet" href="/assets/css/calendario.css" type="text/css" media="screen" />
    <head>
        <?php incluirLib("head", $config, $usuario); ?>
        <style media="screen">
            body{
                background-color: white;
                border:none;
                overflow-x: hidden;
                height:100%;
            }
        </style>
    </head>
    <body style="padding-top: 2px;" class="box-body-iframe">
        <div id="agendamento" class="container-fluid">
            <h2 class="tituloOpcao"><?php echo $idioma["enviar_email"]; ?></h2>
            <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/pdf/enviar_email?<?php echo $_SERVER["QUERY_STRING"]; ?>" target="_parent">
                <label><?php echo $idioma["label"]; ?></label>
                <select class="invisivel" id="emails" name="emails"></select>
                <br>
                <br>
                <input type="submit" class="btn btn-primary btn-cadastrar" value="Enviar">
            </form>
         </div>
        <script src="/assets/js/jquery.1.7.1.min.js"></script>
        <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
        <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {
        $("#emails").fcbkcomplete({
            json_url: '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]?>/buscar_usuario',
            addontab: true,
            height: 10,
            maxshownitems: 10,
            cache: true,
            maxitems: 10,
            input_min_size: 0,
            filter_selected: true,
            firstselected: true,
            complete_text: '<?= $idioma['descricao_imovel']; ?>',
            addoncomma: true,
            newel: true
        });
    });
</script>
    </body>
</html>
