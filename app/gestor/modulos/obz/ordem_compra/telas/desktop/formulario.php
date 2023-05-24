<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/css/etapas.css" media="all" type="text/css"/>
<style>.hidden{margin-top:-75px;}</style>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">

<script>
function valida_form(form, ru) {
	CKEDITOR.instances.form_descricao.updateElement();
	if (validateFields(form, ru))
		return true;
	return false;
}
</script>

</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
      <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["nav_formulario"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
      <div class="box-conteudo">
      <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
		<?php if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in"> 
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
            </div>
        <?php } ?>
        <?php if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in"><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a><strong><?= $idioma[$_POST["msg"]]; ?></strong></div>
        <?php } ?>
        <?php if(count($salvar["erros"]) > 0){ ?>
            <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma["form_erros"]; ?></strong>
                <?php foreach($salvar["erros"] as $ind => $val) { ?>
                    <br />
                    <?php echo $idioma[$val]; ?>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="control-group">
          <form method="post" onsubmit="return valida_form(this, regras)" enctype="multipart/form-data" class="form-horizontal">
            <input name="acao" type="hidden" value="salvar" />
			<fieldset>
            	<legend><?=$idioma["titulo_formulario_ordem"]?></legend>
                <div class="control-group">
                    <label for="idassunto" class="control-label"><strong><?php echo $idioma["form_instituicao"]; ?></strong></label>
                    <div class="controls">
                        <select class="span4" id="idsindicato" name="idsindicato">
                            <option value="">- <?=$idioma["form_selecione_instituicao"]?> -</option>
                            <?php foreach($instituicoes as $ind => $instituicao) { ?>
                                <option value="<?php echo $instituicao["idsindicato"]; ?>" <?php if($_POST["idsindicato"] == $instituicao["idsindicato"]) { ?>selected="selected"<?php } ?>><?php echo $instituicao["nome"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group" id="escola" <?php /*?>style="display:none;"<?php */?>>
                	<label for="idescola" id="label_idescola" class="control-label"><strong><?php echo $idioma["form_escola"]; ?></strong></label>
                    <div class="controls">
                    	<select class="span4" id="idescola" name="idescola">
                        	<option value="">- <?=$idioma["form_selecione_instituicao"]?> -</option>
                        </select>
                    </div>
                </div>
				<div class="control-group">
                    <label for="idcentro_custo" class="control-label"><strong><?php echo $idioma["form_centro"]; ?></strong></label>
                    <div class="controls">
                        <select class="span4" id="idcentro_custo" name="idcentro_custo">
                            <option value="">- <?=$idioma["form_selecione_instituicao"]?> -</option>
                        </select>
                    </div>
                </div>
				
               <div class="control-group">
                    <label for="idcategoria" class="control-label"><strong><?php echo $idioma["form_categoria"]; ?></strong></label>
                    <div class="controls">
                        <select class="span4" id="idcategoria" name="idcategoria">
                            <option value="">- <?=$idioma["form_selecione_centro"]?> -</option>
                        </select>
                    </div>
                </div>
				
               <div class="control-group">
                    <label for="idsubcategoria" class="control-label"><strong><?php echo $idioma["form_subcategoria"]; ?></strong></label>
                    <div class="controls">
                        <select class="span4" id="idsubcategoria" name="idsubcategoria">
                            <option value="">- <?=$idioma["form_selecione_categoria"]?> -</option>
                        </select>
                    </div>
                </div>
				
                <div class="control-group">
                	<label for="nome" class="control-label"><strong><?php echo $idioma["form_titulo"]; ?></strong></label>
                    <div class="controls">
                    	<input type="text" maxlength="100" value="<?php echo $_POST["nome"]; ?>" name="nome" id="nome" class="span4">
                    </div>
                </div>
			  <div class="control-group">
                	<label for="valor" class="control-label"><strong><?php echo $idioma["form_valor"]; ?></strong></label>
                    <div class="controls">
                    	<input type="text" maxlength="100" value="<?php echo $_POST["valor"]; ?>" name="valor" id="valor" class="span2">
                    </div>
                </div>
				<div class="control-group">
                	<label for="form_titulo" class="control-label"><?php echo 'Arquivos'; ?> </label>
                    <div class="controls">
                    	<div id="divArquivos">	
						  <input type="file" name="arquivo[1]" id="arquivo[1]" /> <input type="button" class="btn btn-primary btn-mini" onclick="novoArquivo();" name="enviar" value=" + " />
						  <br />
						</div>
                    </div>
                </div>
                <div class="control-group">
                	<label for="descricao" class="control-label"><strong><?php echo $idioma["form_descricao"]; ?></strong></label>
                    <div class="controls">
                    	<textarea name="descricao" id="form_descricao" class="xxlarge"><?php echo $_POST["descricao"]; ?></textarea>
					</div>
                </div>
            </fieldset>          
            <div class="form-actions"><input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
  <script type="text/javascript">

	  var regrasObr = new Array();
	  regrasObr.push("required,idsindicato,<?php echo $idioma["instituicao_vazio"]; ?>");
	  regrasObr.push("required,idescola,<?php echo $idioma["escola_vazio"]; ?>");
	  regrasObr.push("required,idcentro_custo,<?php echo $idioma["centro_vazio"]; ?>");
	  regrasObr.push("required,idcategoria,<?php echo $idioma["categoria_vazio"]; ?>");
	  regrasObr.push("required,idsubcategoria,<?php echo $idioma["subcategoria_vazio"]; ?>");
	  regrasObr.push("required,nome,<?php echo $idioma["titulo_vazio"]; ?>");
	  regrasObr.push("required,valor,<?php echo $idioma["valor_vazio"]; ?>");
	  regrasObr.push("required,form_descricao,<?php echo $idioma["descricao_vazio"]; ?>");
	  
	  var regras = regrasObr;
	  
	  jQuery(document).ready(function($) {
		 
		 $("#valor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
		 
	var editor = CKEDITOR.replace( 'descricao', {
/*
* Ensure that htmlwriter plugin, which is required for this sample, is loaded.
*/
extraPlugins: 'htmlwriter',

height: 290,
width: '85%',
toolbar: [
["Bold","Italic","Underline","StrikeThrough","-",
"Outdent","Indent","NumberedList","BulletedList"],
["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
["Image","Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],
],

/*
* Style sheet for the contents
*/
contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',

/*
* Quirks doctype
*/
docType: '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',

/*
* Core styles.
*/
coreStyles_bold: { element: 'b' },
coreStyles_italic: { element: 'i' },
coreStyles_underline: { element: 'u' },

/*
* Font face.
*/

// Define the way font elements will be applied to the document. The "font"
// element will be used.
font_style: {
element: 'font',
attributes: { 'face': '#(family)' }
},

/*
* Font sizes.
*/

// The CSS part of the font sizes isn't used by Flash, it is there to get the
// font rendered correctly in CKEditor.
fontSize_sizes: '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28;36px/36;48px/48;72px/72',
fontSize_style: {
element: 'font',
attributes: { 'size': '#(size)' },
styles: { 'font-size': '#(size)px' }
} ,

/*
* Font colors.
*/
colorButton_enableMore: true,

colorButton_foreStyle: {
element: 'font',
attributes: { 'color': '#(color)' }
},

colorButton_backStyle: {
element: 'font',
styles: { 'background-color': '#(color)' }
},

on: { 'instanceReady': configureFlashOutput }
});

/*
* Adjust the behavior of the dataProcessor to match the
* requirements of Flash
*/
function configureFlashOutput( ev ) {
var editor = ev.editor,
dataProcessor = editor.dataProcessor,
htmlFilter = dataProcessor && dataProcessor.htmlFilter;

// Out self closing tags the HTML4 way, like <br>.
dataProcessor.writer.selfClosingEnd = '>';

// Make output formatting match Flash expectations
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
		  
		  	<?php if($_POST['idsindicato']){ ?>
					$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/escola"; ?>',{idsindicato:<?=$_POST['idsindicato']?>, ajax: 'true'}, function(json){
					  var options = '<option value="">– <?php echo $idioma["form_selecione_escola"]; ?> –</option>';
					  for (var i = 0; i < json.length; i++) {
						 var selected = '';
						 if(json[i].idescola == <?=$_POST['idescola']?>)
						 	selected = 'selected';
						options += '<option value="' + json[i].idescola + '" '+selected+'>' + json[i].nome + '</option>';
					  }	
					  $('#idescola').html(options);
					});
					
					$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/centrocusto"; ?>',{idsindicato: <?=$_POST['idsindicato']?>, ajax: 'true'}, function(json){
					  var options = '<option value="">– <?php echo $idioma["form_selecione_centro"]; ?> –</option>';
					  for (var i = 0; i < json.length; i++) {
						 var selected = '';
						 if(json[i].idcentro_custo == '<?=$_POST['idcentro_custo']?>')
						 	selected = 'selected';
						options += '<option value="' + json[i].idcentro_custo + '" '+selected+'>' + json[i].nome + '</option>';
					  }	
					  $('#idcentro_custo').html(options);
					});
					
					$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/categoria"; ?>',{idsindicato: <?=$_POST['idsindicato']?>, ajax: 'true'}, function(json){
					  var options = '<option value="">– <?php echo $idioma["form_selecione_categoria"]; ?> –</option>';
					  for (var i = 0; i < json.length; i++) {
						 var selected = '';
						 if(json[i].idcategoria == '<?=$_POST['idcategoria']?>')
						 	selected = 'selected';
						options += '<option value="' + json[i].idcategoria + '" '+selected+'>' + json[i].nome + '</option>';
					  }	
					  $('#idcategoria').html(options);
					});
			<?php }?>
			
			<?php if($_POST['idcategoria']){ ?>
					$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/subcategoria"; ?>',{idcategoria:<?=$_POST['idcategoria']?>,idsindicato: <?=$_POST['idsindicato']?>, ajax: 'true'}, function(json){
					  var options = '<option value="">– <?php echo $idioma["form_selecione_subcategoria"]; ?> –</option>';
					  for (var i = 0; i < json.length; i++) {
						var selected = '';
						 if(json[i].idsubcategoria == '<?=$_POST['idsubcategoria']?>')
						 	selected = 'selected';
						options += '<option value="' + json[i].idsubcategoria + '" '+selected+'>' + json[i].nome + '</option>';
					  }	
					  $('#idsubcategoria').html(options);
					});
			<?php }?>
			
			  
		$('#idsindicato').change(function(){
		  if($(this).val()){
			
			$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/escola"; ?>',{idsindicato: $(this).val(), ajax: 'true'}, function(json){
			  var options = '<option value="">– <?php echo $idioma["form_selecione_escola"]; ?> –</option>';
			  for (var i = 0; i < json.length; i++) {
				options += '<option value="' + json[i].idescola + '" >' + json[i].nome + '</option>';
			  }	
			  $('#idescola').html(options);
			});
			
		 	$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/centrocusto"; ?>',{idsindicato: $(this).val(), ajax: 'true'}, function(json){
			  var options = '<option value="">– <?php echo $idioma["form_selecione_centro"]; ?> –</option>';
			  for (var i = 0; i < json.length; i++) {
				options += '<option value="' + json[i].idcentro_custo + '" >' + json[i].nome + '</option>';
			  }	
			  $('#idcentro_custo').html(options);
			});
						
		  } else {
			$('#idescola').html('<option value="">– <?php echo $idioma["form_selecione_instituicao"]; ?> –</option>');
			$('#idcentro_custo').html('<option value="">– <?php echo $idioma["form_selecione_instituicao"]; ?> –</option>');
			$('#idcategoria').html('<option value="">– <?php echo $idioma["form_selecione_centro"]; ?> –</option>');
			$('#idsubcategoria').html('<option value="">– <?php echo $idioma["form_selecione_categoria"]; ?> –</option>');
		  }
		});
		
		
		$('#idcentro_custo').change(function(){
		   if($(this).val()){
				 $.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/categoria"; ?>',{idsindicato: $('#idsindicato').val(),idcentro_custo: $(this).val(), ajax: 'true'}, function(json){
				  var options = '<option value="">– <?php echo $idioma["form_selecione_categoria"]; ?> –</option>';
				  for (var i = 0; i < json.length; i++) {
					options += '<option value="' + json[i].idcategoria + '" >' + json[i].nome + '</option>';
				  }	
				  $('#idcategoria').html(options);
				});
		   } else {
				$('#idcategoria').html('<option value="">– <?php echo $idioma["form_selecione_centro"]; ?> –</option>');
				$('#idsubcategoria').html('<option value="">– <?php echo $idioma["form_selecione_categoria"]; ?> –</option>');
		   }
		});
		
		
		$('#idcategoria').change(function(){
		  if($(this).val()){
						
			$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/subcategoria"; ?>',{idcategoria: $(this).val(),idcentro_custo: $('#idcentro_custo').val(),idsindicato: $('#idsindicato').val(), ajax: 'true'}, function(json){
			  var options = '<option value="">– <?php echo $idioma["form_selecione_subcategoria"]; ?> –</option>';
			  for (var i = 0; i < json.length; i++) {
				options += '<option value="' + json[i].idsubcategoria + '" >' + json[i].nome + '</option>';
			  }	
			  $('#idsubcategoria').html(options);
			});
			
		  } else {
			$('#idsubcategoria').html('<option value="">– <?php echo $idioma["form_selecione_categoria"]; ?> –</option>');
		  }
		});
		
	  });
  </script>
  
<?php /*?><script type="text/javascript">
	var regras = new Array();
	regras.push("formato_arquivo,arquivo[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
	regras.push("required,resposta,<?php echo $idioma['mensagem_vazio']; ?>");
</script><?php */?>
<script type="text/javascript">
function novoArquivo(){
	var IE = document.all?true:false
	var div_arquivos = document.getElementById( "divArquivos" );		
	if( !IE ){
		var length = div_arquivos.childNodes.length -1;
	}else{
		var length = div_arquivos.childNodes.length +1;			
	}		

	var input = document.createElement( 'INPUT' );
	input.setAttribute( "type" , "file" );
	id = "arquivo[" + length + "]";
	input.setAttribute( "name" , id);	
	input.setAttribute( "id" , id);
	div_arquivos.appendChild( input );
	var br = document.createElement('br');
	div_arquivos.appendChild(br);
	
	regras.push("formato_arquivo,"+id+",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}
</script>
</div>
</body>
</html>