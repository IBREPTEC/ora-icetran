<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
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
                    <?php incluirTela("inc_menu_edicao_sub", $config, $linha); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                            <?php //print_r2($_POST);exit; ?>
                            <?php if ($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <?php } ?>
                            <?php if (count($salvar["erros"]) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <?php foreach ($salvar["erros"] as $ind => $val) { ?>
                                        <br/>
                                        <?php echo $idioma[$val]; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form class="well" method="post">
                                <p><?php echo "<strong>Sindicatos:</strong> "; ?></p>
                                <select id="idsindicato" name="idsindicato">
                                    <option></option>
                                    <?php foreach ($Sindicatos as $sindicato){ ?>
                                        <option value="<?= $sindicato['idsindicato'] ?>" > <?= $sindicato['nome_abreviado'] ?> </option>
                                    <?php } ?>
                                </select>
                               <br/><br/>
                                <p><?= $idioma["form_instituicao"]; ?></p>
                                <select id="idcentro_custo" style="display: none;" name="idcentro_custo"></select>
                                
                                <br/><br/>
                                    <input type="hidden" id="acao" name="acao" value="salvar_centros">
                                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>"/>  
                            </form>
                            <br/>

                            <form method="post" id="remover_centro" name="remover_centro">
                                <input type="hidden" id="acao" name="acao" value="remover_centro">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <?php $Info = 0; ?>
                            <?php if (count($CentroDeCustos) != 0) { ?>
                                    <?php foreach ($CentroDeCustos as $sindicato) {
                                    ?>
                                        <?php if(!empty($sindicato)){ $Info = 1; ?>
                                        <h3 class="tituloOpcao"> <?= $sindicato[0]["nome_sindicato"] ?> </h3>
                            <table style="  width: 400px !important;" class="table table-striped tabelaSemTamanho">
                                <thead>
                                <tr>
                                    <th><?= $idioma["listagem_nome"]; ?></th>
                                    <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                    <tbody>
                                        <?php foreach ($sindicato as $Centro) {
                                            ?>
                                            <tr>

                                                <td><?php echo $Centro["nome"]; ?></td>
                                                <td>
                                                    <?php if ($perfil["permissoes"][$url[2] . "|4"]) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini"
                                                           data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                           data-placement="left" rel="tooltip"
                                                           onclick="remover(<?php echo $Centro["idsubcategoria_centro"]; ?>)"><i
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
                                    </tbody>
                                </table>
                                    <?php } ?>
                                <?php } ?>
                                <?php } ?> 
                                <?php if(count($CentroDeCustos) == 0){ //SEM INFORMAÇÃO COMEÇA AQUI ?>
                                    <table style="  width: 400px !important;" class="table table-striped tabelaSemTamanho">
                                        <thead>
                                            <tr>
                                                <th><?= $idioma["listagem_nome"]; ?></th>
                                                <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            
            function criarBuscaCentroCusto() {
                $("#idcentro_custo").fcbkcomplete({
                    json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/solicita_centro?idsindicato=" + $('#idsindicato').val() ,
                    addontab: true,
                    height: 10,
                    maxshownitems: 10,
                    cache: true,
                    maxitems: 20,
                    filter_selected: true,
                    firstselected: true,
                    complete_text: "<?= $idioma["mensagem_select"]; ?>",
                    addoncomma: true
                });
            }
            criarBuscaCentroCusto();
            
            $('#idsindicato').change(function () {
                //remove busca anterior
                //classe: holder, facebook-auto
                $(".holder").remove();
                $(".facebook-auto").remove();
                $(".bit-box").remove();
                $(".bit-input").remove();
                $(".maininput").remove();
                criarBuscaCentroCusto();
            });
            
            
            
        });
        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_centro").submit();
            }
        }
    </script>
</div>
</body>
</html>

