<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  
<style>
.status {
  cursor:pointer;  
  color:#FFF;
  font-size:9px;
  font-weight:bold;
  padding:5px;
  text-transform: uppercase;
  white-space: nowrap;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  margin-right:5px;
  line-height:30px;
}
.ativo {
  font-size:15px;		
}
.inativo {
  background-color:#838383;	
}

.divCentralizada {
  position: relative;
  width: 700px;
  height: 150px;
  left: 15%;
  top:50%;
}

</style>
  
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
          <? if($url[3] == "cadastrar") { ?>
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
          <? } else { ?>
            <li class="active"><?php echo $linha["nome"]; ?></li>
          <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <?php if($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
          <div class="tabbable tabs-left">
			<?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php if($url[3] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
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
				<?php if($url[3] != 'cadastrar') { ?>
					<section id="situacao_oferta">
						 <legend><?=$idioma['label_situacao_oferta'];?></legend>
						 <div id="divSituacoes" style="padding-top:15px; padding-bottom:15px; width:90%">
						  <? foreach($situacaoWorkflow as $ind => $val){ ?>
							  <span id="<?=$ind;?>" <? ($ind == $linha['idsituacao']) ? print 'class="status ativo" style="background-color: #'.$val["cor_bg"].'"' : print 'class="status inativo"'; ?>
							  <? if (in_array($ind, $situacaoWorkflowRelacionamento) && $linha["situacao"]["visualizacoes"][1]) { ?>onclick="modificarSituacao('<?=$ind;?>','<?=$val["nome"];?>');"<? } else { ?>data-original-title="<?=$idioma['indisponivel']; ?>" style="background-color:#CCC" rel="tooltip"<? } ?>>
							  <?=$val["nome"];?>
							  </span>
						  <? } ?>
						 </div>                
						 <?php if($linha["situacao"]["visualizacoes"][1]) { ?>    
						<script>
							function modificarSituacao(para,nome){
									var de = "<?= $linha["idsituacao"]; ?>";
									var msg = "<?=$idioma['confirma_altera_situacao_oferta'];?>";
									msg = msg.replace("[[idoferta]]", "<?=$url[3];?>");
									msg = msg.replace("[[nome]]", nome);
									var confirma = confirm(msg);
									if(confirma){
										document.getElementById('situacao_para').value = para;
										document.getElementById('form_situacao').submit();
									} else {
										return false;
									}
							}
						</script>
						<form method="post" action="#situacao" id="form_situacao">
							<input name="acao" type="hidden" value="alterarSituacao" />  
							<input name="situacao_para" id="situacao_para" type="hidden" value="" />    
						</form>     
						<?php } ?>             
					</section>
				<?php } ?>
				
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar" />
				  <? if($url[4] == "editar") {
					echo '<input type="hidden" name="'.$config["banco"]["primaria"].'" id="'.$config["banco"]["primaria"].'" value="'.$linha[$config["banco"]["primaria"]].'" />';
					foreach($config["banco"]["campos_unicos"] as $campoid => $campo) {
					?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
					<? 
					}					  
					$linhaObj->GerarFormulario("formulario",$linha,$idioma);				
				  } else {
					  $linhaObj->GerarFormulario("formulario",$_POST,$idioma);
				  }
				  ?>
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
      <?php/*<div class="span3">
        <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
          <div class="well">
            <?= $idioma["nav_cadastrar_explica"]; ?>
            <br />
            <br />
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar" class="btn primary"><?= $idioma["nav_cadastrar"]; ?></a>
          </div>
        <? } ?>
        <?php incluirLib("sidebar_".$url[1],$config); ?>      
      </div>*/?>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript">
      var regras = new Array();
      <?php
      foreach($config["formulario"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
              if($campo["tipo"] == "file"){
              ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? } else { ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? }
            }
          }
        }
      }
      ?>
      jQuery(function($){		
        <? foreach($config["formulario"] as $fieldsetid => $fieldset) {
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
            if($campo["datepicker"]) { ?>
              $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
            <? 
            }
            if($campo["numerico"]) {
            ?>
              $("#<?= $campo["id"]; ?>").keypress(isNumber);
              $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
            <? 
            }
            if($campo["decimal"]) {
            ?>
              $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
            <?
            }
          }
        } ?>			
      });
    </script>
  </div>
</body>
</html>