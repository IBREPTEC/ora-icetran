<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style type="text/css">
        .pagarme-checkout-btn {
            color: #000000;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1>
                <?= $idioma["pagina_titulo"]; ?>&nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>">
                    <?= $idioma["nav_modulo"]; ?>
                </a> <span class="divider">/</span>
            </li>
            <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
            <?php
            if ($_GET['q']) {
                ?>
                <li><span class="divider">/</span> <a
                    href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a>
                </li>
                <?php
            }
            ?>
            <span class="pull-right" style="padding-top:3px; color:#999">
                <?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?>
            </span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <?php
                if ($_POST['msg']) {
                    ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST['msg']]; ?></strong>
                    </div>
                    <?php
                }

                if (count($salvar['erros']) > 0) {
                    ?>
                    <div class="alert alert-error fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                            <strong><?= $idioma['form_erros']; ?></strong>
                            <?php
                            foreach ($salvar['erros'] as $ind => $val) {
                                echo '<br />' . $idioma[$val];
                            }

                            if ($salvar['exception']) {
                                echo '<br />' . $salvar['exception'];
                            }
                            ?>
                        </div>
                    <?php
                }
                ?>
                <div id="listagem_informacoes">
                    <?php printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                    <br/>
                    <?php printf($idioma["paginas"], $linhaObj->Get("pagina"), $linhaObj->Get("paginas")); ?>
                </div>
                <?php $linhaObj->GerarTabela($dadosArray, $_GET["q"], $idioma); ?>
                <div id="listagem_form_busca">
                    <div class="input">
                        <div class="inline-inputs">
                            <?= $idioma["registros"]; ?>
                            <form action="" method="get" id="formQtd">
                                <?php
                                if ($_GET['buscarpor'] && $_GET['buscarem']) {
                                    ?>
                                    <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET['buscarpor']; ?>">
                                    <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET['buscarem']; ?>">
                                <?php
                                }

                                if (is_array($_GET['q'])) {
                                    foreach ($_GET['q'] as $ind => $valor) {
                                        ?>
                                        <input id="q[<?= $ind ?>]" type="hidden" value="<?= $valor; ?>" name="q[<?= $ind ?>]"/>
                                        <?php
                                    }
                                }

                                if ($_GET['cmp']) {
                                    ?>
                                    <input id="cmp" type="hidden" value="<?= $_GET["cmp"]; ?>" name="cmp"/>
                                    <?
                                }

                                if ($_GET['ord']) {
                                    ?>
                                    <input id="ord" type="hidden" value="<?= $_GET["ord"]; ?>" name="ord"/>
                                    <?
                                }
                                ?>
                                <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?= $linhaObj->Get('limite'); ?>"/>
                                <a href="javascript:document.getElementById('formQtd').submit();" class="btn small">
                                    <?= $idioma['exibir']; ?>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                $linhaObj->paginas = ceil($numTotalRegistros/$_GET["qtd"]);
                if (  $linhaObj->Get("paginas") > 1 ) {
                    ?>
                    <div class="pagination">
                        <?php 
                        if(!$_GET['cmp']){ 
                            $linhaObj->ordem = 'DESC';
                            $linhaObj->ordem_campo = 'c.idconta'; 
                        }else{ 
                            $linhaObj->ordem = $_GET['ord'];
                            $linhaObj->ordem_campo = $_GET['cmp']; 
                        } 
                        ?>
                        <ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul>
                    </div>
                    <?php
                }
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script language="javascript" type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#qtd").keypress(isNumber);
            $("#qtd").blur(isNumberCopy);

            $("input[name='q[1|c.idconta]']").keypress(isNumberCopy);
            $("input[name='q[1|c.idconta]']").blur(isNumberCopy);

            $("input[name='q[1|c.qnt_matriculas]']").keypress(isNumberCopy);
            $("input[name='q[1|c.qnt_matriculas]']").blur(isNumberCopy);

            $("input[name='q[4|c.valor]']").maskMoney({symbol:"R$",decimal:",",thousands:"."});

            $("input[name='q[3|c.data_vencimento]']").mask("99/99/9999");
            $("input[name='q[3|c.data_vencimento]']").datepicker($.datepicker.regional["pt-BR"]);

            $("input[name='q[3|c.data_modificacao_fatura]']").mask("99/99/9999");
            $("input[name='q[3|c.data_modificacao_fatura]']").datepicker($.datepicker.regional["pt-BR"]);

            $("input[name='q[6|p.id]']").keypress(isNumberCopy);
            $("input[name='q[6|p.id]']").blur(isNumberCopy);

            $(".select231").select2();
        });

        function confirmaCancelamento() {
            if (!confirm("<?php echo $idioma['confirma_cancelamento']; ?>")) {
                event.preventDefault();
                return;
            }
        }

        $(document).on('submit', 'form', function(e){
            if (!confirm("<?php echo $idioma['confirma_operacao']  ?>")) {
                e.preventDefault();
                return;
            }
        });

    </script>
</div>
</body>
</html>