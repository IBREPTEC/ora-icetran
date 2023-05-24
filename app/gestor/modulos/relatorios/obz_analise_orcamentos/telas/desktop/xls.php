<?php
//error_reporting(E_ALL);
date_default_timezone_set('America/Recife');

//$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
//$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
//$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;
$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;


/**  PhpSpreadsheet */

//$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
require_once("../classes/phpexcel/classes/PHPExcel/IOFactory.php");

$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheet->setCellValue('A6', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$letras = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
				"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
				"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
				"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
				"EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
				"FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
				"GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
				"HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ");

//Foreach para montar cabeçalho das colunas
$contador = -1;
foreach ($colunas as $indCol => $coluna) {

    if ($coluna == 'ano_atual_orcado') {
        if (!$dadosArray['ano']) {
            $idioma[$coluna] = substr($_GET['de'], 6, 9);
        } else {
            $idioma[$coluna] = $dadosArray['ano'];
        }
    } elseif ($coluna == 'ano_anterior_realizado') {
        if (!$dadosArray['ano']) {
            $idioma[$coluna] = substr($_GET['de'], 6, 9) - 1;
        } else {
            $idioma[$coluna] = $dadosArray['anoAnterior'];
        }
    }

	$contador++;
	$sheet->setCellValue($letras[$contador].'2', $idioma[$coluna]);
    $sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

}

$sheet->mergeCells('A1:'.$letras[$contador].'1');
$sheet->mergeCells('A6:'.$letras[$contador].'6');

$linhaBase = 4;
if (count($dadosArray) > 0) {
	$sheet->insertNewRowBefore($linhaBase,count($dadosArray));

	$total['ano_anterior_realizado'] = 0;
    $total['ano_atual_orcado'] = 0;
	foreach ($dadosArray as $ind => $dados) {
        if (!is_numeric($ind)) {
            break;
        }

        $total['ano_anterior_realizado'] += $dados['ano_anterior_realizado'];
        $total['ano_atual_orcado'] += $dados['ano_atual_orcado'];

		$linha = $linhaBase + $ind;


        $contador = -1;
		foreach ($colunas as $indCol => $coluna) {
			$contador++;

			if ($coluna == 'ano_anterior_realizado' || $coluna == 'ano_atual_orcado') {
				$valor = 'R$ ' . number_format($dados[$coluna], 2, ',', '.');
            } elseif ($coluna == 'variacao_dinheiro') {
                $valor = $dados['ano_atual_orcado'] - $dados['ano_anterior_realizado'];
                $valor = 'R$ ' . number_format($valor, 2, ',', '.');
            } elseif ($coluna == 'variacao_porcentagem') {

                $diferenca = $dados['ano_atual_orcado'] - $dados['ano_anterior_realizado'];
                if($diferenca > 0 && $dados['ano_anterior_realizado'] > 0){

                    $valor = ($diferenca * 100) / $dados['ano_anterior_realizado'];//Porcentagem variação do orçado x realizado
                    $valor = number_format($valor, 2, ',', '.') . ' %';
                }
                else{
                    $valor = 0;
                    $valor = number_format($valor, 2, ',', '.') . ' %';

                }

            } else {
                $valor = $dados[$coluna];
            }

            $sheet->setCellValue($letras[$contador].$linha, $valor);
		}
	}

	$total['variacao_dinheiro'] = $total['ano_atual_orcado'] - $total['ano_anterior_realizado'];//Diferença em R$ total variação do orçado x realizado

    if($total['ano_anterior_realizado'] > 0) {
        $total['variacao_porcentagem'] = ($total['variacao_dinheiro'] * 100) / $total['ano_anterior_realizado'];//Porcentagem total variação do orçado x realizado
    }
    else{
        $total['variacao_porcentagem'] = 0.0;
    }
    $linha++;
    $contador = -1;
    foreach ($colunas as $indCol => $coluna) {
    	$contador++;

        if ($coluna == 'ano_anterior_realizado' || $coluna == 'ano_atual_orcado' || $coluna == 'variacao_dinheiro') {//Se o total for em R$
        	$valor = 'R$ ' . number_format($total[$coluna], 2, ',', '.');
        	$sheet->setCellValue($letras[$contador].$linha, $valor);
        } elseif ($coluna == 'variacao_porcentagem') {//Se o total for em %
        	$valor = number_format($total[$coluna], 2, ',', '.') . ' %';
        	$sheet->setCellValue($letras[$contador].$linha, $valor);
        }
    }
}

$sheet->removeRow($linhaBase-1,1);

//$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
