<?php
function GerarTabela2($dados,$q = null,$idioma,$configuracao = "listagem")
{
    // Buscando os idiomas do formulario
    include(dirname(__FILE__).'/../../idiomas/' . $GLOBALS['config']['idioma_padrao'] . '/index.php');
    echo '<table class="zebra-striped" id="sortTableExample" style="padding: 0; font-size: 9px; border-collapse: collapse;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th style="padding: 4px; line-height: 16px; text-align: left;padding-top: 9px; font-weight: bold; vertical-align: middle;">Filtro</th>';
    echo '<th style="padding: 4px; line-height: 16px; text-align: left;padding-top: 9px; font-weight: bold; vertical-align: middle;">Valor</th>';
    echo '</tr>';
    echo '</thead>';

    $cont = 0;
    foreach($GLOBALS['config']['formulario'] as $ind => $fieldset){
        foreach($fieldset["campos"] as $ind => $campo){
            if($campo["nome"][0] == "q"){
                $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                $campoAux = $_GET["q"][$campoAux];

                if($campo["sql_filtro"]){
                    if($campo["sql_filtro"] == "array"){
                        $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
                        $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
                    } else {
                        $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                        $seleciona = $connection->query($sql);
                        $linha = $seleciona->fetch();
                        $campoAux = $linha[$campo["sql_filtro_label"]];
                    }
                }

            } elseif(is_array($_GET[$campo["nome"]])){

                if($campo["array"]){
                    foreach($_GET[$campo["nome"]] as $ind => $val){
                        $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
                    }
                } elseif($campo["sql_filtro"]){
                    foreach($_GET[$campo["nome"]] as $ind => $val){
                        $sql = str_replace("%",$val,$campo["sql_filtro"]);
                        $seleciona = $connection->query($sql);
                        $linha = $seleciona->fetch();
                        $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                    }
                }

                $campoAux = implode(", ", $_GET[$campo["nome"]]);
            } elseif($campo["sql_filtro"]) {
                if($campo["sql_filtro"] == "array"){
                    $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET[$campo["nome"]]];
                } else {
                    $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                    $seleciona = $connection->query($sql);
                    $linha = $seleciona->fetch();
                    $campoAux = $linha[$campo["sql_filtro_label"]];
                }
            } else {
                $campoAux = $_GET[$campo["nome"]];
            }

            if ($campoAux <> ""){
                if ($cont == 0) {
                    $style = 'background-color: #F4F4F4;';
                    $cont = 1;
                } else {
                    $cont = 0;
                    $style = '';
                }

                echo '<tr style="' . $style . '">';
                echo '<td style="padding: 4px; line-height: 16px; text-align: left;vertical-align: top; border: 1px solid #000;"><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
                echo '<td style="padding: 4px; line-height: 16px; text-align: left;vertical-align: top; border: 1px solid #000;">'.$campoAux.'</td>';
                echo '</tr>';
            }
        }
    }
    echo '</table><br>';
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head',$config,$usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="/assets/js/jquery.1.7.1.min.js"></script>
    <script src="/assets/plugins/facebox/src/facebox.js"></script>
    <script src="/assets/js/validation.js"></script>
    <script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('a[rel*=facebox]').facebox();
        });

        var regras = new Array();
        regras.push("required,nome,Nome obrigatório");
    </script>
</head>
<body style="background-color: #FFF; background-image: none; padding-top: 0px; margin-left: 5px; margin-top: 5px; margin-right: 5px; margin-bottom: 5px; font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
<table width="100%" border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td height="80" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
            <table border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <td><img src="/assets/img/logo_pequena.png"></td>
                </tr>
            </table>
        </td>
        <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
            <h2 style="font-family:Calibri !important; letter-spacing:0;">
                <strong><?= $idioma["pagina_titulo"]; ?></strong>
            </h2>
        </td>
    </tr>
</table>
<?php
if ($mensagem_sucesso) {
    ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert" style="color: #000; text-decoration: none;">×</a>
        <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
    </div>
    <?php
} elseif ($mensagem_erro) {
    ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert" style="color: #000; text-decoration: none;">×</a>
        <strong><?= $idioma[$mensagem_erro]; ?></strong>
    </div>
    <?php
}

if (count($dadosArray['erros']) > 0) {
    ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong>
            <?= $idioma['form_erros']; ?>
        </strong>
        <?php
        foreach($dadosArray['erros'] as $ind => $val) {
            echo '<br />'.$idioma[$val];
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
            <td>
                <?php printf($idioma["informacoes"], $dadosArray["quantidadeLinhas"]); ?>
            </td>
        </tr>
    </table>
    <?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
    <table style="width: 100%;" id="sortTableExample" border="1" cellpadding="5">
        <thead>
        <tr>
            <?php
            foreach($colunas as $ind => $coluna) {
                ?>
                <th bgcolor="#F4F4F4" class=" headerSortReloca">
                    <?= $idioma[$coluna]; ?>
                </th>
                <?php
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($dadosArray as $ind => $linha) {
            //var_dump($ind);
            if(is_int($ind)){
                ?>
                <tr>
                    <?php
                    foreach ($colunas as $ind => $coluna) { ?>
                        <td>
                            <?= $linha[$coluna]; ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php }
        } ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <strong>
                    <?php echo $idioma["total"]; ?>
                </strong>
            </td>
            <td>
                <strong>R$
                    <?= number_format($dadosArray["orcadoTotal"], 2, ",", "."); ?>
                </strong>
            </td>
            <td>
                <strong>R$
                    <?= number_format($dadosArray["metaTotal"], 2, ",", "."); ?>
                </strong>
            </td>
            <td>
                <strong>R$
                    <?= number_format($dadosArray["realizadoTotal"], 2, ",", "."); ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?= number_format($dadosArray["variacaoPorcentoTotal"], 2)." %"; ?>
                </strong>
            </td>
            <td>
                <strong>R$
                    <?= number_format($dadosArray["variacaoRealTotal"], 2, ",", "."); ?>
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
    <br />
    <br />
    <div class="row-fluid">
        <div class="span7" id="grafico1" style="width: 70%; margin: 0 auto;float:left !important;"></div>
        <div class="span5" id="grafico2" style="width: 30%;margin: 0 auto;"></div>
    </div>

    <?php
}
?>
<br />
<!--<table border="1" style="width:100%;">-->
<!--    <tr>-->
<!--        <td align="center">-->
<!--            <img src="/storage/relatorios_obz/grafico_orcado_realizado1.jpg" />-->
<!--        </td>-->
<!--        <td align="center">-->
<!--            <img src="/storage/relatorios_obz/grafico_orcado_realizado2.jpg" />-->
<!--        </td>-->
<!--    </tr>-->
<!--</table>-->
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
    <tr>
        <td valign="top" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
        <td align="right" valign="top" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><div align="right"><a href="/<?= $url[0]; ?>" class="logo" style="color: #000; text-decoration: none;"></a></div></td>
    </tr>
</table>
</body>
</html>
