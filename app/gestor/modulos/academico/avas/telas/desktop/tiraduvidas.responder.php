<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
<script>
function valida_form(form, ru) {
	CKEDITOR.instances.form_resposta.updateElement();
	if (validateFields(form, ru))
		return true;
	return false;
}
</script>
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? if($url[5] == "cadastrar") { echo $linha["nome"]; } else { echo $linha["ava"]; } ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/tiraduvidas"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
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
                <?php include("inc_submenu_tiraduvidas.php"); ?>
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php  echo $idioma["titulo_opcao_editar"]; ?></h2>
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
                  <legend><?= $idioma['form_pergunta'];?></legend>
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover">
                    <tr>
                      <td>
                        <small><strong># <?= $linha["idduvida"] ?></strong></small>
                        <br />
                        <small>
                        	<strong><?= $idioma["data"]; ?> </strong><?= formataData($linha['data_cad'],"br",1); ?> <strong><?= $idioma["por"]; ?></strong>
                            <?php if ($linha['usu_adm']){ ?>
								<?php echo $linha['usu_adm']; ?>
							<? }elseif ($linha['professor']){?>
								<?php echo $linha['usu_adm']; ?>
							<? }elseif($linha['aluno']) {?>
                            	<?php echo $linha['usu_adm']; ?>
                            <? } ?>
                         	<?php echo $linha["aluno"]; ?>
                        </small>
                        <span style="color:#999;">
                            <?php if ($linha['autoriza_exibir'] == 'S'){?>
                              <small><?= $idioma['form_sim'];?></small>
                            <? } else{ ?>
                              <small><?= $idioma['form_nao'];?></small>
                            <? } ?> 
                        </span>
                        <br />
                        <br />
                        <strong><?= $idioma["form_desc_pergunta"]?></strong><br />
                        <?php echo nl2br($linha["pergunta"]); ?>	
                      </td>
                    </tr>  
                  </table>
              <form method="post" onsubmit="return valida_form(this, regras)" enctype="multipart/form-data" class="form-horizontal">
              <input name="acao" type="hidden" value="salvar_resposta" />
                  <? if($url[6] == "responder") {
                    echo '<input type="hidden" name="'.$config["banco_tira_duvidas"]["primaria"].'" id="'.$config["banco_tira_duvidas"]["primaria"].'" value="'.$linha[$config["banco_tira_duvidas"]["primaria"]].'" />';
					$linhaObj->GerarFormulario("formulario_responder_duvida",$linha,$idioma);				
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
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/ckeditor/sample.js"></script>
	<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
	<script src="/assets/js/ajax.js"></script>
      <script type="text/javascript">
	  	var regras = new Array();
		regras.push("required,form_resposta,<?php echo $idioma["resposta_vazio"]; ?>");
      <?php
      foreach($config["formulario_responder_duvida"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
			  if($campo["tipo"] == "file"){ ?>
				regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			  <? } elseif($campo["tipo"] != "text") { ?>
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
        foreach($config["formulario_responder_duvida"] as $fieldsetid => $fieldset) {
          foreach($fieldset["campos"] as $campoid => $campo) {
			if($campo["editor"]){	
			?>	
			  var editor = CKEDITOR.replace( '<?= $campo["nome"]; ?>', {
				extraPlugins: 'htmlwriter',
				height: 290,
				width: '80%',
				toolbar: [
				  ["Bold","Italic","Underline","StrikeThrough","-","Outdent","Indent","NumberedList","BulletedList"],
				  ["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
				  ["Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],["Image"],
				],
				contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',
				docType: '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',
				coreStyles_bold: { element: 'b' },
				coreStyles_italic: { element: 'i' },
				coreStyles_underline: { element: 'u' },
				font_style: {
				  element: 'font',
				  attributes: { 'face': '#(family)' }
				},
				fontSize_sizes: '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28;36px/36;48px/48;72px/72',
				fontSize_style: {
				  element: 'font',
				  attributes: { 'size': '#(size)' },
				  styles: { 'font-size': '#(size)px' }
				},
				on: { 'instanceReady': configureFlashOutput }
			  });

			  function configureFlashOutput( ev ) {
				var editor = ev.editor,
				dataProcessor = editor.dataProcessor,
				htmlFilter = dataProcessor && dataProcessor.htmlFilter;

				dataProcessor.writer.selfClosingEnd = '>';
				
				var dtd = CKEDITOR.dtd;
				for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
				  dataProcessor.writer.setRules( e, {
					indent: false,
					breakBeforeOpen: false,
					breakAfterOpen: false,
					breakBeforeClose: false,
					breakAfterClose: false
				  });
				}
				dataProcessor.writer.setRules( 'br', {
				  indent: false,
				  breakBeforeOpen: false,
				  breakAfterOpen: false,
				  breakBeforeClose: false,
				  breakAfterClose: false
				});

				// Output properties as attributes, not styles.
				htmlFilter.addRules( {
				  elements: {
					$: function( element ) {
					  var style, match, width, height, align;
	
					  // Output dimensions of images as width and height
					  if ( element.name == 'img' ) {
						style = element.attributes.style;
	
						if ( style ) {
						  // Get the width from the style.
						  match = ( /(?:^|\s)width\s*:\s*(\d+)px/i ).exec( style );
						  width = match && match[1];
	
						  // Get the height from the style.
						  match = ( /(?:^|\s)height\s*:\s*(\d+)px/i ).exec( style );
						  height = match && match[1];
	
						  if ( width ) {
							element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
							element.attributes.width = width;
						  }
	
						  if ( height ) {
							element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
							element.attributes.height = height;
						  }
						}
					  }
	
					  // Output alignment of paragraphs using align
					  if ( element.name == 'p' ) {
						style = element.attributes.style;
	
						if ( style ) {
						  // Get the align from the style.
						  match = ( /(?:^|\s)text-align\s*:\s*(\w*);?/i ).exec( style );
						  align = match && match[1];
	
						  if ( align ) {
							element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
							element.attributes.align = align;
						  }
						}
					  }
	
					  if ( element.attributes.style === '' )
						delete element.attributes.style;
					  return element;
					}
				  }
				});
			  }
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
	</script>
  </div>
</body>
</html>