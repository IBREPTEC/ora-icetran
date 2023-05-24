<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario	
		array(
			"id" => "form_email",
			"nome" => "email_pessoa",
			"nomeidioma" => "form_email",
			"tipo" => "input", 
			"valor" => "email",
			"validacao" => array("required" => "email_vazio"),
			"class" => "span5",
			"legenda" => "@",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
	  	),																					
	  	array(
			"id" => "form_nome",
			"nome" => "nome_pessoa", 
			"nomeidioma" => "form_nome",
			"tipo" => "input",
			"valor" => "nome",
			"validacao" => array("required" => "nome_vazio"), 
			"class" => "span6",
			"banco" => true,
			"banco_string" => true,
	  	),
	  	array(
            "id" => "form_mensagem",
            "nome" => "mensagem",
            "nomeidioma" => "form_mensagem",
            "tipo" => "text", 
            "editor" => true,
            "valor" => "mensagem",
            "class" => "span6",
            "ajudaidioma" => "form_mensagem_ajuda",
            "banco" => false,
            "banco_string" => true
        ),
        array(
            "id" => "form_proxima_acao",
            "nome" => "proxima_acao",
            "nomeidioma" => "form_proxima_acao",
            "tipo" => "input", 
            "valor" => "proxima_acao", 
            "class" => "span2",
            "mascara" => "99/99/9999",
            "datepicker" => true,
            "banco" => false, 
            "banco_php" => 'return formataData("%s", "en", 0)',
            "banco_string" => true                                                          
        ),
	  	array(
			"id" => "form_ativo_painel",
			"nome" => "ativo_painel",
			"nomeidioma" => "form_ativo_painel",
			"tipo" => "select",
			"array" => "ativo", // Array que alimenta o select
			"class" => "span2", 
			"valor" => "ativo_painel",
			"validacao" => array("required" => "ativo_vazio"),
			"ajudaidioma" => "form_ativo_ajuda",
			"banco" => true,
			"banco_string" => true
	  	),																															
	)
  )								  
);
?>