<?php

$simpleXml = simplexml_load_file(dirname(__FILE__) . '/model.xml');
$simpleXml->versao = '1.00';
$simpleXml->unidade = 'M1I1';
$simpleXml->lote = str_pad($linha['idfechamento'], 6, '0', STR_PAD_LEFT);
$contador = 0;
$formaPagamentoXML = array(
    1 => 'BOL',
    2 => 'CAR',
    3 => 'CAR',
    4 => 'CHQ',
    5 => 'DIN',
    6 => 'DEP',
    7 => 'DEP',
    8 => 'OUT'
);


if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
    $linhaObj->executaSQL("START TRANSACTION;");
}

foreach ($contas_faturas as $conta) {
    if($conta['idescola']){
        if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
            $linhaObj->cadastrarContaCorrenteXML($conta["idconta"], $_GET["idconta_corrente"]);
        }
        $simpleXml->cupomfiscal[$contador] = new StdClass;
        $simpleXml->cupomfiscal[$contador]->aluno->cpf =$conta['escola_documento'];
        $simpleXml->cupomfiscal[$contador]->aluno->matricula = $conta['idescola'];
        $simpleXml->cupomfiscal[$contador]->aluno->curso = 4;

        $simpleXml->cupomfiscal[$contador]->aluno->nome = $conta['escola_razao_social'];
        $simpleXml->cupomfiscal[$contador]->aluno->endereco = $conta['escola_endereco'];
        $simpleXml->cupomfiscal[$contador]->aluno->numero =$conta['escola_numero'];
        $simpleXml->cupomfiscal[$contador]->aluno->complemento =  $conta['escola_complemento'];
        $simpleXml->cupomfiscal[$contador]->aluno->bairro =  $conta['escola_bairro'];
        $simpleXml->cupomfiscal[$contador]->aluno->municipio = $conta['cidade'];

        $simpleXml->cupomfiscal[$contador]->aluno->codmunic = $conta['escola_cidade_codigo'];
        $simpleXml->cupomfiscal[$contador]->aluno->cep = $conta['escola_cep'];
        $simpleXml->cupomfiscal[$contador]->aluno->uf =$conta['escola_sigla'];
        $simpleXml->cupomfiscal[$contador]->aluno->fone =  $conta['escola_telefone'];
        $simpleXml->cupomfiscal[$contador]->aluno->email = $conta['email_escola'];

        $simpleXml->cupomfiscal[$contador]->pagamento->idfatura =  $conta['idconta'];
        $simpleXml->cupomfiscal[$contador]->pagamento->datapgto =  $conta['data_pagamento'];
        $simpleXml->cupomfiscal[$contador]->pagamento->valor = number_format($conta['valor'], 2, '.', '');
        $simpleXml->cupomfiscal[$contador]->pagamento->formapgto = $formaPagamentoXML[$conta['forma_pagamento']];
        $simpleXml->cupomfiscal[$contador]->pagamento->parcela = $conta['parcela'];
        $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = $conta['total_parcelas'];

        $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = number_format($conta['valor'], 2, '.', '');
        $simpleXml->cupomfiscal[$contador]->pagamento->datacontrato =$conta['data_pagamento'];





        $contador++;
    }

}

$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($simpleXml->asXML());

// header('Content-Type: text/plain');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="cf' . date('Ymd', time()) . '.xml"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');

echo $dom->saveXML();

if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
    $linhaObj->executaSQL("COMMIT;");
}
