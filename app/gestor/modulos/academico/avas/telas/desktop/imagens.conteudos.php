<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link href="/assets/css/menuVertical.css" rel="stylesheet" />

</head>
<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        width: 100%;
        padding-top: 10px;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    .container {
        padding: 2px 2px;
    }
</style>
<body>
<? incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? echo $linha["ava"]; ?></a> <span class="divider">/</span> </li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/conteudos"><?= 'Conteúdo'; ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $linha["nome"]; ?></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo box-ava">
                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_submenu",$config,$linha); ?>
                    <div class="ava-conteudo">
                        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                        <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
                        <?php include("inc_submenu_conteudos.php"); ?>
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_remover"]; ?></h2>
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
                            <br/>
                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/cadastrar_imagem" class="btn btn-small btn-primary dropdown-toggle btn-mini" data-placement="left" rel="tooltip facebox">Inserir Imagem</a>

                            <div class="row-fluid">
                                <br/>
                                <?php foreach ($conteudos_imagens as $imagem) {
                                    ?>

                                    <div class="span3" >

                                        <div class="card" id="card_<?=$imagem['idimagem']?>">
                                            <img id="imagem_<?=$imagem['idimagem']?>"src="<?php echo '/storage/avas_conteudos_imagem_exibicao/'.$imagem['imagem_exibicao_servidor']; ?>" alt="Avatar" style="width:100%;height: 200px;">
                                            <div class="container">
                                                <div class="span1">

                                                    <h5 style="position: relative;float: left;text-transform: capitalize"><b><?php echo $imagem['titulo']; ?></b></h5>                                                    <br/>
                                                    <p style="position: relative;float: left"><b>Ordem:</b><?php echo $imagem['ordem']; ?></p>

                                                </div>

                                                <div class="span2" >
                                                    <button class="btn btn-primary"style="position: relative;float: right" onclick="removerImagem(<?=$imagem['idimagem']?>)"><i class="icon-remove icon-white"></i></button>

                                                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/editar_imagem/<?=$imagem['idimagem']?>"  class="btn btn-primary dropdown-toggle" data-placement="left" rel="tooltip facebox" style="position: relative;float: right" ><i class="icon-pencil icon-white"></i></a>


                                                </div>
                                            </div>

                                        </div>




                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
<script src="/assets/js/ajax.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function($) {

    });
    function removerImagem(id) {
        if(confirm("Deseja realmente remover essa imagem?")) {
            $.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"]."/".$url["6"]."/json/remover_imagem"; ?>',{idimagem:id, ajax: 'true'}, function(json){
                $('#card_'+id+'').hide()
                alert('Imagem removida com sucesso!')
            });
        }
    }
</script>


</html>