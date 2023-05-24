<?php
// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_nome",
                "nome"         => "nome",
                "nomeidioma"   => "form_nome",
                "tipo"         => "input",
                "valor"        => "nome",
                "validacao"    => array("required" => "nome_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_descricao",
                "nome"         => "descricao",
                "nomeidioma"   => "form_descricao",
                "tipo"         => "input",
                "valor"        => "descricao",
                "validacao"    => array("required" => "descricao_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
             array(
                "id"           => "form_ativo_painel",
                "nome"         => "ativo_painel",
                "nomeidioma"   => "form_ativo_painel",
                "tipo"         => "select",
                "array"        => "sim_nao", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "ativo_painel",
                "validacao"    => array("required" => "painel_vazio"),
                "banco"        => true,
                "banco_string" => true
            ),
        )
    ),   
);
?>