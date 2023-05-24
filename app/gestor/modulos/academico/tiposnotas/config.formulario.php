<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_sigla",
		"nome" => "sigla", 
		"nomeidioma" => "form_sigla",
		"tipo" => "input",
		"valor" => "sigla",
		"validacao" => array("required" => "sigla_vazio"), 
		"class" => "span1",
		"banco" => true,
		"evento" => 'maxlength="5"',
		"banco_string" => true,
	  ),	  
	  /*array(
		"id" => "idsindicato",
		"nome" => "idsindicato",
		"nomeidioma" => "form_sindicato",
		"tipo" => "select",
		"sql" => "select idsindicato, nome_abreviado as nome FROM sindicatos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
		"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idsindicato",
		"validacao" => array("required" => "sindicato_vazio"),
		//"referencia_label" => "cadastro_sindicato",
		//"referencia_link" => "/gestor/cadastros/sindicatos",
		"banco" => true
	  ),*/
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