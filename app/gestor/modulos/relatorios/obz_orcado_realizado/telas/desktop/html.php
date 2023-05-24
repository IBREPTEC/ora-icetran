<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <?php incluirLib('head',$config,$usuario); ?>
    <script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
    <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
      body,
      td,
      th {
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

      table.zebra-striped th,
      table td {
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

      .zebra-striped tbody tr:nth-child(odd) td,
      .zebra-striped tbody tr:nth-child(odd) th {
        background-color: #F4F4F4;
      }

      .zebra-striped tbody tr:hover td,
      .zebra-striped tbody tr:hover th {
        background-color: #E4E4E4;
      }
    </style>
    <style type="text/css" media="print">
      body,
      td,
      th {
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
      $(document).ready(function() {
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
            <td>
              <a href="/<?= $url[0]; ?>" class="logo"></a>
            </td>
          </tr>
        </table>
      </td>
      <td align="center">
        <h2>
          <strong>
            <?= $idioma["pagina_titulo"]; ?>
          </strong>
        </h2>
      </td>
      <td align="right" valign="top">
        <table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
          <tr>
            <td>
              <img src="/assets/img/print_24x24.png" width="24" height="24">
            </td>
            <td>
              <a href="javascript:window.print();">
                <?= $idioma["imprimir"]; ?>
              </a>
            </td>
            <td>
              <a class="btn" href="#link_salvar" rel="facebox">
                <?= $idioma['salvar_relatorio'] ?>
              </a>
              <div id="link_salvar" style="display:none;">
                <div style="width:300px;">
                  <form method="post" onSubmit="return validateFields(this, regras)">
                    <input type="hidden" name="acao" value="salvar_relatorio" />
                    <label for="nome">
                      <strong>
                        <?= $idioma['tabela_nome']; ?>:</strong>
                    </label>
                    <input type="text" class="input" name="nome" id="nome" style="height:30px;" />
                    <br />
                    <br />
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
      <strong>
        <?= $idioma[$mensagem_sucesso]; ?>
      </strong>
    </div>
    <?php
    } elseif ($mensagem_erro) {
        ?>
      <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong>
          <?= $idioma[$mensagem_erro]; ?>
        </strong>
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
          <?php //print_r2($dadosArray);print_r2($_GET["q"]);exit; ?>
          <?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
            <table style="width: 100%;" id="sortTableExample" border="1" cellpadding="5">
              <thead>
                <tr>
                  <?php
                    foreach($colunas as $ind => $coluna) {
                        ?>
					<?php if($coluna!='justificativa_texto' or $dadosArray["liberaJustificativa"]){ ?>
                            <th bgcolor="#F4F4F4" class=" headerSortReloca" <?php if($coluna=='justificativa_texto'){ ?> width="165"<?php } ?>>
                                    <?= $idioma[$coluna]; ?>
                            </th>
                    <?php } ?>
                    <?php
                    }
                    ?>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($dadosArray as $ind => $linha) {
                    //var_dump($dadosArray);exit;
                    if(is_int($ind)){
                    ?>
                  <tr>
                    <?php
                        foreach ($colunas as $ind => $coluna) { ?>
                       		 <?php if($coluna!='justificativa_texto' or $dadosArray["liberaJustificativa"]){ ?>
                                      <td <?php if($coluna=='justificativa_texto'){ ?> width="50"<?php } ?> <?php if($coluna == 'variacao_real' || $coluna == 'variacao_porcento'){ echo "style='color:{$linha["corVariacao"]}'"; }?> >
                                        
                                      <?php if($coluna == "realizado" && $config['modulo_obz']){ 
                                                echo "<a target='_blank' href='/gestor/obz/orcado_realizado?idsindicato={$linha["idsindicato"]}&idcentro_custo={$linha["idcentro_custo"]}&ano={$linha["ano"]}&idcategoria={$linha["idcategoria"]}&idsubcategoria={$linha["idsubcategoria"]}'>$linha[$coluna]</a>";
                                            }else{
                                                echo $linha[$coluna];
                                            } ?>
                                      </td>
                                <?php } ?>
                      <?php } ?>
                  </tr>
                  <?php }
                    } ?>
                   <?php if($dadosArray["quantidadeLinhas"]){ ?>
                    <tr>
                      <td></td>
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
              <table border="1" style="width:100%;">
                <tr>
                  <td align="center">
                    <img src="/storage/relatorios_obz/grafico_orcado_realizado1.jpg" />
                  </td>
                  <td align="center">
                    <img src="/storage/relatorios_obz/grafico_orcado_realizado2.jpg" />
                  </td>
                </tr>
              </table>
                  <?php
                        }else{ ?>
            <td colspan="12">
               Nenhuma informação encontrada.
            </td>
            <?php }
                    }
                    ?>
              <br />
              <table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
                <tr>
                  <td valign="top">
                    <span style="color:#999999;">
                      <?= $idioma["rodape"]; ?>
                    </span>
                  </td>
                  <td align="right" valign="top">
                    <div align="right">
                      <a href="/<?= $url[0]; ?>" class="logo"></a>
                    </div>
                  </td>
                </tr>
              </table>
              <script>
                function number_format(number, decimals, dec_point, thousands_sep) {
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

                  var n = number,
                    prec = decimals;
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

                    _[0] = s.slice(0, i + (n < 0)) +
                      _[0].slice(i).replace(/(\d{3})/g, sep + '$1');

                    s = _.join(dec);
                  } else {
                    s = s.replace('.', dec);
                  }

                  return s;
                }
              </script>
</body>

</html>
