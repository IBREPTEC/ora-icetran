<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="icon" href="/assets/img/favicon.ico">
	<style type="text/css">
body {
	padding-top: 40px;
	background-image: none;
}

h2 {
	font-size: 30px;
	text-transform: uppercase;
	line-height: 110%;
	margin: 25px;
	color: #666;
}

body, td, th {
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
	margin-left: 25px;
}

.breadcrumb {
	font-size: 10px;
}

a:hover {
	color: #000000;
}
</style>

</head>
<body>
	<div class="container">
		<div style="margin-bottom: 25px">
			<a href="/<?= $url[0]; ?>" class="logo"><?php /*<img src="<?php echo $config['logo_pequena']; ?>" width="135" height="50" />*/?></a>
		</div>
		<div class="row">
			<ul class="breadcrumb">
				<li><?= $idioma["nav_inicio"]; ?><span class="divider">/</span></li>
				<li><?= $idioma["nav_relatorios"]; ?> <span class="divider">/</span></li>
				<li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a
					href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
    	<span class="pull-right" style="color: #999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
			</ul>
			<h2><?= $idioma["pagina_titulo"]; ?></h2>
			<!--<p>Para gerar esse relatÃ³rio vÃ¡ atÃ© a listagem de fechamento de caixa e clique no botÃ£o "VISUALIZAR" do fechamento de caixa que deseja visualizar.</p> -->
			<!--<a class="btn btn-primary" href="/gestor/financeiro/fechamento_caixa" style="color:#FFF;padding:18px;font-size:14px;"><strong>Ir para a listagem de fechamento de caixa</strong></a>
      	-->

			<p>
				<form method="get"
					action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html"
					id="formRelatorio" class="form-horizontal" target="_blank">    
	  	<? $relatorioObj->GerarFormulario("formulario",$linha,$idioma); ?>
        			<div class="form-actions">
						<input type="submit" id="btn-salvar" class="btn btn-primary"
							value="<?= $idioma["btn_gerar_html"]; ?>"
							onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html'">
							&nbsp; <input type="submit" id="btn-salvar-xls"
							class="btn btn-primary" value="<?= $idioma["btn_gerar_xlsx"]; ?>"
							onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls'">
								&nbsp; <a href="/gestor/relatorios" class="btn dropdown-toggle"> <?= $idioma["btn_cancelar"]; ?> </a>
					
					</div>
					</fieldset>
				</form>
			</p>
		</div>
	</div>
<?php incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
	<script
		src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
	<script
		src="/assets/plugins/password_force/password_strength_plugin.js"></script>
	<link rel="stylesheet" href="/assets/plugins/password_force/style.css"
		type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">
  var regras = new Array();
  <?php
		foreach ( $config ["formulario"] as $fieldsetid => $fieldset ) {
			foreach ( $fieldset ["campos"] as $campoid => $campo ) {
				if (is_array ( $campo ["validacao"] )) {
					foreach ( $campo ["validacao"] as $tipo => $mensagem ) {
						if ($campo ["id"] != "form_idpais") {
							if ($campo ["tipo"] == "file") {
								?>
			  regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			<? } else { ?>
			  regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			<?
							}
						} else {
							?>
			regras.push("<?php echo $tipo; ?>,form_idpais3,<?php echo $idioma[$mensagem]; ?>");
			<?
						}
					}
				}
			}
		}
		?>
  jQuery(document).ready(function($) { 
	<?
	foreach ( $config ["formulario"] as $fieldsetid => $fieldset ) {
		foreach ( $fieldset ["campos"] as $campoid => $campo ) {
			if ($campo ["mascara"]) {
				?>
				<?php if($campo["mascara"] == "99/99/9999") { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
					$('#<?= $campo["id"]; ?>').change(function() {
						if($('#<?= $campo["id"]; ?>').val() != '') {
							valordata = $("#<?= $campo["id"]; ?>").val();
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
								alert("\"" + valordata + "\" nÃ£o Ã© uma data vÃ¡lida!!!");
								$('#<?= $campo["id"]; ?>').focus();
								$("#<?= $campo["id"]; ?>").val('');
								return false;
							}
							return true;
						}
					});
				<?php } elseif($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
					$('#<?= $campo["id"]; ?>').focusout(function(){
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
				<?php } else { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
				<?php } ?>
            <?
			}
			if ($campo ["datepicker"]) {
				?>
		  $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
		  <?
			}
			if ($campo ["numerico"]) {
				?>
		  $("#<?= $campo["id"]; ?>").keypress(isNumber);
		  $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
		<?
			}
			if ($campo ["decimal"]) {
				?>
		  $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
		<?
			}
			if ($campo ["json"]) {
				?>
		  $('#<?=$campo["json_idpai"];?>').change(function(){
			if($(this).val()){
			  $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
				var options = '<option value=""><?=$idioma[$campo["json_input_vazio"]]; ?></option>';
				for (var i = 0; i < json.length; i++) {
				  var selected = '';
				  if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
					var selected = 'selected';
				  options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
				}	
				$('#<?=$campo["id"];?>').html(options);
			  });
			} else {
			  $('#<?=$campo["id"];?>').html('<option value=""><?=$idioma[$campo["json_input_pai_vazio"]]; ?></option>');
			}
		  });
				  
		  $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', function(json){
			var options = '<option value=""><?=$idioma[$campo["json_input_vazio"]]; ?></option>';	
			for (var i = 0; i < json.length; i++) {
			  var selected = '';
			  if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
				var selected = 'selected';
			  options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
			}
			$('#<?=$campo["id"];?>').html(options);
		  });
		  <?
			}
			
			// ALTERAÃ‡ÃƒO PARA OS RELATÃ“RIOS COM DE E ATE - INÃ�CIO
			if ($campo ["botao_hide"]) {
				
				if ($campo ['tipo'] == 'select') {
					?>			
			  
			  <?php if ($campo["id"] == 'form_forma_pagamento') { ?>
				$('#<?= $campo["id"]; ?>').change(function() {
					aux_d = $('#<?= $campo["id"]; ?>').attr('value');
					if (aux_d == 2 || aux_d == 3){
					  $('#div_form_<?= $campo["iddiv"]; ?>').show("fast");					
					} else {
					  $('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
					  $('#form_<?= $campo["iddiv"]; ?>').attr("value","");
					}
				});
			  <?php } else { ?>
				
				$('#div_form_<?= $campo["iddiv"]; ?>').show();
				$('#div_form_<?= $campo["iddiv2"]; ?>').show();
				$('#<?= $campo["id"]; ?> option[value="PER"]').attr('selected','selected');
				
				$('#<?= $campo["id"]; ?>').change(function() {
					var remover_1 = 0;
					var remover_2 = 0;
					aux_d = $('#<?= $campo["id"]; ?>').attr('value');
					div1_obr = '<?= $campo["iddiv_obr"]; ?>';
					div2_obr = '<?= $campo["iddiv2_obr"]; ?>';

					if (aux_d == 'PER'){
						$('#div_form_<?= $campo["iddiv"]; ?>').show("fast");
						$('#div_form_<?= $campo["iddiv2"]; ?>').show("fast");
														
						if (div1_obr)
							regras.push("required,form_<?= $campo["iddiv"]; ?>,<?= $idioma[$campo["iddiv"]."_vazio"] ?>"); 
						if (div2_obr)
							regras.push("required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma[$campo["iddiv2"]."_vazio"] ?>"); 
													
					} else {
						$('#div_form_<?= $campo["iddiv2"]; ?>').hide("fast");
						$('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
						$('#form_<?= $campo["iddiv"]; ?>').attr("value","");
						$('#form_<?= $campo["iddiv2"]; ?>').attr("value","");
						for (var i = 0; i < regras.length; i++){ 
							if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma[$campo["iddiv"]."_vazio"] ?>')
							   remover_1 = i;
						}
						if (remover_1 != 0)
							regras.splice(remover_1, 1);
						for (var i = 0; i < regras.length; i++){ 
							if (regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma[$campo["iddiv2"]."_vazio"] ?>')
								remover_2 = i;
						}
						if (remover_2 != 0)
							regras.splice(remover_2, 1);
					}
				}				
				);			
<?
					}
				} // TIPO
			}
			// ALTERAÃ‡ÃƒO PARA OS RELATÃ“RIOS COM DE E ATE - FIM
		}
	}
	?>
  });

  function selecionaTodos() {
	var div = document.getElementById('div_colunas');
	var inputs = div.getElementsByTagName('input');
	var todos = document.getElementById('marcar_todas').checked;
	var marcar = false;
	if(todos) {
	  marcar = true;
	}
	for (i = 0; i < inputs.length; i++) {
	  inputs[i].checked = marcar;
	}
  }
