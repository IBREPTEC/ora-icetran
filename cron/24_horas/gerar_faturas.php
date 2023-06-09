<?php

include_once('../../app/classes/escolas.class.php');
include_once('../../app/classes/contas.class.php');

$escolaObj = new Escolas();

$escolaObj->Set('campos', 'e.idsindicato, e.idescola, e.parceiro, e.periodo_faturas');
$escolaObj->Set('ordem_campo', 'e.idescola');
$escolaObj->Set('ordem', 'ASC');
$escolaObj->Set('limite', -1);
$escolas = $escolaObj->listarTodas();
foreach ($escolas as $ind => $escola)
{
    if ($escola['parceiro'] === 'S')
    {
        $contasObj = new Contas();
        $optantePlano = $contasObj->retornarOptantePlano($escola['idescola']);

        if ($periodo_fatura["pt_br"][$escola['periodo_faturas']] === 'Semanal' && $diaDaSemana === 1)
        {
            $contasObj->gerarFatura($escola['idescola'], $escola['idsindicato']);
            if($optantePlano['idplano'] == 2){
                $contasObj->gerarFaturaAulaRemota($escola['idescola'],$escola['idsindicato'],$optantePlano["valor_minimo"]);

            }


        }
        if ($periodo_fatura["pt_br"][$escola['periodo_faturas']] === 'Mensal' && $mes === 01)
        {
            $contasObj->gerarFatura($escola['idescola'], $escola['idsindicato']);
            if($optantePlano['idplano'] == 2){
                $contasObj->gerarFaturaAulaRemota($escola['idescola'],$escola['idsindicato'],$optantePlano["valor_minimo"]);

            }
        }
    }
}
