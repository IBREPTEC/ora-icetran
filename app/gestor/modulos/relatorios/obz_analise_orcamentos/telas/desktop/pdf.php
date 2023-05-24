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
                        <td><a href="/<?= $url[0]; ?>" class="logo" style="color: #000; text-decoration: none;"></a></td>
                    </tr>
                </table>
            </td>
            <td align="center" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
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
            <a href="javascript:void(0);" class="close" data-dismiss="alert" style="color: #000; text-decoration: none;">×</a>
            <strong><?= $idioma['form_erros']; ?></strong>
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
                <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><?php printf($idioma["informacoes"], count($dadosArray)); ?></td>
            </tr>
        </table>
        <?php GerarTabela2($dadosArray,$_GET["q"],$idioma); ?>
        <table id="sortTableExample" border="1" cellpadding="5">
            <thead>
                <tr>
                    <?php
                    foreach($colunas as $indCol => $coluna) {
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
                $total['ano_anterior_realizado'] = 0;
                $total['ano_atual_orcado'] = 0;
                foreach ($dadosArray as $ind => $linha) {
                    $total['ano_anterior_realizado'] += $linha['ano_anterior_realizado'];
                    $total['ano_atual_orcado'] += $linha['ano_atual_orcado'];
                    ?>
                    <tr>
                        <?php
                        foreach ($colunas as $indCol => $coluna) {
                            if ($coluna == 'ano_anterior_realizado' || $coluna == 'ano_atual_orcado') {
                                $style = '';
                                if ($coluna == 'ano_atual_orcado') {
                                    if ((float)$linha['ano_atual_orcado'] > (float)$linha['ano_anterior_realizado']) {
                                        $style = 'color:#FF0000;';
                                    } else {
                                        $style = 'color:#006600;';
                                    }
                                }
                                ?>
                                <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                                    <a target="_blank" href="/<?= $url[0]; ?>/obz/orcado_realizado" style="color: #000; text-decoration: none;<?= $style; ?>">
                                        R$ <?= number_format($linha[$coluna], 2, ',', '.'); ?>
                                    </a>
                                </td>
                                <?php
                            } elseif ($coluna == 'variacao_dinheiro') {
                                $valor = $linha['ano_atual_orcado'] - $linha['ano_anterior_realizado'];
                                ?>
                                <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">R$ <?= number_format($valor, 2, ',', '.'); ?></td>
                                <?php
                            } elseif ($coluna == 'variacao_porcentagem') {
                                $diferenca = $linha['ano_atual_orcado'] - $linha['ano_anterior_realizado'];
                                $valor = ($diferenca * 100) / $linha['ano_anterior_realizado'];//Porcentagem variação do orçado x realizado
                                ?>
                                <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><?= number_format($valor, 2, ',', '.'); ?> %</td>
<?php
                            } else {
                                ?>
                                <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><?= $linha[$coluna]; ?></td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }

                $total['variacao_dinheiro'] = $total['ano_atual_orcado'] - $total['ano_anterior_realizado'];//Diferença em R$ total variação do orçado x realizado
                $total['variacao_porcentagem'] = ($total['variacao_dinheiro'] * 100) / $total['ano_anterior_realizado'];//Porcentagem total variação do orçado x realizado

                $totalColspan = 0;
                $temLinha = false;
                foreach ($colunas as $indCol => $coluna) {
                    $totalColspan++;

                    if ($coluna == 'ano_anterior_realizado' || $coluna == 'ano_atual_orcado' || $coluna == 'variacao_dinheiro') {//Se o total for em R$
                        if (!$temLinha) {
                            echo '<tr>';
                        }
                        $temLinha = true;

                        if ($totalColspan > 1) {
                            ?>
                            <td colspan="<?= $totalColspan - 1; ?>" style="text-align:right; font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                                &nbsp;
                            </td>
                            <?php
                        }
                        $totalColspan = 0;
                        ?>
                        <td bgcolor="#E4E4E4" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td>
                                        <strong style="color:#999;">R$</strong>
                                        <strong><?= number_format($total[$coluna], 2, ',', '.'); ?></strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php
                    } elseif ($coluna == 'variacao_porcentagem') {//Se o total for em %
                        if (!$temLinha) {
                            echo '<tr>';
                        }
                        $temLinha = true;

                        if ($totalColspan > 1) {
                            ?>
                            <td colspan="<?= $totalColspan - 1; ?>" style="text-align:right; font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                                &nbsp;
                            </td>
                            <?php
                        }
                        $totalColspan = 0;
                        ?>
                        <td bgcolor="#E4E4E4" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;">
                                        <strong><?= number_format($total[$coluna], 2, ',', '.'); ?></strong>
                                        <strong style="color:#999;">%</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php
                    }
                }

                if ($temLinha) {
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <br />
    <table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
        <tr>
            <td valign="top" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
            <td align="right" valign="top" style="font-family: Verdana, Geneva, sans-serif; font-size: 10px; color: #000;"><div align="right"><a href="/<?= $url[0]; ?>" class="logo" style="color: #000; text-decoration: none;"></a></div></td>
        </tr>
    </table>
</body>
</html>
