<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "associar_usuario";

$config["monitoramento"]["onde"] = "295";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela"             => "centros_custos",
    "primaria"           => "idcentro_custo",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo"    => "'S'"
    ),
    "campos_unicos"      => array(
        array(
            "campo_banco" => "nome",
            "campo_form"  => "nome",
            "erro_idioma" => "nome_utilizado"
        )
    ),
    /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */
);
?>