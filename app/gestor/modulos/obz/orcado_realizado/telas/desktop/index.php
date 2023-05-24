<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>
        .form-horizontal .control-group {
            margin-bottom: 2px !important;
        }

        .control-group {
            margin-bottom: 4px !important;
        }

        .table th, .table td {
            padding: 5px !important;
        }

        .label-de {
            padding: 2px;
            margin-left: 10px;
            line-height: 27px;
        }

        .label-ate {
            padding: 2px;
            margin-left: 10px;
            line-height: 27px;
        }

        .campo-de {
            padding: 2px;
        }

        .campo-ate {
            padding: 2px;
        }
    </style>
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
            <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
            <?php if ($_GET["idsindicato"]) { ?>
                <li><span class="divider">/</span> <a
                    href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a>
                </li><?php } ?>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <?php if ($_POST["msg"]) { ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                    </div>
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
                <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" method="get" id="form_filtro" onsubmit="return formOrcadoRealizado();">
                    <?php
                    $anoInicio = 2014;
                    $anoFim = date("Y") + 2;
                    ?>
                    <table id="sortTableExample" style="margin-bottom: 0px; " class="table tabelaSemHover tabelaSemTamanho">
                        <tbody>
                        <tr>
                            <td style="border-top: 0px;"><?php echo $idioma["sindicato"]; ?><br/>
                                <select class="span3" onchange="retornarCentrosCusto(this.value);" id="idsindicato" name="idsindicato">
                                    <option value="">Selecione uma sindicato</option>
                                    <?php foreach ($sindicatoArray as $ind => $sindicato) { ?>
                                        <option value="<?php echo $sindicato["idsindicato"]; ?>"
                                                <?php if ($_GET["idsindicato"] == $sindicato["idsindicato"] || $sindicato["idsindicato"] == $dadosArray[0]["idsindicato"]) { ?>selected="selected"<?php } ?>><?php echo $sindicato["nome_abreviado"]; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><?php echo $idioma["centro_custo"]; ?><br/>
                                <select onchange="retornarCategoriasget(this.value);" class="span3" id="idcentro_custo" name="idcentro_custo">
                                    <option value="">Selecione uma sindicato</option>
                                    <?php foreach($centrosCustos as $centroCusto){ ?>
                                    <option <?php if($_GET["idcentro_custo"] == $centroCusto["idcentro_custo"] || $centroCusto["idcentro_custo"] == $dadosArray[0]["idcentro_custo"]){ ?> selected="selected" <?php } ?> value="<?php echo $centroCusto["idcentro_custo"]?>"><?php echo $centroCusto["nome"]?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><?php echo $idioma["ano"]; ?><br/>
                                <select id="ano" class="inputPreenchimentoCompleto" name="ano">
                                    <?php for ($ano = $anoInicio; $ano <= $anoFim; $ano++) { ?>
                                        <option value="<?php echo $ano; ?>"
                                                <?php if ($_GET["ano"] == $ano) { ?>selected="selected"<?php } ?>><?php echo $ano; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                     <table id="sortTableExample" class="table tabelaSemHover tabelaSemTamanho">
                        <tbody>
                        <tr>
                            <td style="border-top: 0px;"><?php echo $idioma["categoria"]; ?><br/>
                                <select onchange="retornarSubCategorias(this.value);" class="span3" id="idcategoria" name="idcategoria">
                                    <option value="">Selecione um centro de custo</option>
                                    <?php foreach($categorias as $categoria){ ?>
                                    <option <?php if($_GET["idcategoria"] == $categoria["idcategoria"] || $categoria["idcategoria"] == $dadosArray[0]["idcategoria"]){ ?> selected="selected" <?php } ?> value="<?php echo $categoria["idcategoria"]?>"><?php echo $categoria["nome"]?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><?php echo $idioma["subcategoria"]; ?><br/>
                                <select style="width:auto;" id="idsubcategoria" name="idsubcategoria">
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach($subCategorias as $subCategoria){ ?>
                                    <option <?php if($_GET["idsubcategoria"] == $subCategoria["idsubcategoria"] || $subCategoria["idsubcategoria"] == $dadosArray[0]["idsubcategoria"]){ ?> selected="selected" <?php } ?> value="<?php echo $subCategoria["idsubcategoria"]?>"><?php echo $subCategoria["nome"]?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><button style="margin-top:15px;" id="exibir_polo"  class="btn btn-primary" type="submit"><?php echo $idioma["exibir_polo"]?></button></td>
                        </tr>
                        </tbody>
                    </table>
                </form>

                  <?php if(is_array($dadosArray))$linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma);  ?>
                
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script language="javascript" type="text/javascript">
      
		function formOrcadoRealizado(){
			
			if(document.getElementById('idsindicato').value == ''){
				alert('<?=$idioma["insituicao_vazio"]?>');
				document.getElementById('idsindicato').focus();
				return false;
			}
			
			if(document.getElementById('idcentro_custo').value == ''){
				alert('<?=$idioma["centro_vazio"]?>');
				document.getElementById('idcentro_custo').focus();
				return false;
			}
			
			if(document.getElementById('idcategoria').value == ''){
				alert('<?=$idioma["categoria_vazio"]?>');
				document.getElementById('idcategoria').focus();
				return false;
			}
			
			if(document.getElementById('idsubcategoria').value == ''){
				alert('<?=$idioma["subcategoria_vazio"]?>');
				document.getElementById('idsubcategoria').focus();
				return false;
			}
			
		}

		
        function retornarCentrosCusto(valor){
            console.log(valor)
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/centros_custo",
                    dataType: "json",
                    type: "GET",
                    data: {idsindicato: valor},
                    success: function (json) {
                        if (json) {
                            console.log(json)
                            var options = "<option value=''>Selecione um centro de custo</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                               
                                options += "<option " + selected + " value="+ json[i].idcentro_custo +">" + json[i].nome + "</option>";
                            }
                            $("#idcentro_custo").html(options);
                        } else {
                            
                        }
                    }
                });
            }else{
                $("#idcentro_custo").html("<option>Selecione uma sindicato</option>");
                $("#idcategoria").html("<option>Selecione uma sindicato</option>");
                $("#idsubcategoria").html("<option>Selecione uma sindicato</option>");
            }
        }
        
        function retornarCategorias(valor){
            console.log(valor)
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/categorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcentro_custo: valor, 
                           idsindicato: $("#idsindicato").val()},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma categoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                
                                options += "<option " + selected +" value="+ json[i].idcategoria +">" + json[i].nome + "</option>";
                            }
                            $("#idcategoria").html(options);
							$("#idsubcategoria").html("<option>Selecione uma categoria</option>");
                        } else {
                            
                        }
                    }
                });
            }else{
                $("#idcategoria").html("<option>Selecione um centro de custo</option>");
                $("#idsubcategoria").html("<option>Selecione um centro de custo</option>");
            }
        }
        function retornarSubCategorias(valor){
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/subcategorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcategoria: valor,
                           idsindicato: $("#idsindicato").val(),
                           idcentro_custo: $("#idcentro_custo").val()},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma subcategoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                
                                options += "<option " + selected +" value="+ json[i].idsubcategoria +">" + json[i].nome + "</option>";
                            }
                            $("#idsubcategoria").html(options);
                        } else {
                            
                        }
                    }
                });
            }else{
                $("#idsubcategoria").html("<option>Selecione uma categoria</option>");
			}
        }
		
		function retornarCentrosCustoget(valor){
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/centros_custo",
                    dataType: "json",
                    type: "GET",
                    data: {idsindicato: valor},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione um centro de custo</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                <?php if($_GET["idcentro_custo"]){?>
                                if(<?php echo $_GET["idcentro_custo"]?> == json[i].idcentro_custo){
                                    selected = "selected";
                                }else{
                                    selected = "";
                                }
                                <?php } ?>
                                options += "<option " + selected + " value="+ json[i].idcentro_custo +">" + json[i].nome + "</option>";
                            }
                            $("#idcentro_custo").html(options);
                        } else {
                            
                        }
                    }
                });
            }else{
                $("#idcentro_custo").html("<option>Selecione um sindicato</option>");
                $("#idcategoria").html("<option>Selecione um sindicato</option>");
                $("#idsubcategoria").html("<option>Selecione um sindicato</option>");
            }
        }
        
        function retornarCategoriasget(valor){
            console.log(valor)
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/categorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcentro_custo: valor, 
                           idsindicato: $("#idsindicato").val()},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma categoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                <?php if($_GET["idcategoria"]){?>
                                if(<?php echo $_GET["idcategoria"]?> == json[i].idcategoria){
                                    selected = "selected";
                                }else{
                                    selected = "";
                                }
                                <?php } ?>
                                options += "<option " + selected +" value="+ json[i].idcategoria +">" + json[i].nome + "</option>";
                            }
                            $("#idcategoria").html(options);
                        } else {
                            
                        }
                    }
                });
            }else{
                $("#idcategoria").html("<option>Selecione um centro de custo</option>");
                $("#idsubcategoria").html("<option>Selecione um centro de custo</option>");
            }
        }
       
		
		function retornarSubCategoriasget(valor,centro){
            if(valor){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/subcategorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcategoria: valor,
                           idsindicato: $("#idsindicato").val(),
                           idcentro_custo: centro},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma subcategoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                <?php if($_GET["idsubcategoria"]){?>
                                if(<?php echo $_GET["idsubcategoria"]?> == json[i].idsubcategoria){
                                    selected = "selected";
                                }else{
                                    selected = "";
                                }
                                <?php } ?>
                                options += "<option " + selected +" value="+ json[i].idsubcategoria +">" + json[i].nome + "</option>";
                            }
                            $("#idsubcategoria").html(options);
                        } else {
                            
                        }
                    }
                });
            }
        }
        
        $(document).ready(function(){
            <?php if(!$_GET["ano"]){?>
                   $("#ano").val(<?php echo date("Y");?>); 
            <?php } ?>
		});
    </script>
</div>
</body>
</html>