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
            <?php if ($_GET["q"]) { ?>
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
                <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" method="get" id="form_filtro" onsubmit="return formOrcamentogasto();">
                    <?php
                    $anoInicio = 2014;
                    $anoFim = date("Y") + 2;
                    ?>
                    <table id="sortTableExample" style="margin-bottom: 0px; " class="table tabelaSemHover tabelaSemTamanho">
                        <tbody>
                        <tr>
                            <td style="border-top: 0px;"><?php echo $idioma["sindicato"]; ?><br/>
                                <select class="span3" onchange="retornarCentrosCusto(this);" id="idsindicato" name="idsindicato">
                                    <option value="">Selecione um sindicato</option>
                                    <?php foreach ($sindicatosArray as $ind => $sindicato) { ?>
                                        <option value="<?php echo $sindicato["idsindicato"]; ?>"
                                                <?php if ($_GET["idsindicato"] == $sindicato["idsindicato"] || $sindicato["idsindicato"] == $dadosArray[0]["idsindicato"]) { ?>selected="selected"<?php } ?>><?php echo $sindicato["nome_abreviado"]; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><?php echo $idioma["centro_custo"]; ?><br/>
                                <select onchange="retornarCategorias(this);" class="span3" id="idcentro_custo" name="idcentro_custo">
                                    <option value="">Selecione um sindicato</option>
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
                                <select onchange="retornarSubCategorias(this);" class="span3" id="idcategoria" name="idcategoria">
                                    <option value="">Selecione um centro de custo</option>
                                    <?php foreach($categorias as $categoria){ ?>
                                        <option <?php if($_GET["idcategoria"] == $categoria["idcategoria"] || $categoria["idcategoria"] == $dadosArray[0]["idcategoria"]){ ?> selected="selected" <?php } ?> value="<?php echo $categoria["idcategoria"]?>"><?php echo $categoria["nome"]?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><?php echo $idioma["subcategoria"]; ?><br/>
                                <select  style="width:auto" id="idsubcategoria" name="idsubcategoria">
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

                <?php if ($polos && empty($dadosArray)) { ?>
                    <form class="form-horizontal" enctype="multipart/form-data"
                          onsubmit="return validateFields(this, regras)" method="post">
                        <input type="hidden" id="acao" name="acao" value="salvar"/>
                        <input type="hidden" id="idsindicato" name="idsindicato" value="<?php echo $_GET["idsindicato"]?>"/>
                        <input type="hidden" id="idcentro_custo" name="idcentro_custo" value="<?php echo $_GET["idcentro_custo"]?>"/>
                        <input type="hidden" id="idcategoria" name="idcategoria" value="<?php echo $_GET["idcategoria"]?>"/>
                        <input type="hidden" id="idsubcategoria" name="idsubcategoria" value="<?php echo $_GET["idsubcategoria"]?>"/>
                        <input type="hidden" id="ano" name="ano" value="<?php echo $_GET["ano"]?>"/>
                        <table id="sortTableExample" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                            <tr>
                                <th><?php echo $idioma["polos"];?></th>
                                <th><?php echo $idioma["descricao"];?></th>
                                <th><?php echo $idioma["memorial"];?></th>
                                <th><?php echo $idioma["jan"];?></th>
                                <th><?php echo $idioma["fev"];?></th>
                                <th><?php echo $idioma["mar"];?></th>
                                <th><?php echo $idioma["abr"];?></th>
                                <th><?php echo $idioma["mai"];?></th>
                                <th><?php echo $idioma["jun"];?></th>
                                <th><?php echo $idioma["jul"];?></th>
                                <th><?php echo $idioma["ago"];?></th>
                                <th><?php echo $idioma["set"];?></th>
                                <th><?php echo $idioma["out"];?></th>
                                <th><?php echo $idioma["nov"];?></th>
                                <th><?php echo $idioma["dez"];?></th>
                                <th><?php echo $idioma["total"]; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  foreach($polos as $key => $polo){ ?>
                                <tr>
                                    <td><?php echo $polo["nome_fantasia"] ?></td>
                                    <td><input value="<?php echo $_POST["polos"][$polo["idescola"]]["descricao"];?>" id="<?php echo $polo["idescola"];?>" name="polos[<?php echo $polo["idescola"];?>][descricao]" class="span2" type="text"></input></td>
                                    <td><input value="<?php echo $_POST["polos"][$polo["idescola"]]["memorial"];?>" id="<?php echo $polo["idescola"];?>" name="polos[<?php echo $polo["idescola"];?>][memorial]" class="span1" type="text"></input></td>
                                    <?php foreach($meses as $v){
                                        ?>
                                        <td><input onkeyup="somarValorTotalHorizontal(this)" value="<?php echo $_POST["polos"][$polo["idescola"]][$v] ?>" id="polo.<?php echo $polo["idescola"]."-".$v;?>" name="polos[<?php echo $polo["idescola"];?>][<?php echo $v;?>]" class="span1 inputValor" type="text"></input></td>
                                    <?php  } ?>
                                    <td><input value="<?php if($polo["total"]){ echo number_format($polo["total"], 2, ",", ".");} else if($_POST["totalHorizontalPolo{$polo["idescola"]}"]){ echo $_POST["totalHorizontalPolo{$polo["idescola"]}"]; } ?>" name="totalHorizontalPolo<?php echo $polo["idescola"];?>" id="totalHorizontalPolo<?php echo $polo["idescola"];?>" type="text" readonly class="span1"></input></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="text-align:right;"><strong><?php echo $idioma["total"]; ?>:</strong></td>
                                <?php foreach($meses as $val){
                                    ?>
                                    <td><input class="span1" value="<?php echo $_POST["total" . $val . "Vertical"]; ?>" name="total<?php echo $val; ?>Vertical" id="total<?php echo $val; ?>Vertical" type="text" readonly></input></td>
                                <?php } ?>
                            </tr>
                            </tbody>
                        </table>
                        <span class="pull-right"><strong>Total Geral:</strong> <input name="totalgeral" value="<?php echo $_POST["totalgeral"]; ?>" id="totalGeral" type="text" class="span2" readonly="readonly"></span>
                        <div class="clearfix"></div>
                        <div class="form-actions">
                            <input type="submit" class="pull-right btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                        </div>
                    </form>
                <?php } ?>

                <?php if ($dadosArray) { ?>
                    <form class="form-horizontal" enctype="multipart/form-data"
                          onsubmit="return validateFields(this, regras)" method="post">
                        <input type="hidden" id="acao" name="acao" value="salvar"/>
                        <input type="hidden" id="idsindicato" name="idsindicato" value="<?php echo $idinstituicao = $dadosArray[0]["idsindicato"] ? $dadosArray[0]["idsindicato"] : $_GET["idsindicato"]?>"/>
                        <input type="hidden" id="idcentro_custo" name="idcentro_custo" value="<?php echo $idcentro_custo = $dadosArray[0]["idcentro_custo"] ? $dadosArray[0]["idcentro_custo"] : $_GET["idcentro_custo"]?>"/>
                        <input type="hidden" id="idcategoria" name="idcategoria" value="<?php echo $idcategoria = $dadosArray[0]["idcategoria"] ? $dadosArray[0]["idcategoria"] : $_GET["idcategoria"]?>"/>
                        <input type="hidden" id="idsubcategoria" name="idsubcategoria" value="<?php echo $idsubcategoria = $dadosArray[0]["idsubcategoria"] ? $dadosArray[0]["idsubcategoria"] : $_GET["idsubcategoria"]?>"/>
                        <input type="hidden" id="ano" name="ano" value="<?php echo $ano = $dadosArray[0]["ano"] ? $dadosArray[0]["ano"] : $_GET["ano"]; ?>"/>
                        <table id="sortTableExample" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                            <tr>
                                <th><?php echo $idioma["cfcs"];?></th>
                                <th><?php echo $idioma["descricao"];?></th>
                                <th><?php echo $idioma["memorial"];?></th>
                                <th><?php echo $idioma["jan"];?></th>
                                <th><?php echo $idioma["fev"];?></th>
                                <th><?php echo $idioma["mar"];?></th>
                                <th><?php echo $idioma["abr"];?></th>
                                <th><?php echo $idioma["mai"];?></th>
                                <th><?php echo $idioma["jun"];?></th>
                                <th><?php echo $idioma["jul"];?></th>
                                <th><?php echo $idioma["ago"];?></th>
                                <th><?php echo $idioma["set"];?></th>
                                <th><?php echo $idioma["out"];?></th>
                                <th><?php echo $idioma["nov"];?></th>
                                <th><?php echo $idioma["dez"];?></th>
                                <th><?php echo $idioma["total"]; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($dadosArray as $key => $polo){
                                if(count($polo) > 12){

                                    ?>
                                    <tr>
                                        <td><?php echo $polo["nome_fantasia"] ?></td>
                                        <td><input value="<?php echo $polo["descricao"];?>" id="<?php echo $polo["idescola"];?>" name="polos[<?php echo $polo["idescola"];?>][descricao]" class="span2" type="text"></input></td>
                                        <td><input value="<?php echo $polo["memorial"];?>" id="<?php echo $polo["idescola"];?>" name="polos[<?php echo $polo["idescola"];?>][memorial]" class="span1" type="text"></input></td>
                                        <?php foreach($polo as $k => $v){
                                            if(in_array($k, $meses)){
                                                ?>
                                                <input value="<?php echo $polo["idorcamento"]?>" type="hidden" name="polos[<?php echo $polo["idescola"];?>][idorcamento]">
                                                <td><input value="<?php if($polo[$k]){ echo number_format($polo[$k], 2, ",", ".");}?>" onkeyup="somarValorTotalHorizontal(this)" id="polo.<?php echo $polo["idescola"]."-".$k;?>" name="polos[<?php echo $polo["idescola"];?>][<?php echo $k;?>]" class="span1 inputValor" type="text"></input></td>
                                            <?php } } ?>
                                        <td><input value="<?php if($polo["total"]){ echo number_format($polo["total"], 2, ",", ".");}?>" name="totalHorizontalPolo<?php echo $polo["idescola"];?>" id="totalHorizontalPolo<?php echo $polo["idescola"];?>" type="text" readonly="readonly" class="span1"></input></td>
                                    </tr>
                                <?php } }?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="text-align:right;"><strong><?php echo $idioma["total"]; ?>:</strong></td>
                                <?php foreach($dadosArray["totalmes"] as $key => $val){
                                    ?>
                                    <td><input class="span1" value="<?php if($dadosArray["totalmes"][$key]){ echo number_format($dadosArray["totalmes"][$key], 2, ",", ".");}?>" name="total<?php echo $key; ?>Vertical" id="total<?php echo $key; ?>Vertical" type="text" readonly="readonly"></input></td>
                                <?php } ?>
                            </tr>
                            </tbody>
                        </table>
                        <span class="pull-right"><strong>Total Geral:</strong> <input id="totalGeral" value="<?php echo number_format($totalGeral, 2, ",", ".");?>" type="text" class="span2" readonly="readonly"></span>
                        <div class="clearfix"></div>
                        <div class="form-actions">
                            <input id="btn_salvar" type="submit" class="pull-right btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                        </div>
                    </form>
                <?php }  ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script language="javascript" type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".inputValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        });

        function formOrcamentogasto(){

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


        <?php if($polos || $dadosArray) {  ?>
        function somarValorTotalHorizontal(e){
            var valor = e.id;
            var arroba = valor.indexOf(".");
            var traco = valor.indexOf("-");
            var idescola = valor.substring(arroba + 1, traco);
            var meses = ["mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12"];
            var valores = [];
            var valorFinal = 0.0;
            for(var i = 0; i < meses.length; i++){
                var elemento =  "polo."+idescola+"-"+meses[i];
                var valor = document.getElementById(elemento).value;
                if((valor !== "") && (valor !== "undefined")){
                    valor = valor.replace(".","");
                    valor = valor.replace(".","");
                    valor = valor.replace(",",".");
                    valor = parseFloat(valor);
                    valores.push(valor);
                }
            }
            for(var a = 0; a < valores.length; a++){
                parseFloat(valores[a]);
                valorFinal += valores[a];
            }

            valorFinal = number_format(valorFinal, 2, ',', '.');
            if(valorFinal === "0,00"){
                $("#totalHorizontalPolo" + idescola).val("");
                somarValorTotalVertical(e);
                somarValorTotalGeral();
            }else{

                $("#totalHorizontalPolo" + idescola).val(valorFinal);
                valor2 = somarValorTotalVertical(e);

                somarValorTotalGeral();
            }
        }

        function somarValorTotalVertical(e){
            var valor = e.id;
            var traco = valor.indexOf("-");
            var mes = valor.substring(traco + 1);
            var idPolos = [];

            <?php if($polos){ ?>
            <?php foreach($polos as $polo){ ?>

            idPolos.push(<?php echo $polo["idescola"]; ?>);
            <?php } ?>
            <?php }elseif($dadosArray){ ?>
            <?php foreach($dadosArray as $polo){ ?>
            idPolos.push(<?php echo $polo["idescola"]; ?>);
            <?php } ?>
            <?php } ?>
            var valores = [];
            var valorFinal = 0.0;

            for(var i = 0; i < idPolos.length; i++){
                var elemento = "polo."+idPolos[i]+"-"+mes;

                var valor = document.getElementById(elemento).value;
                if((valor !== "") && (valor !== "undefined")){
                    valor = valor.replace(".","");
                    valor = valor.replace(".","");
                    valor = valor.replace(",",".");
                    valor = parseFloat(valor);
                    valores.push(valor);
                }
            }
            for(var a = 0; a < valores.length; a++){
                parseFloat(valores[a]);
                valorFinal += valores[a];
            }

            var valorAux = valorFinal;
            valorFinal = number_format(valorFinal, 2, ',', '.');
            if(valorFinal === "0,00"){
                $("#total"+ mes +"Vertical").val("");
                somarValorTotalGeral();
            }else{
                $("#total"+ mes +"Vertical").val(valorFinal);
                somarValorTotalGeral();
            }

            return valorAux;
        }

        function somarValorTotalGeral(){
            var idPolos = [];
            <?php if($polos){ ?>
            <?php foreach($polos as $polo){ ?>
            idPolos.push(<?php echo $polo["idescola"]; ?>);
            <?php } ?>
            <?php }elseif($dadosArray){ ?>
            <?php foreach($dadosArray as $polo){ ?>
            idPolos.push(<?php echo $polo["idescola"]; ?>);
            <?php } ?>
            <?php } ?>
            var valores = [];
            var valorFinal = 0.0;
            for(var i = 0; i < idPolos.length; i++){
                var elemento = "totalHorizontalPolo"+idPolos[i];
                var valor = document.getElementById(elemento).value;
                if((valor !== "") && (valor !== "undefined")){
                    valor = valor.replace(".","");
                    valor = valor.replace(".","");
                    valor = valor.replace(",",".");
                    valor = parseFloat(valor);
                    valores.push(valor);
                }
            }
            var meses = ["mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12"];
            for(var i = 0; i < meses.length; i++){
                var elemento =  "total"+meses[i]+"Vertical";
                var valor = document.getElementById(elemento).value;
                if((valor !== "") && (valor !== "undefined")){
                    valor = valor.replace(".","");
                    valor = valor.replace(".","");
                    valor = valor.replace(",",".");
                    valor = parseFloat(valor);
                    valores.push(valor);
                }
            }
            for(var a = 0; a < valores.length; a++){
                parseFloat(valores[a]);
                valorFinal += valores[a];
            }
            valorFinal /= 2;
            valorFinal = number_format(valorFinal, 2, ',', '.');
            if(valorFinal === "0,00"){
                document.getElementById("totalGeral").value = "";
            }else{

                document.getElementById("totalGeral").value = valorFinal;
            }
        }

        <?php } ?>


        function number_format( number, decimals, dec_point, thousands_sep ) {
            // %     nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
            // *     exemplo 1: number_format(1234.56);
            // *     retorno 1: '1,235'
            // *     exemplo 2: number_format(1234.56, 2, ',', ' ');
            // *     retorno 2: '1 234,56'
            // *     exemplo 3: number_format(1234.5678, 2, '.', '');
            // *     retorno 3: '1234.57'
            // *     exemplo 4: number_format(67, 2, ',', '.');
            // *     retorno 4: '67,00'
            // *     exemplo 5: number_format(1000);
            // *     retorno 5: '1,000'
            // *     exemplo 6: number_format(67.311, 2);
            // *     retorno 6: '67.31'

            var n = number, prec = decimals;
            n = !isFinite(+n) ? 0 : +n;
            prec = !isFinite(+prec) ? 0 : Math.abs(prec);
            var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
            var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

            var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

            var abs = Math.abs(n).toFixed(prec);
            var _, i;

            if (abs >= 1000) {
                _ = abs.split(/\D/);
                i = _[0].length % 3 || 3;

                _[0] = s.slice(0,i + (n < 0)) +
                    _[0].slice(i).replace(/(\d{3})/g, sep+'$1');

                s = _.join(dec);
            } else {
                s = s.replace('.', dec);
            }

            return s;
        }

        function retornarCentrosCusto(e){
            console.log(e.value)
            if(e.value){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/centros_custo",
                    dataType: "json",
                    type: "GET",
                    data: {idsindicato: e.value},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione um centro de custo</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                options += "<option " + selected + " value="+ json[i].idcentro_custo +">" + json[i].nome + "</option>";
                            }
                            $("#idcentro_custo").html(options);
                            $("#idcategoria").html("<option>Selecione um centro de custo</option>");
                            $("#idsubcategoria").html("<option>Selecione uma categoria</option>");
                        } else {

                        }
                    }
                });
            }else{
                $("#idcentro_custo").html("<option>Selecione uma instituição</option>");
                $("#idcategoria").html("<option>Selecione um centro de custo</option>");
                $("#idsubcategoria").html("<option>Selecione uma categoria</option>");
            }
        }

        function retornarCategorias(e){
            console.log(e.value)
            if(e.value){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/categorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcentro_custo: e.value,
                        idsindicato: $("#idsindicato").val()},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma categoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                options += "<option " + selected +" value="+ json[i].idcategoria +">" + json[i].nome + "</option>";
                            }
                            $("#idcategoria").html(options);
                        } else {

                        }
                    }
                });
            }else{
                $("#idcategoria").html("<option>Selecione um centro de custo</option>");
                $("#idsubcategoria").html("<option>Selecione uma categoria</option>");
            }
        }
        function retornarSubCategorias(e){
            console.log(e.value)
            if(e.value){
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/subcategorias",
                    dataType: "json",
                    type: "GET",
                    data: {idcategoria: e.value,
                        idsindicato: $("#idsindicato").val(),
                        idcentro_custo: $("#idcentro_custo").val()},
                    success: function (json) {
                        if (json) {
                            var options = "<option value=''>Selecione uma subcategoria</option>";
                            var selected = "";
                            for(var i = 0; i < json.length; i++){
                                <?php /* if($_GET["idsubcategoria"]){?>
                                if(<?php echo $_GET["idsubcategoria"]?> == json[i].idsubcategoria){
                                    selected = "selected";
                                }else{
                                    selected = "";
                                }
                                <?php } */?>
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
        $(document).ready(function(){
            <?php if(!$_GET["ano"]){?>
            $("#ano").val(<?php echo date("Y");?>);
            <?php } ?>
            <?php if(!$polos && !$dadosArray){?>
            var sindicato = document.getElementById("idsindicato");
            var idcentro_custo = document.getElementById("idcentro_custo");
            var idcatergoria = document.getElementById("idcategoria");
            var idsubcategoria = document.getElementById("idsubcategoria");
            if(!sindicato && !idcentro_custo && !idcatergoria && !idsubcategoria){
                var botao = document.getElementById("exibir_polo");
                botao.disabled = true;
            }
            <?php } ?>
        });
    </script>
</div>
</body>
</html>