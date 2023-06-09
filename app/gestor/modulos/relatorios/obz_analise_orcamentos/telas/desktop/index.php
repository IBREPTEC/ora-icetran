<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php incluirLib('head',$config,$usuario); ?>
	<style type="text/css">
		body {
			padding-top: 40px;
			background-image:none;
		}

		h2 {
			font-size:30px;
			text-transform:uppercase;
			line-height:110%;
			margin:25px;
			color:#666;
		}

		body,td,th {
			font-size: 12px;
			color: #666666;
		}

		a:link {
			color: #000000;
		}

		a:visited {
			color: #000000;
		}

		a:active {
			color: #000;
		}

		p {
			margin-left:25px;
		}

		.breadcrumb {
			font-size:10px;
		}

		a:hover {
			color: #000000;
		}

		.help-block {
			margin-left:0px !important;
		}
	</style>
	<script>
		function valida(form,regras) {
			if (!validateFields(form, regras)) {
				return false;
			} else {
				fechaLoading();
				return true;
			}
		}
	</script>
</head>
<body>
	<div class="container">
		<div style="margin-bottom:25px">
			<a href="/<?= $url[0]; ?>" class="logo"></a>
		</div>
		<div class="row">
			<ul class="breadcrumb">
				<li><?= $idioma['nav_inicio']; ?><span class="divider">/</span></li>
				<li><?= $idioma['nav_relatorios']; ?> <span class="divider">/</span></li>
				<li class="active"><?= $idioma['pagina_titulo']; ?></li>
				<span class="pull-right" style="color:#999"><?= $idioma['hora_servidor']; ?> <?= date('d/m/Y H\hi'); ?></span>
			</ul>
			<h2><?= $idioma['pagina_titulo']; ?></h2>
			<p><?= $idioma['selecione']; ?></p>
			<p>
				<form method="get" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html" id="formRelatorio" class="form-horizontal" target="_blank" onsubmit="return valida(this, regras)">
					<div class="span12">
						<?php $relatorioObj->GerarFormulario("formulario",$linha,$idioma); ?>
					</div>
					<div class="span12">
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="<?= $idioma["btn_gerar_html"]; ?>" onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html'">&nbsp;
							<input type="submit" class="btn btn-primary" value="<?= $idioma["btn_gerar_xlsx"]; ?>" onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls'">&nbsp;
							<a href="/gestor/relatorios" class="btn dropdown-toggle"> <?= $idioma['btn_cancelar']; ?> </a>
						</div>
					</div>
				</form>
			</p>
		</div>
	</div>
	<?php incluirLib('rodape',$config,$usuario); ?>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
	<script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
	<link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">
		var regras = new Array();
		<?php
		foreach ($config['formulario'] as $fieldsetid => $fieldset) {
			foreach ($fieldset['campos'] as $campoid => $campo) {
				if (is_array($campo['validacao'])){
					foreach ($campo['validacao'] as $tipo => $mensagem) {
						if ($campo['id'] != 'form_idpais') {
							if ($campo['tipo'] == 'file') {
								?>
								regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
								<?php
							} else {
								?>
								regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
								<?php
							}
						} else {
							?>
							regras.push("<?php echo $tipo; ?>,form_idpais3,<?php echo $idioma[$mensagem]; ?>");
							<?php
						}
					}
				}
			}
		}
		?>
		jQuery(document).ready(function($) {
			<?php
			foreach ($config['formulario'] as $fieldsetid => $fieldset) {
				foreach ($fieldset['campos'] as $campoid => $campo) {
					if ($campo['mascara']) {
						if ($campo['mascara'] == '99/99/9999') {
							?>
							$("#<?= $campo['id']; ?>").mask("<?= $campo['mascara']; ?>");
							$("#<?= $campo['id']; ?>").change(function() {
								if($('#<?= $campo['id']; ?>').val() != '') {
									valordata = $("#<?= $campo['id']; ?>").val();
									date= valordata;
									ardt= new Array;
									ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
									ardt=date.split("/");
									erro=false;
									if ( date.search(ExpReg)==-1){
										erro = true;
									}
									else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
										erro = true;
									else if ( ardt[1]==2) {
										if ((ardt[0]>28)&&((ardt[2]%4)!=0))
											erro = true;
										if ((ardt[0]>29)&&((ardt[2]%4)==0))
											erro = true;
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
						} elseif ($campo['mascara'] == '(99) 9999-9999' || $campo['mascara'] == '(99) 9999-9999?9') {
							?>
							$('#<?= $campo['id']; ?>').focusout(function(){
								var phone, element;
								element = $(this);
								element.unmask();
								phone = element.val().replace(/\D/g, '');
								if(phone.length > 10) {
									element.mask("(99) 99999-999?9");
								} else {
									element.mask("(99) 9999-9999?9");
								}
							}).trigger('focusout');
							<?php
						} else {
							?>
							$("#<?= $campo['id']; ?>").mask("<?= $campo['mascara']; ?>");
							<?php
						}
					}

					if ($campo['datepicker']){
						?>
						$( "#<?= $campo['id']; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
						<?php
					}

					if ($campo['numerico']){
						?>
						$("#<?= $campo['id']; ?>").keypress(isNumber);
						$("#<?= $campo['id']; ?>").blur(isNumberCopy);
						<?php
					}

					if ($campo['decimal']){
						?>
						$("#<?= $campo['id']; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});
						<?php
					}

					if ($campo['json']) {
						if ($campo['tipo'] == 'select') {
							?>
							$('#<?=$campo['json_idpai'];?>').change(function(){
								if($(this).val()){
									$.getJSON('<?= $campo['json_url']; ?>',{<?= $campo['json_idpai']; ?>: $(this).val(), ajax: 'true'}, function(json){
										var options = '<option value="">– <?= $idioma[$campo['json_input_vazio']]; ?> –</option>';
										for (var i = 0; i < json.length; i++) {
											var selected = '';
											if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
											var selected = 'selected';
											options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
										}
										$('#<?=$campo["id"];?>').html(options);
									});
								} else {
									$('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
								}
							});

							if('<?= $linha[$campo['json_idpai']]; ?>'){
								$.getJSON('<?=$campo['json_url'];?><?= $linha[$campo['json_idpai']]; ?>', function(json) {
									var options = '<option value="">- <?=$idioma[$campo['json_input_vazio']]; ?> -</option>';
									for (var i = 0; i < json.length; i++) {
										var selected = '';
										if(json[i].<?=$campo['valor'];?> == <?= intval($linha[$campo['valor']]); ?>)
										var selected = 'selected';
										options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
									}
									$('#<?=$campo['id'];?>').html(options);
								});
							} else {
									$('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
								}
							<?php
						} elseif ($campo['tipo'] == 'checkbox') {
							?>
							$('#<?=$campo['json_idpai'];?>').change(function(){
								if($(this).val()){
									$.getJSON('<?= $campo['json_url']; ?>',{<?= $campo['json_idpai']; ?>: $(this).val(), ajax: 'true'}, function(json){
										var texto = '';
										var style = 'style="display:none;"';
										if (json.length == 0) {
											texto = '<?= $idioma['informacao_nao_encontrada']; ?>';
											style = '';
										}

										var options = '<label class="checkbox" ' + style + '>' +
															'<input type="checkbox" name="<?= $campo['nome']; ?>[]" id="<?= $campo['id']; ?>">' + texto +
													  	'</label>';
										for (var i = 0; i < json.length; i++) {
											var checked = '';
											if(json[i].<?=$campo["valor"];?> == <?= intval($linha[$campo["valor"]]); ?>) {
												var checked = 'checked';
											}

											options += '<label class="checkbox">' +
															'<input type="checkbox" value="' + json[i].<?=$campo['valor'];?> + '" name="<?= $campo['nome']; ?>[]" id="<?= $campo['id']; ?>" '+ checked +'>' + json[i].<?= $campo["json_campo_exibir"]; ?> +
													  	'</label>';
										}
										$("input:checkbox[name='<?= $campo['nome']; ?>[]']").parent().parent().html(options);
									});
								} else {
									$("input:checkbox[name='<?= $campo['nome']; ?>[]']").parent().parent().html('<label class="checkbox">' +
																												'<input type="checkbox" value="" name="<?= $campo['nome']; ?>[]" disabled><?= $idioma[$campo["json_input_pai_vazio"]]; ?>' +
																											'</label>');
								}
							});

							if('<?= $linha[$campo['json_idpai']]; ?>'){
								$.getJSON('<?=$campo['json_url'];?><?= $linha[$campo['json_idpai']]; ?>', function(json) {
									var texto = '';
									var style = 'style="display:none;"';
									if (json.length == 0) {
										texto = '<?= $idioma['informacao_nao_encontrada']; ?>';
										style = '';
									}

									var options = '<label class="checkbox" ' + style + '>' +
														'<input type="checkbox" name="<?= $campo['nome']; ?>[]" id="<?= $campo['id']; ?>">' + texto +
												  	'</label>';
									for (var i = 0; i < json.length; i++) {
										var checked = '';
										if(json[i].<?=$campo['valor'];?> == <?= intval($linha[$campo['valor']]); ?>) {
											var checked = 'checked';
										}

										options += '<label class="checkbox">' +
															'<input type="checkbox" value="' + json[i].<?=$campo['valor'];?> + '" name="<?= $campo['nome']; ?>[]" id="<?= $campo['id']; ?>" '+ checked +'>' + json[i].<?=$campo["json_campo_exibir"];?> +
													  	'</label>';
									}
									$("input:checkbox[name='<?= $campo['nome']; ?>[]']").parent().parent().html(options);
								});
							} else {
								$("input:checkbox[name='<?= $campo['nome']; ?>[]']").parent().parent().html('<label class="checkbox">' +
																												'<input type="checkbox" value="" name="<?= $campo['nome']; ?>[]" disabled><?= $idioma[$campo["json_input_pai_vazio"]]; ?>' +
																											'</label>');
							}
							<?php
						}
					}

					//ALTERAÇÃO PARA OS RELATÓRIOS COM DE E ATE - INÍCIO
					if ($campo['botao_hide']) {
						if ($campo['tipo'] == 'select') {
							if ($campo['id'] == 'form_tipo_data_filtro') {
								?>
								$('#div_form_<?= $campo['iddiv']; ?>').show();
								$('#div_form_<?= $campo['iddiv2']; ?>').show();
								$('#<?= $campo["id"]; ?> option[value="PER"]').attr('selected','selected');

								$('#<?= $campo['id']; ?>').change(function() {
									var remover_1 = 0;
									var remover_2 = 0;
									aux_d = $('#<?= $campo["id"]; ?>').attr('value');
									div1_obr = '<?= $campo["iddiv_obr"]; ?>';
									div2_obr = '<?= $campo["iddiv2_obr"]; ?>';

									if (aux_d == 'PER'){
										$('#div_form_<?= $campo['iddiv']; ?>').show("fast");
										$('#div_form_<?= $campo['iddiv2']; ?>').show("fast");

										if (div1_obr) {
											regras.push("required,form_<?= $campo['iddiv']; ?>,<?= $idioma[$campo['iddiv'].'_vazio'] ?>");
										}

										if (div2_obr) {
											regras.push("required,form_<?= $campo['iddiv2']; ?>,<?= $idioma[$campo['iddiv2'].'_vazio'] ?>");
										}
									} else {
										$('#div_form_<?= $campo["iddiv2"]; ?>').hide("fast");
										$('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
										$('#form_<?= $campo["iddiv"]; ?>').attr("value","");
										$('#form_<?= $campo["iddiv2"]; ?>').attr("value","");

										for (var i = 0; i < regras.length; i++) {
											if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma[$campo["iddiv"]."_vazio"] ?>') {
												remover_1 = i;
											}
										}

										if (remover_1 != 0) {
											regras.splice(remover_1, 1);
										}

										for (var i = 0; i < regras.length; i++) {
											if (regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma[$campo["iddiv2"]."_vazio"] ?>') {
												remover_2 = i;
											}
										}

										if (remover_2 != 0) {
											regras.splice(remover_2, 1);
										}
									}
								});
								<?php
							}
						}
					}
				}
			}
			?>
			$("#form_ate").on("change", function(){
                var de = $("#form_de").val();
                var ano1 = de.split("/");
                var ate = $("#form_ate").val();
                var ano2 = ate.split("/");
                if((de && ate) && ano1[2] != ano2[2]){
                    alert("As datas devem ser do mesmo ano.");
                    $("#form_ate").val("");
                    }
                });
            $("#form_de").on("change", function(){
                var de = $("#form_de").val();
                var ano1 = de.split("/");
                var ate = $("#form_ate").val();
                var ano2 = ate.split("/");
                if((de && ate) && ano1[2] != ano2[2]){
                    alert("As datas devem ser do mesmo ano.");
                    $("#form_de").val("");
                    }
                });
		});
	</script>
</body>
</html>
