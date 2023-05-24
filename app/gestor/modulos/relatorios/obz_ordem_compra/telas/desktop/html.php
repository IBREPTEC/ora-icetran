<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head',$config,$usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body, td, th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10px;
            color: #000;
        }
        
        body {
            background-color: #FFF;
            background-image: none;
            padding-top: 0px;
            margin-left: 5px;
            margin-top: 5px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        a:link {
            color: #000;
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
            color: #000;
        }

        a:hover {
            text-decoration: underline;
            color: #000;
        }

        a:active {
            text-decoration: none;
            color: #000;
        }

        table.zebra-striped {
            padding: 0;
            font-size: 9px;
            border-collapse: collapse;
        }

        table.zebra-striped th, table td {
            padding: 4px;
            line-height: 16px;
            text-align: left;
        }

        table.zebra-striped th {
            padding-top: 9px;
            font-weight: bold;
            vertical-align: middle;
        }

        table.zebra-striped td {
            vertical-align: top;
            border: 1px solid #000;
        }

        table.zebra-striped tbody th {
            border-top: 1px solid #ddd;
            vertical-align: top;
        }

        .zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
            background-color: #F4F4F4;
        }

        .zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
            background-color: #E4E4E4;
        }
    </style>
    <style type="text/css" media="print">
        body, td, th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 9px;
            color: #000;
        }
        .impressao {
            display: none;
        }
    </style>
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
<body>
    <table width="100%" border="0" cellpadding="10" cellspacing="0">
        <tr>
            <td height="80">
                <table border="0" cellspacing="0" cellpadding="8">
                    <tr>
                        <td><a href="/<?= $url[0]; ?>" class="logo"></a></td>
                    </tr>
                </table>
            </td>
            <td align="center">
                <h2>
                    <strong><?= $idioma["pagina_titulo"]; ?></strong>
                </h2>
            </td>
            <td  align="right" valign="top">
                <table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
                    <tr>
                        <td>
                            <img src="/assets/img/print_24x24.png" width="24" height="24">
                        </td>
                        <td>
                            <a href="javascript:window.print();"><?= $idioma["imprimir"]; ?></a>
                        </td>
                        <td>
                            <a class="btn" href="#link_salvar" rel="facebox" ><?= $idioma['salvar_relatorio'] ?></a>
                            <div id="link_salvar" style="display:none;"> 
                                <div style="width:300px;">
                                    <form method="post" onSubmit="return validateFields(this, regras)">
                                        <input type="hidden" name="acao" value="salvar_relatorio" />
                                        <label for="nome"><strong><?= $idioma['tabela_nome']; ?>:</strong></label>
                                        <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                                        <input type="submit" class="btn" value="<?= $idioma['salvar_relatorio'] ?>" />
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?= $_SERVER["QUERY_STRING"]; ?>">
                                <input class="btn" type="submit" value="<?= $idioma['baixar_planilha'] ?>" />
                            </form>
                        </td>

                        <td>
                            <a rel="facebox" class="btn" href="#iframe_email"><?= $idioma['enviar_email'] ?></a>
                            <div style="display:none;" id="iframe_email">
                                <iframe frameborder="0" width="600" height="200" src="/<?php echo $url[0]."/".$url[1]."/".$url[2]."/enviar_email/?{$_SERVER["QUERY_STRING"]}" ?>"></iframe>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php
    if ($mensagem_sucesso) {
        ?>
        <div class="alert alert-success fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
        </div>
        <?php
    } elseif ($mensagem_erro) {
        ?>
        <div class="alert alert-error fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$mensagem_erro]; ?></strong>
        </div>
        <?php
    }

     if ($_POST['msg']) {
        ?>
        <div class="alert alert-success fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$_POST['msg']]; ?></strong>
        </div>
        <?php
    }

    if (count($dadosArray['erros']) > 0) {
        ?>
        <div class="alert alert-error fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
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
                <td><?php printf($idioma["informacoes"], count($dadosArray)); ?></td>
            </tr>
        </table>
        <?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
        <table id="sortTableExample" border="1" cellpadding="5">
            <thead>
                <tr>
                    <?php 
                    foreach($colunas as $indCol => $coluna) { 
                        ?>
                        <th bgcolor="#F4F4F4" class=" headerSortReloca">
                            <?php if($coluna == "ano_atual_orcado"){
                                    if(!$dadosArray["ano"]){
                                        echo substr($_GET["de"], 6, 9);
                                    }else{
                                        echo $dadosArray["ano"];
                                    }
                                  }elseif ($coluna == "ano_anterior_realizado") {
                                      if(!$dadosArray["ano"]){
                                         echo substr($_GET["de"], 6, 9) - 1;
                                      }else{
                                        echo $dadosArray["anoAnterior"];
                                      }
                                  }else{
                                      echo $idioma[$coluna];
                                    } ?>
                        </th>
                        <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($dadosArray as $ind => $linha) {
                    if(!is_int($ind)){ continue; }

                    
                    ?>  
                    <tr>
                        <?php
                        foreach ($colunas as $indCol => $coluna) {
                            if ($coluna == 'valor') {
                                
                                ?>
                                <td>
                                    R$ <?= number_format($linha[$coluna], 2, ',', '.'); ?>
                                </td>
                                <?php
                            } else if($coluna == 'anexo') {
                                if($linha[$coluna]){ ?>
                                    <td>Sim</td>
                                <?php }else{ ?> 
                                    <td>Não</td>
                            <?php    } 
                             } else {
                                ?>
                                <td><?= $linha[$coluna]; ?></td>
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
                            <td colspan="<?= $totalColspan - 1; ?>" style="text-align:right;">&nbsp;
                                
                            </td>
                            <?php
                        }
                        $totalColspan = 0;
                        ?>
                        <td bgcolor="#E4E4E4">
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
                            <td colspan="<?= $totalColspan - 1; ?>" style="text-align:right;">&nbsp;
                                
                            </td>
                            <?php
                        }
                        $totalColspan = 0;
                        ?>
                        <td bgcolor="#E4E4E4">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td>
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
            <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
            <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div></td>
        </tr>
    </table>
    <script src="/assets/min/aplicacao.desktop.min.js"></script>
</body>
</html>
