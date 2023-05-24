<?php

function mycallback($str)
{
    list($percent, $label) = explode(' ', $str, 2);
    return sprintf("%s \n (%.1f%%)", $label, $percent);
}

function gerarGraficoOrcadoRealizado1($dados)
{
    require_once '../classes/phplot/phplot.php';
    $plot = new PHPlot();
    $plot->SetTitle("Valores mensais");
    $plot->SetLegend(array(utf8_decode('Meta'), 'Realizado'));
    $plot->SetLegendPosition(0, 1, 'image', 0, 1, 20, -435);
    $plot->SetDataValues($dados);
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');
    $plot->SetYDataLabelPos('plotin');
    $plot->SetPrecisionY(2);
    $plot->SetLineWidths(2);
    $plot->SetMarginsPixels(80, 20, 100, 50);
    $plot->SetNumberFormat(",", ".");
    $dir = $_SERVER["DOCUMENT_ROOT"] . "/storage/relatorios_obz/";
    $filename = "grafico_orcado_realizado1.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true);
    $plot->PHPlot(900,500,$dir.$filename);
    $plot->DrawGraph();
}

function pickcolor($img, $data_array, $row, $col)
{
  $d = $data_array[$row][$col+1]; // col+1 skips over the row's label
  if ($d >= 80) return 0;
  if ($d >= 60) return 1;
  return 2;
}

function gerarGraficoOrcadoRealizado2($dados)
{ 
    require_once '../classes/phplot/phplot.php';

    $plot = new PHPlot();
    $plot->SetImageBorderType('plain');
    $orcado = utf8_decode('Orçado');
    $total_orcado = number_format($dados["total_orcado_ano"], 2, ",", ".");
    $data = array(
                array('Meta total', $dados["metaTotal"]),
                array('Realizado total', null, $dados["realizadoTotal"], null),
                array($orcado. " total", null, null, $dados["total_orcado_ano"])
    );

    $titulo = utf8_decode("Total realizado no período");
    $plot->SetTitle($titulo);
    $plot->SetPlotType('bars');
    $plot->SetDataValues($data);
    $plot->SetYDataLabelPos('plotin');
    $plot->SetPrecisionY(2);
    $plot->SetNumberFormat(",", ".");
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $dir = $_SERVER["DOCUMENT_ROOT"] . "/storage/relatorios_obz/";
    $filename = "grafico_orcado_realizado2.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true);
    $plot->PHPlot(400,500,$dir.$filename);
    $plot->DrawGraph();
}
