<?php

error_reporting(E_ALL);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT'] . '/' . $url[0] . '/modulos/' . $url[1] . '/' . $url[2] . '/telas/' . $config['tela_padrao'] . '/' . 'xls_' . $config['idioma_padrao'] . '.xls';
$nome_arquivo = $url[1] . '_' . $url[2] . '_' . time() . '.xls';
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT'] . '/storage/temp/' . $nome_arquivo;

require_once '../classes/phpexcel/classes/PHPExcel/IOFactory.php';
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheet->setCellValue('A5', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$linhaBase = 4;
if (count($dadosArray) > 0){
	$sheet->insertNewRowBefore($linhaBase,count($dadosArray));

	foreach ($dadosArray as $ind => $dados){
		$linha = $linhaBase + $ind;

		$sheet->setCellValue('A' . $linha, $dados['idescola']);
		$sheet->setCellValue('B' . $linha, $dados['nome_fantasia']);
		$sheet->setCellValue('C' . $linha, $dados['razao_social']);
		$sheet->setCellValue('D' . $linha, $dados['documento']);
		$sheet->setCellValue('E' . $linha, $dados['fax']);
		$sheet->setCellValue('F' . $linha, $dados['telefone']);
		$sheet->setCellValue('G' . $linha, $dados['celular_administrador']);
		$sheet->setCellValue('H' . $linha, $dados['email']);
		$sheet->setCellValue('I' . $linha, $dados['estado']);
		$sheet->setCellValue('J' . $linha, $dados['cep']);
		$sheet->setCellValue('K' . $linha, $dados['logradouro']);
		$sheet->setCellValue('L' . $linha, $dados['endereco']);
		$sheet->setCellValue('M' . $linha, $dados['bairro']);
		$sheet->setCellValue('N' . $linha, $dados['numero']);
		$sheet->setCellValue('O' . $linha, $dados['complemento']);
		$sheet->setCellValue('P' . $linha, $dados['cidade']);
		$sheet->setCellValue('Q' . $linha, $dados['sindicato']);
        $sheet->setCellValue('R' . $linha, $dados['criar_matricula']);
	}
}

$sheet->removeRow($linhaBase-1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);


header('Content-type: ' . filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="' . basename($nome_arquivo) . '"');
header('Content-Length: ' . filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
