<?php
// Array de configuração para a formulario			
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdousuario", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosusuarios", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_nome", // Id do atributo HTML
                "nome" => "nome", // Name do atributo HTML
                "nomeidioma" => "form_nome", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "valor" => "nome", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "nome_vazio"), // Validação do campo
                "class" => "span5", //Class do atributo HTML
                "evento" => "maxlength='80' disabled='disabled'"
            ),	
            array(
                "id" => "form_email",
                "nome" => "email",
                "nomeidioma" => "form_email",
                "tipo" => "input",
                "valor" => "email",
                //"ajudaidioma" => "form_email_ajuda",
                //"validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "evento" => "maxlength='100' disabled='disabled'"
            ),
            array(
                "id" => "gestor_centro_custo",
                "nome" => "gestor_centro_custo",
                "nomeidioma" => "form_gestor_centro_custo",
                "tipo" => "select",
                "valor" => "gestor_centro_custo",
                //"ajudaidioma" => "form_email_ajuda",
                //"validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class" => "span2",
                "array" => "sim_nao",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "comite_obz",
                "nome" => "comite_obz",
                "nomeidioma" => "form_comite_obz",
                "tipo" => "select",
                "valor" => "comite_obz",
                //"ajudaidioma" => "form_email_ajuda",
                //"validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class" => "span2",
                "array" => "sim_nao",
                "banco" => true,
                "banco_string" => true,
            ),
        )
    )
);