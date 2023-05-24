<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
	<section id="global">
		<div class="page-header">
    		<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  		</div>
  		<ul class="breadcrumb">
      		<li><a href="/<?= $url[0]; ?>"><?= $idioma['nav_inicio']; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma['nav_modulo']; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma['pagina_titulo']; ?></a> <span class="divider">/</span></li>
  			<li class="active"><?= $linha['nome']; ?> <span class="divider">/</span></li>
            <li class="active"><?= $idioma['nav_formulario']; ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma['hora_servidor']; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid">
  		<div class="span12">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma['btn_sair']; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha['nome']; ?> </h2>
            	<div class="tabbable tabs-left">
			 		<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              		<div class="tab-content">
                		<div class="tab-pane active" id="tab_editar">
                      		<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
							<?php
                            if ($_POST["msg"]) {
                                ?>
                            	<div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                  	<strong><?= $idioma[$_POST["msg"]]; ?></strong>
                              	</div>
                          	    <?php
                            }

                            if (count($salvar["erros"]) > 0) {
                                ?>
                              	<div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <?php
                                    foreach ($salvar["erros"] as $ind => $val) {
                                        echo '<br />'.$idioma[$val];
                                    }
                                    ?>
                                </div>
                          	    <?php
                            }

                            if ($perfil["permissoes"][$url[2]."|5"]) {
                                $linha = $_POST;
                                ?>
                                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal well">
                                    <input type="hidden" id="acao" name="acao" value="salvar_regra">
                                    <?= $linhaObj->GerarFormulario("formulario", $_POST, $idioma); ?>

                                    <div class="form-actions">
                                        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                                        <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>"/>
                                    </div>
        						</form><br />
                                <?php
                            }
                            ?>
     						<table class="table table-striped tabelaSemTamanho">
        						<thead>
                                    <tr>
                                      <th width="60"><?= $idioma["listagem_idregra"]; ?></th>
                                      <th><?= $idioma["listagem_idpolo"]; ?></th>
                                      <th><?= $idioma["listagem_idcentro_custo"]; ?></th>
                                      <th><?= $idioma["listagem_idcategoria"]; ?></th>
                                      <th><?= $idioma["listagem_idsubcategoria"]; ?></th>
                                      <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                    </tr>
        						</thead>
        						<tbody>
                                    <?php if(count($regrasArray) > 0) { ?>
										<?php foreach($regrasArray as $ind => $var) { ?>
                                            <tr>
                                                <td><?= $var["idregra"]; ?></td>
                                                <td><?= $var["cfc"]; ?></td>
                                                <td><?= $var["centro_custo"]; ?></td>
                                                <td><?= $var["categoria"]; ?></td>
                                                <td><?= $var["subcategoria"]; ?></td>
                                                <td>
                                                    <?php
                                                    if ($perfil["permissoes"][$url[2]."|6"]) {
                                                        ?>
                                                        <a href="javascript:void(0);"  class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?= $var["idregra"]; ?>)">
                                                            <i class="icon-remove"></i>
                                                        </a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip">
                                                            <i class="icon-remove"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
									<?php } else { ?>
                                        <tr>
                                            <td colspan="5"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
									<?php } ?>
                                </tbody>
                            </table>
                            <?php
                            if ($perfil["permissoes"][$url[2]."|6"]) {
                                ?>
                                <form method="post" id="remover_regra" name="remover_regra">
                                    <input type="hidden" id="acao" name="acao" value="remover_regra">
                                    <input type="hidden" id="remover" name="remover" value="">
                                </form>
                                <?php
                            }
                            ?>
						</div>
              		</div>
            	</div>
        	</div>
    	</div>
  	</div>
  	<?php incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
        var regras = new Array();
        <?php
        foreach ($config["formulario"] as $fieldsetid => $fieldset) {
            foreach ($fieldset["campos"] as $campoid => $campo) {
                if (is_array($campo["validacao"])) {
                    foreach ($campo["validacao"] as $tipo => $mensagem) {
                        if ($campo["tipo"] == "file"){
                            ?>
                            regras.push("<?= $tipo; ?>,<?= $campo["id"]; ?>,<?= $campo["extensoes"]; ?>,<?= $campo["tamanho"]; ?>,<?= $idioma[$mensagem]; ?>");
                            <?php
                        } else {
                            ?>
                            regras.push("<?= $tipo; ?>,<?= $campo["id"]; ?>,<?= $idioma[$mensagem]; ?>");
                            <?php
                        }
                    }
                }
            }
        }
        ?>

        jQuery(function ($) {
            <?php
            foreach ($config['formulario'] as $fieldsetid => $fieldset) {
                foreach ($fieldset['campos'] as $campoid => $campo) {
                    if ($campo['mascara']) {
                        ?>
                        <?php
                        if ($campo['mascara'] == '99/99/9999') {
                            ?>
                            $("#<?= $campo['id']; ?>").mask("<?= $campo['mascara']; ?>");
                            $('#<?= $campo['id']; ?>').change(function () {
                                if ($('#<?= $campo['id']; ?>').val() != '') {
                                    valordata = $("#<?= $campo['id']; ?>").val();
                                    date = valordata;
                                    ardt = new Array;
                                    ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
                                    ardt = date.split("/");
                                    erro = false;

                                    if (date.search(ExpReg) == -1) {
                                        erro = true;
                                    } else if (((ardt[1] == 4) || (ardt[1] == 6) || (ardt[1] == 9) || (ardt[1] == 11)) && (ardt[0] > 30)) {
                                        erro = true;
                                    } else if (ardt[1] == 2) {
                                        if ((ardt[0] > 28) && ((ardt[2] % 4) != 0)) {
                                            erro = true;
                                        } if ((ardt[0] > 29) && ((ardt[2] % 4) == 0)) {
                                            erro = true;
                                        }
                                    }

                                    if (erro) {
                                        alert("\"" + valordata + "\" não é uma data válida!!!");
                                        $('#<?= $campo["id"]; ?>').focus();
                                        $("#<?= $campo["id"]; ?>").val('');
                                        return false;
                                    }

                                    return true;
                                }
                            });
                            <?php
                        } elseif ($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") {
                            ?>
                            $('#<?= $campo["id"]; ?>').focusout(function () {
                                var phone, element;
                                element = $(this);
                                element.unmask();
                                phone = element.val().replace(/\D/g, '');

                                if (phone.length > 10) {
                                    element.mask("(99) 99999-999?9");
                                } else {
                                    element.mask("(99) 9999-9999?9");
                                }
                            }).trigger('focusout');
                            <?php
                        } else {
                            ?>
                            $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
                            <?php
                        }
                    }

                    if ($campo['datepicker']) {
                        ?>
                        $("#<?= $campo['id']; ?>").datepicker($.datepicker.regional["pt-BR"]);
                        <?php
                    }

                    if ($campo['numerico']) {
                        ?>
                        $("#<?= $campo['id']; ?>").keypress(isNumber);
                        $("#<?= $campo['id']; ?>").blur(isNumberCopy);
                        <?php
                    }

                    if ($campo['decimal']) {
                        ?>
                        $("#<?= $campo['id']; ?>").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
                        <?php
                    }

                    if ($campo['json']) {
                        ?>
                        $('#<?= $campo['json_idpai']; ?>').change(function () {
                            if ($(this).val()) {
                                $.getJSON('<?= $campo['json_url']; ?>', {
                                        <?= $campo['json_idpai']; ?> : $(this).val(),
                                        ajax : 'true'
                                    },
                                    function (json) {
                                        var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
                                        for (var i = 0; i < json.length; i++) {
                                            var selected = '';
                                            if (json[i].<?= $campo['valor']; ?> == <?= intval($linha[$campo['valor']]); ?>) {
                                                var selected = 'selected';
                                            }

                                            options += '<option value="' + json[i].<?= $campo['valor']; ?> + '" ' + selected + '>' + json[i].<?= $campo['json_campo_exibir']; ?> + '</option>';
                                        }

                                        $('#<?=$campo["id"];?>').html(options);
                                    }
                                );
                            } else {
                                $('#<?= $campo['id']; ?>').html('<option value="">– <?= $idioma[$campo['json_input_pai_vazio']]; ?> –</option>');
                            }
                            setTimeout(function() {
                                $('#<?= $campo['id']; ?>').change();
                            }, 1000);
                        });

                        $.getJSON('<?= $campo['json_url']; ?><?= $linha[$campo['json_idpai']]; ?>', function (json) {
                            var options = '<option value="">- <?= $idioma[$campo['json_input_vazio']]; ?> -</option>';
                            for (var i = 0; i < json.length; i++) {
                                var selected = '';
                                if (json[i].<?=$campo["valor"];?> == <?= intval($linha[$campo["valor"]]); ?>) {
                                    var selected = 'selected';
                                }

                                options += '<option value="' + json[i].<?= $campo['valor']; ?> + '" ' + selected + '>' + json[i].<?= $campo['json_campo_exibir']; ?> + '</option>';
                            }

                            $('#<?= $campo["id"]; ?>').html(options);
                        });
                        <?php
                    }
                }
            }
            ?>
        });

		function remover(id) {
			confirma = confirm('<?= $idioma['confirmar_remocao']; ?>');

			if (confirma) {
				document.getElementById("remover").value = id;
				document.getElementById("remover_regra").submit();
			}
		}
	</script>
</div>
</body>
</html>
