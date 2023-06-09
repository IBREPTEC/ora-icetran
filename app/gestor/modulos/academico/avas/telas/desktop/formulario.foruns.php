<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><? echo $linha["nome"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/foruns"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <? if($url[5] == "cadastrar") { ?>
          <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
          <li class="active"><?php echo $forum["nome"]; ?></li>
        <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_submenu",$config,$forum); ?>
            <div class="ava-conteudo"> 
              <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
              <?php if($url[5] != "cadastrar") { ?>
                <h2 class="tituloEdicao"><?php echo $forum["nome"]; ?></h2>
                <?php include("inc_submenu_foruns.php"); ?>
              <?php } ?>
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php if($url[5] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
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
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar_forum" />
                  <? if($url[6] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco_foruns"]["primaria"].'" id="'.$config["banco_foruns"]["primaria"].'" value="'.$forum[$config["banco_foruns"]["primaria"]].'" />';
                    foreach($config["banco_foruns"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $forum[$campo["campo_banco"]]; ?>" />
                    <? 
                    }					  
                    $linhaObj->GerarFormulario("formulario_foruns",$forum,$idioma);				
                  } else {
                    $linhaObj->GerarFormulario("formulario_foruns",$_POST,$idioma);
                  }
                  ?>
                  <fieldset>
                    <?php foreach($tipoPessoas as $indTipoPessoa => $tipoPessoa) { 
					  $permissao = "";
					  ?>
                    <div class="span6" id="<?= $indTipoPessoa; ?>">
                      <div class="control-group">
                        <legend><?php echo $idioma["form_permissoes_de"]; ?><?= $tipoPessoa["nome"]; ?></legend>
                        <div class="controls">
                          <label class="checkbox">
                            <input name="marcar_todas_<?= $indTipoPessoa; ?>" id="marcar_todas_<?= $indTipoPessoa; ?>" onclick="selecionaTodos('<?= $indTipoPessoa; ?>')" value="" type="checkbox">
                            <em><?= $idioma["marcar_todas"]; ?></em>
                          </label>
                        </div>
						<? foreach($tipoPessoa["funcionalidades"] as $indFuncionalidade => $funcionalidade) { 
						$permissao = $indTipoPessoa."|".$indFuncionalidade
						?>
                          <label class="control-label" for="<?= $indFuncionalidade; ?>"><strong><?= $funcionalidade["nome"]; ?></strong></label>
                          <div class="controls" id="<?= $permissao; ?>">
                            <?php if (count($funcionalidade["acoes"]) > 1) { ?>
                              <label class="checkbox">
                                <input name="marcar_todas_<?= $permissao; ?>" id="marcar_todas_<?= $permissao; ?>" onclick="selecionaTodos('<?= $permissao; ?>')" value="" type="checkbox">
                                <em><?= $idioma["marcar_todas"]; ?></em>
                              </label>
                            <?php } ?>
                            <? 
                            foreach($funcionalidade["acoes"] as $indAcao => $acao) { 
                              $permissao = $indTipoPessoa."|".$indFuncionalidade."|".$indAcao;
                              ?>
                              <label class="checkbox">
                                <input name="permissoes[<?= $permissao; ?>]" value="1" type="checkbox" <? if($forum["permissoes"][$permissao]) { echo ' checked="checked"'; } ?>>
                                <?php if(empty($acao)) echo "<spam style='color:#FF0000'>Erro: Sem idioma.</span>"; else echo $acao; ?>
                              </label>
                            <? } ?>
                          </div>
						<? } ?>
                      </div>
                    </div>
					<?php } ?>
                  </fieldset>
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
	<? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/js/ajax.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript">
      var regras = new Array();
      <?php
      foreach($config["formulario_foruns"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
			  if($campo["tipo"] == "file"){ ?>
				regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			  <? } else { ?>
				regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			  <?
			  }              
            }
          }
        }
      }
      ?>
      jQuery(document).ready(function($) { 
        <?
        foreach($config["formulario_foruns"] as $fieldsetid => $fieldset) {
          foreach($fieldset["campos"] as $campoid => $campo) {
            if($campo["mascara"]){ ?>
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
								alert("\"" + valordata + "\" não é uma data válida!!!");
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
            if($campo["datepicker"]){ ?>
              $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
            <?
            }
            if($campo["numerico"]){ ?>
              $("#<?= $campo["id"]; ?>").keypress(isNumber);
              $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
            <?
            }
            if($campo["decimal"]){ ?>
              $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
            <?
            }									
          }
        }
        ?>
      });
	  function deletaArquivo(div, obj) {
		if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
		  solicita(div, obj);		
		}
	  }
	  function selecionaTodos(id) {
        var div = document.getElementById(id);
        var inputs = div.getElementsByTagName("input");
        var todos = document.getElementById("marcar_todas_"+id).checked;
        var marcar = false;
        if(todos) {
          marcar = true;
        }
        for (i = 0; i < inputs.length; i++) {
          inputs[i].checked = marcar;
        }
      }
    </script>
  </div>
</body>
</html>