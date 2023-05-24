<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
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
        <div class="span12">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                            <div id="listagem_informacoes">
                                <h4>Sindicato: &nbsp;
                                    <small><?= $linha['sindicato'] ?></small>
                                </h4>
                                <?= $idioma["texto_explicativos"]; ?>
                            </div>
                            <? if ($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
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
                            <form class="well" method="post" id="form">
                                <p><?= $idioma["form_associar"]; ?></p>
                                <?php if ($perfil["permissoes"][$url[2] . "|5"]) { ?>
                                    <select id="matriculas" name="matriculas"></select>
                                    <br/>
                                    <br/>
                                    <input type="hidden" id="acao" name="acao" value="associar_matricula">
                                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>"/>
                                <?php } else { ?>
                                    <select id="matriculas" name="matriculas" disabled="disabled"></select>
                                    <br/>
                                    <br/>
                                    <a href="javascript:void(0);" rel="tooltip"
                                       data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>"
                                       data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                <?php } ?>
                            </form>
                            <br/>

                            <form method="post" id="remover_matricula" name="remover_matricula">
                                <input type="hidden" id="acao" name="acao" value="remover_matricula">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <table class="table table-striped tabelaSemTamanho">
                                <thead>
                                <tr>
                                    <th><?= $idioma["listagem_matricula"]; ?></th>
                                    <th><?= $idioma["listagem_nome"]; ?></th>
                                    <th><?= $idioma["listagem_valor"]; ?></th>
                                    <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <form method="post">
                                    <input type="hidden" name="acao" value="salvar_valores_matriculas"/>
                                    <tbody>
                                    <?php if (count($associacoesArray) > 0) { ?>
                                        <?php foreach ($associacoesArray as $ind => $associacao) { ?>
                                            <tr>
                                                <td><?php echo $associacao["idmatricula"]; ?></td>
                                                <td><?php echo $associacao["nome"]; ?></td>
                                                <td><input class="span2" type="text"
                                                           id="matriculas_valor_<?php echo $associacao['idmatricula']; ?>"
                                                           name="matriculas_array[<?= $associacao['idpagamento_matricula']; ?>][valor]"
                                                           value="<?php echo number_format($associacao['valor'], 2, ',', '.'); ?>"/>
                                                </td>
                                                <td>
                                                    <?php if ($perfil["permissoes"][$url[2] . "|5"]) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini"
                                                           data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                           data-placement="left" rel="tooltip"
                                                           onclick="remover(<?php echo $associacao["idpagamento_matricula"]; ?>)"><i
                                                                class="icon-remove"></i></a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini disabled"
                                                           data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>"
                                                           data-placement="left" rel="tooltip"><i
                                                                class="icon-remove"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="5"><input class="btn" type="submit" value="Salvar"/></td>
                                    </tr>
                                    </tbody>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#matriculas").fcbkcomplete({
                json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_matricula/<?= $linha['idsindicato']; ?>",
                addontab: true,
                height: 10,
                maxshownitems: 10,
                cache: true,
                maxitems: 20,
                filter_selected: true,
                firstselected: true,
                input_min_size: 1,
                complete_text: "<?= $idioma["mensagem_select"]; ?>",
                addoncomma: true
            });
        });

        <?php if(count($associacoesArray)) {
          foreach($associacoesArray as $mat) { ?>
        $("#<?= 'matriculas_valor_'.$mat["idmatricula"]; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
        <?php } } ?>

        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_matricula").submit();
            }
        }
    </script>
</div>
</body>
</html>