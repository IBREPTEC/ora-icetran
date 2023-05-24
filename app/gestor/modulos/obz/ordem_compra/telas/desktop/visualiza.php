<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/css/etapas.css" media="all" type="text/css"/>
    <script src='/assets/plugins/rating_h/inc/rating.js' type="text/javascript" language="javascript"></script>
    <link href='/assets/plugins/rating_h/inc/rating.css' type="text/css" rel="stylesheet"/>
    <script language="javascript">

        function msg_ativa(tipo) {
            if (tipo == 'autorizar') {
                document.getElementById('formautorizar_naoautorizar').style.display = '';
				document.getElementById('formstatus').style.display = 'none';
				document.getElementById('statustitulo').innerHTML = 'Autorizar';
          		document.getElementById('qual').value = 'autorizar';
            } else if (tipo == 'naoautorizar') {
                document.getElementById('formautorizar_naoautorizar').style.display = '';
				document.getElementById('formstatus').style.display = 'none';
                document.getElementById('statustitulo').innerHTML = 'Não autorizar';
          		document.getElementById('qual').value = 'naoautorizar';
            } else {
                document.getElementById('formautorizar_naoautorizar').style.display = 'none';
				document.getElementById('formstatus').style.display = '';
				document.getElementById('qual').value = '';
            }
        }

        function validaStatusForm() {
            if (document.getElementById('justificativa').value == '') {
                 alert('<?php echo $idioma["justificativa_vazio"]; ?>');
				 document.getElementById('justificativa').focus();
                 return false;
            } else if (document.getElementById('idmotivo').value == '') {
                 alert('<?php echo $idioma["selecione_motivo"]; ?>');
				 document.getElementById('idmotivo').focus();
                 return false;
            }
            return true;
        }
    </script>
    <style>
        .tabela {
            border: #CCC solid 1px;
            width: 100%;
        }

        .linha {
            border-bottom: #CCC solid 1px;
        }

        .coluna {
            border-right: #CCC solid 1px;
        }

        .botao {
            background-color: #F9F9F9;
            color: #000;
            height: 350px;
            border: #CCC solid 1px;
            cursor: pointer;
        }

        .nav > .li {
            border: #CCC solid 1px;
            padding: 10px;
        }

        .nav > .li:hover {
            text-decoration: none;
            background-color: #eeeeee;
        }

        .botao_big {
            height: 100px;
            margin-top: 15px;
            padding-bottom: 0px;
        }

        .li {
            border: #CCC solid 1px;
            padding: 10px;
        }

        .li:hover {
            text-decoration: none;
            background-color: #eeeeee;
        }

        .status {
            cursor: pointer;
            color: #FFF;
            font-size: 9px;
            font-weight: bold;
            padding: 5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .ativo {
            font-size: 15px;
        }

        .inativo {
            background-color: #838383;
        }

        .divCentralizada {
            position: relative;
            width: 700px;
            height: 200px;
            left: 15%;
            top: 50%;
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span>
        </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                class="divider">/</span></li>
        <li class="active">#<?php echo $linha['idordemdecompra']; ?> </li>
        <span class="pull-right"
              style="padding-top:3px; color:#999;"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span9">
<div class="box-conteudo" style="margin:0px;">
 <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
<?php if (count($salvar["erros"]) > 0) { ?>
    <div style="width: 87%;" class="control-group">
        <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <?php foreach ($salvar["erros"] as $ind => $val) { ?><br/><?php echo $idioma[$val]; ?><?php } ?>
        </div>
    </div>
<?php } ?>
<?php if (count($linha["erros"]) > 0) { ?>
    <div style="width: 87%;" class="control-group">
        <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <?php foreach ($linha["erros"] as $ind => $val) { ?><br/><?php echo $idioma[$val] . $val; ?><?php } ?>
        </div>
    </div>
<?php } ?>
<?php if ($_POST["msg"]) { ?>
    <div style="width: 87%;" class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
    </div>
    <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
<?php } ?>
<?php if ($mensagem["erro"]) { ?>
    <div style="width: 87%;" class="alert alert-error">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <?= $idioma[$mensagem["erro"]]; ?>
    </div>
    <script>alert('<?= $idioma[$mensagem["erro"]]; ?>');</script>
<?php } ?>
<div class="control-group">
<div class="page-header" style="border-bottom:0px;">

    <h2>
        <?php echo $idioma["ordemdecompra"]; ?> #<?php echo $linha['idordemdecompra']; ?>
    </h2>
    <small><strong><?= $idioma["data_abertura"]; ?></strong><?= formataData($linha["data_cad"], "br", 1); ?>
        (<?php echo diferencaDias($linha["data_cad"]); ?>)
      </small><br />
     <small><strong><?= $idioma["mesano"]; ?>:</strong>
     <?php

		$data = explode('-',$linha["data_cad"]);
	 	echo $data[1].'/'.$data[0];
     ?>
    </small>
    <br/>  <br/>
</div>
<small><?php echo $idioma["instituicao"]; ?> </small>
<h3><?php echo $linha['sindicato']; ?>  <?php if (!empty($linha['cfc'])) echo ' -> ' . $linha['cfc']; ?></h3>
<small><?php echo $idioma["centro"]; ?> </small>
<h3><?php echo $linha['centrodecusto']; ?>  </h3>
<small><?php echo $idioma["categoria"]; ?> </small>
<h3><?php echo $linha['categoria']; ?>  <?php if (!empty($linha['subcategoria'])) echo ' -> ' . $linha['subcategoria']; ?> </h3>
<small><?php echo $idioma["valor"]; ?> </small>
<h3><?php echo 'R$ '.number_format($linha['valor'],2,',','.'); ?> </h3>
<br/>

<small><?php echo $idioma["situacao"]; ?> </small>
<br/>
<fieldset>
    <div id="divSituacoes" style="padding-top:15px; padding-bottom:25px;">
        <?php foreach ($situacaoWorkflow as $ind => $val) {
				$click = '';
				if($val["cancelado"] == 'S'){
					echo ' <a href="#cancelarmatricula" rel="facebox" style="text-decoration:none;" > ';
				}else{
					$click = ' onclick="modificarSituacao(\''.$ind.'\',\''.$val["nome"].'\');" ';
				}
				?>
            <span
                id="sit_<?= $ind; ?>" <?php ($ind == $linha['idsituacao']) ? print 'class="status ativo" style="background-color: #' . $val["cor_bg"] . ';color: #' . $val["cor_nome"] . '"' : print 'class="status inativo"'; ?>
                <?php if (in_array($ind, $array_situacoes)) { echo $click ; } else { ?>data-original-title="<?= $idioma['indisponivel']; ?>" style="background-color:#CCC" rel="tooltip"<?php } ?>>
				  <?=  $val["nome"]; ?>
                </span>
        <?php
			if($val["cancelado"] == 'S'){
				echo '</a>';
			}
		} ?>
    </div>
    <?php if($linha['motivo_cancelamento']){ ?>
    <div class="alert alert-danger">
          <h4><?=$idioma['motivo_cancelamento'];?></h4>
          <br>
          <strong><?=$linha['motivo_cancelamento']?></strong>
    </div>
    <?php }?>

    <div id="cancelarmatricula" style="display:none">
      <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cancelar" width="400" height="350" frameborder="0"></iframe>
    </div>
    <script type="text/javascript">
        function modificarSituacao(para, nome) {
            var de = "<?= $linha["idsituacao"]; ?>";
            var msg = "<?=$idioma['confirma_altera_situacao_atendimento'];?>";
            msg = msg.replace("[[idordemdecompra]]", "<?=$url[3];?>");
            msg = msg.replace("[[nome]]", nome);
            var confirma = confirm(msg);
            if (confirma) {
                document.getElementById('situacao_para').value = para;
                document.getElementById('form_situacao').submit();
            } else {
                return false;
            }
        }
    </script>
    <form method="post" action="#situacao" id="form_situacao">
        <input name="acao" type="hidden" value="alterarSituacao"/>
        <input name="situacao_para" id="situacao_para" type="hidden" value=""/>
    </form>
</fieldset>
<br/>
<br/>

<div class="nav li" style="background-color: rgb(238, 238, 238);">
    <h3><?php echo $linha["nome"]; ?></h3>
    <small><?= $idioma["dia"]; ?><?= formataData($linha["data_cad"], "br", 1); ?> <?= $idioma["por"]; ?> <?php echo ($linha["usuario"]) ; ?></small>
    <br/><br/>
    <?php echo $linha['descricao']; ?>

    <br/><br/>
    <?php if (count($arquivos)) { ?>
        <strong><?= $idioma["arquivo"]; ?></strong>
        <br/>
        <?php foreach ($arquivos as $arquivo) { ?>
            <div id="arquivo<?= $arquivo["idarquivo"]; ?>">
                <span class="icon-file"></span>
                <a href="<?php echo "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/download/" . $arquivo["idarquivo"]; ?>"><?= $arquivo["nome"]; ?>
                    (<?= tamanhoArquivo($arquivo["tamanho"]); ?>)</a>
                <br/>
            </div>
        <?php } ?>
    <?php } ?>
    <br/>
</div>
<?php if($linha['justificativa']){ ?>
        <ul class="nav nav-tabs nav-stacked">
            <li class="li">
                <small>
                    <strong><?= $idioma["dia"]; ?></strong><?= formataData($linha["data_status"], "br", 1); ?>
                    <strong><?= $idioma["por"]; ?></strong><?php echo $linha["usuario_status"]; ?>
                </small><br />
				<h3> <?php
                		if($linha["autorizado_status"] == 'S'){
							echo $idioma["ordemdecompraAutorizado"];
						}elseif($linha["nao_autorizado_status"] == 'S'){
							echo $idioma["ordemdecompraNaoAutorizado"];
						}

				 ?> </h3>
                <br />
                 <small><?=$idioma['justificativa']?></small>
                 <h4><?=$linha['justificativa']?></h4>
                <br/>
                 <small><?=$idioma['motivo']?></small>
                 <h4><?=$linha['motivo']?></h4>
                <br/>
            </li>
        </ul>
<?php } ?>

<?php if (!$linha['idsituacao_status'] and $linha['autorizado'] == 'N' and $linha['nao_autorizado'] == 'N' and $linha['cancelado'] == 'N' and count($array_situacoes) > 0) { ?>
    <br/>
    <strong><?php echo $idioma['responder']; ?></strong>
    <div style="border:#CCC solid 1px;" class="row-fluid" id="formstatus">
        <br/><br/><br/>

        <div class="divCentralizada">
            <strong><?php echo $idioma['tipo_selecao']; ?></strong><br/><br/>

            <div style="float:left;">
                <a href="javascript:void(0);" class="span3 btn" id="respostaAuto" onclick="msg_ativa('autorizar');">
                    <?php echo $idioma['autorizar']; ?>
                </a>
            </div>
            <div style="float:left;">
                <a href="javascript:void(0);" class="span3 btn" id="respostaManu" onclick="msg_ativa('naoautorizar');">
                    <?php echo $idioma['naoautorizar']; ?>
                </a>
            </div>
        </div>
    </div>

    <form name="formautorizar_naoautorizar" method="post" enctype="multipart/form-data" onsubmit="return validaStatusForm();" id="formautorizar_naoautorizar" style="display:none;">
        <div style="border:#CCC solid 1px; padding-bottom:10px;" class="row-fluid">
            <div style="width:90%; padding-left:15px;">
                <br/>
                <h3 id="statustitulo">Autorizar</h3>
                <br/><small><?php echo $idioma['justificativa']; ?></small>
                <br/>
                <textarea name="justificativa" id="justificativa" rows="5" style="width:99%;"></textarea>
                <br/>
                <br/><small><?php echo $idioma['motivo']; ?></small>
                <br/>
                <select id="idmotivo" name="idmotivo">
                    <option value=""><?php echo $idioma['selecione_motivo']; ?></option>
                    <?php foreach ($motivos as $ind => $val) { ?>
                        <option
                            value="<?php echo $val['idmotivo']; ?>"><?php echo $val['nome']; ?></option>
                    <?php } ?>
                </select>
                <br/>
                <input type="hidden" name="acao" value="mudarstatus">
				<input type="hidden" name="qual" id="qual" value="">

                <div style="float:right;"><input type="submit" class="btn btn-primary" name="enviar" value="Enviar"/>
                </div>
            </div>
            <div style="float:right; margin-right:3px;">
                <input type="button" class="btn" id="respostastatus" onclick="msg_ativa('principal');"
                       value="<?php echo $idioma['btn_cancelar']; ?>"/>
            </div>
        </div>
    </form>

<?php } ?>

</div>

</div>
</div>

<div class="span3">
    <div class="box-conteudo">

        <div style="width:100%; overflow:auto; height:300px; border:#CCC solid 1px;">
            <table class="" cellpadding="5" cellspacing="0" width="100%">
                <tr class="linha">
                    <td class="coluna_fim">
                        <h5><?php echo $idioma['titulo_historico']; ?></h5><br/>
                        <strong><?php echo $idioma['ordem_aberto']; ?></strong><br/>
                        <?php echo formataData($linha["data_cad"], 'pt', 1); ?>,
                        <strong><?php echo $idioma['por']; ?></strong> <?php echo ($linha["usuario"]) ; ?>
                    </td>
                </tr>
                <?php foreach ($historicos as $historico) { ?>
                    <tr class="linha">
                        <td class="coluna_fim">
                            <strong>
                                <?php
                                echo $idioma["historico_" . $historico["tipo"]];
                                switch ($historico["tipo"]) {
                                    case "S":
                                        echo "<br /><br />";
                                        if ($historico["status_de"]) {
                                            echo '<span class="status" style="  text-transform: none;background-color:#' . $historico["cor_de"] . '" >' . $historico["status_de"] . '</span> -&gt; ';
                                        }
                                        echo '<span class="status" style="  text-transform: none;background-color:#' . $historico["cor_para"] . '" >' . $historico["status_para"] . '</span><br />';
                                        break;
                                }
                                ?>
                            </strong>
                            <br/>
                            <?php /*echo formataData($historico["data_cad"],'pt',1); ?><?php if($historico["usuario"] || $historico["cliente"]) { ?>, <strong><?php echo $idioma['por']; ?></strong><?php } ?> <?php if($historico["usuario"]) { echo $historico["usuario"]; } elseif($historico["cliente"]) { echo $historico["cliente"]; } */ ?>
                            <?php echo formataData($historico["data_cad"], 'pt', 1); ?>
                            <?php if ($historico["usuario"] || $historico["cliente"] || $historico["usuario_imobiliaria"] || $historico["corretor"]) { ?>,
                                <strong><?php echo $idioma['por']; ?></strong>
                            <?php } ?>
                            <?php
                            if ($historico["usuario"]) {
                                echo $historico["usuario"] . ' (Gestor)';
                            }  ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</div>
<?php incluirLib("rodape", $config, $usuario); ?>
</div>

</body>

<script type="text/javascript">
    var regras = new Array();
    regras.push("formato_arquivo,arquivos[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
</script>

<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>

<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function ($) {
        $("input[name='proxima_acao']").datepicker($.datepicker.regional["pt-BR"]);
    });
</script>

<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script>
    function modificarStatus(situacao, cor) {
        confirma = confirm("<?php echo $idioma["confirma_modificar_situacao"]; ?>");
        if (!confirma)
            return false;
        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: 'Processando solicitação.',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/modificar_status",
                    dataType: "json",
                    type: "POST",
                    data: {modificar_status: situacao},
                    success: function (json) {
                        if (json.sucesso) {
                            altualizaBotoes(json.situacao, json.situacoes_json, cor);
                            self.unblock();
                        } else {
                            alert('<?php echo $idioma['erro_json']; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }

    function altualizaBotoes(situacao, situacoes_json, cor) {

        cores_arr = new Array();
        <?php
        foreach($situacaoWorkflow as $ind => $val){
            ?>
        cores_arr['<?=$ind; ?>'] = '#<?=$val["cor_bg"]; ?>';
        <?php
           }
           ?>

        situacoes_array = Array();
        for (var a = 0; a < situacoes_json.length; a++)
            situacoes_array[a] = situacoes_json[a]['idsituacao_para'];

        var situacoes = document.getElementById('divSituacoes').getElementsByTagName('span');
        var tamanho = (situacoes.length);
        for (var i = 0; i < tamanho; i++) {
            var arr = situacoes[i].id.split('_');
            var novo_id = arr[1];
            if (novo_id != situacao) {
                var id = situacoes[i].id;
                document.getElementById(id).setAttribute("style", "background-color:#666;");
                $("#" + id).removeClass("ativo");
                $("#" + id).addClass("inativo");

                if (situacoes_array.indexOf(novo_id) == -1) {
                    document.getElementById(id).removeAttribute("onClick");
                    document.getElementById(id).setAttribute("rel", "tooltip");
                    document.getElementById(id).setAttribute("style", "background-color:#CCC;");
                    document.getElementById(id).setAttribute("data-original-title", "<?php echo $idioma['indisponivel']; ?>");
                    $("span[rel*=tooltip]").tooltip({ });
                } else {
                    document.getElementById(id).setAttribute("onClick", "modificarStatus('" + novo_id + "','" + cores_arr[novo_id] + "');");
                    document.getElementById(id).removeAttribute("rel");
                    document.getElementById(id).removeAttribute("data-original-title");
                }
            }
        }
        if (situacao != "") {
            var id_charp = "#sit_" + situacao;
            var id_sem = "sit_" + situacao;
            var background = "background-color:" + cor + ";";
            $(id_charp).addClass("ativo");
            document.getElementById(id_sem).setAttribute("style", background);
        }
    }

</script>


</html>