</script>


<script type="text/javascript">
	$(document).ready(function($) { 
			// Verificando se as datas foram prenchidas				
		   	$("#btn-salvar").on("click", function(){		   		
		   		// Intervalo de datas de pagamento
		   		var pagamentoFiltro = $("#form_tipo_data_pagamento_filtro").val();
		        var dePagamento = $("#form_de_data_pagamento").val();		        			       
		        var atePagamento = $("#form_ate_data_pagamento").val();				        
		        // Intervalo de datas de recebimento	
		        var vencimentoFiltro = $("#form_tipo_data_vencimento_filtro").val()
		        var deVencimento = $("#form_de_data_vencimento").val();
		        var ateVencimento = $("#form_ate_data_vencimento").val();				        		        	        		        

		        if( pagamentoFiltro == "" && vencimentoFiltro == "") {
		        	alert("Por favor informe uma data de pagamento ou vencimento");
		        	return false;
		        } 

		        if( pagamentoFiltro == "PER" || vencimentoFiltro == "PER"){
		        	if( (dePagamento == "" || atePagamento == "" ) && ((deVencimento == "" || ateVencimento == "")) ){
		        		alert("Por favor informe uma data de pagamento ou vencimento");
		        		return false;
		        	}	       	       			        		       	       				        	
		        }		   
		    });		   

			$("#btn-salvar-xls").on("click", function(){		   		
				// Intervalo de datas de pagamento
				var pagamentoFiltro = $("#form_tipo_data_pagamento_filtro").val();
			    var dePagamento = $("#form_de_data_pagamento").val();		        			       
			    var atePagamento = $("#form_ate_data_pagamento").val();				        
				// Intervalo de datas de venciemento
				var vencimentoFiltro = $("#form_tipo_data_vencimento_filtro").val()
				var deVencimento = $("#form_de_data_vencimento").val();
				var ateVencimento = $("#form_ate_data_vencimento").val();				        		        	        		        

		        if( pagamentoFiltro == "" && vencimentoFiltro == "") {
		        	alert("Por favor informe uma data de pagamento ou vencimento");
		        	return false;
		        } 

		        if( pagamentoFiltro == "PER" || vencimentoFiltro == "PER"){
		        	if( (dePagamento == "" || atePagamento == "" ) && ((deVencimento == "" || ateVencimento == "")) ){
		        		alert("Por favor informe uma data de pagamento ou vencimento");
		        		return false;
		        	}	       	       			        		       	       				        	
		        }		   
		    });		   
		});
	</script>

</body>
</html>