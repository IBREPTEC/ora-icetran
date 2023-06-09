<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>
<? incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?php echo $linha["nome"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span9">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php if (!$linha['idmatricula'] && $linha['fatura'] == 'N') incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                            <? if (count($salvar["erros"]) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach ($salvar["erros"] as $ind => $val) { ?>
                                        <br/>
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                            <? } ?>

                            <section id="historico_reserva">
                                <legend>
                                    <?= $idioma["historico_label"]; ?>
                                </legend>


                                <table cellpadding="5" cellspacing="0"
                                       class="table table-bordered table-condensed tabelaSemHover" style="width:800px;">
                                    <tr>
                                        <td width="100" bgcolor="#F4F4F4">
                                            <strong><?php echo $idioma["historico_modulo"] ?></strong></td>
                                        <td width="200" bgcolor="#F4F4F4">
                                            <strong><?php echo $idioma["historico_usuario"] ?></strong></td>
                                        <td width="140" bgcolor="#F4F4F4">
                                            <strong><?php echo $idioma["historico_data"] ?></strong></td>
                                        <td bgcolor="#F4F4F4"><strong><?php echo $idioma["historico_desc"] ?></strong>
                                        </td>
                                    </tr>
                                    <tbody>
                                    <tr>
                                        <td colspan="4" style="padding:0px;">
                                            <div style="height:400px; overflow:auto;">
                                                <table border="0" cellspacing="0" width="100%">
                                                    <?php
                                                    // echo "<pre>".var_dump($historicoArray)."</pre>";
                                                    // exit;
                                                    foreach ($historicoArray as $ind => $val) {
                                                        if ($val["tipo"] == "situacao" and $val["acao"] == "modificou" and $val["situacao"]["para"]["idsituacao"] == $val["situacao_pago"]) {
                                                            ?>
                                                            <tr>
                                                                <td colspan="4" bgcolor="#0088e8">
                                                                    <strong><?php echo $idioma["historico_quitada"]; ?></strong>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>

                                                        <tr>
                                                            <td width="100"><?php echo $val["modulo"] ?></td>
                                                            <td width="200"><?php echo $val["usuario"]["nome"]; ?></span></td>
                                                            <td width="140"><?php echo formataData($val["data_cad"], 'br', 1) ?>
                                                                <br/><span
                                                                    style="color:#999;"><?php echo $idioma["historico_id"] . ' ' . $val["idhistorico"] ?></span>
                                                            </td>
                                                            <td width=""><?php echo $val["descricao"] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </section>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <div class="well">
                    <?= $idioma["nav_cadastrar_explica"]; ?>
                    <br/>
                    <br/>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar"
                       class="btn primary"><?= $idioma["nav_cadastrar"]; ?></a>
                </div>
            <? } ?>
            <?php incluirLib("sidebar_" . $url[1], $config); ?>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
</div>
</body>
</html>