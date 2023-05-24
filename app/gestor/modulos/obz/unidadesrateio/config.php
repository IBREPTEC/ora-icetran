<?php
$config["link_manual_funcionalidade"] = "";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/usuarios_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "modificar";
$config["acoes"][3] = "remover";

$config["monitoramento"]["onde"] = "240";

// Array de configuração de banco de dados
// (
//     nome da tabela,
//     chave primaria,
//     campos com valores fixos,
//     campos unicos
// )
$config["banco"] = array(
    "tabela" => "obz_unidade_rateio",
    "primaria" => "idunidade",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo" => "'S'"
    )
);