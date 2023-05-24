<?php
$config["link_manual_funcionalidade"] = "";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/usuarios_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "modificar";

$config["monitoramento"]["onde"] = "238";

// Array de configuração de banco de dados
// (
//     nome da tabela,
//     chave primaria,
//     campos com valores fixos,
//     campos unicos
// )
$config["banco"] = array(
    "tabela" => "obz_usuarios_adm",
    "primaria" => "idusuario",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "idusuario" => $url[3]
    )
);