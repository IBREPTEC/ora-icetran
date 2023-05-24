<?php
// Array de configuração para a formulario
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto",
	"legendaidioma" => "legendadadosdados",
	"campos" => array(
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
		"id" => "form_linhas",
		"nome" => "linhas",
		"nomeidioma" => "form_linhas",
		"tipo" => "input",
		"valor" => "linhas",
		"evento" => "maxlength='2'",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_colunas",
		"nome" => "colunas",
		"nomeidioma" => "form_colunas",
		"tipo" => "input",
		"valor" => "colunas",
		"evento" => "maxlength='2'",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_top",
		"nome" => "margem_top",
		"nomeidioma" => "form_top",
		"tipo" => "input",
		"valor" => "margem_top",
		"evento" => "maxlength='5'",
		"decimal" => true,
		//"validacao" => array("required" => "nome_vazio"),
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_bottom",
		"nome" => "margem_bottom",
		"nomeidioma" => "form_bottom",
		"tipo" => "input",
		"valor" => "margem_bottom",
		"evento" => "maxlength='5'",
		"decimal" => true,
		//"validacao" => array("required" => "nome_vazio"),
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_left",
		"nome" => "margem_left",
		"nomeidioma" => "form_left",
		"tipo" => "input",
		"valor" => "margem_left",
		"evento" => "maxlength='5'",
		"decimal" => true,
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_right",
		"nome" => "margem_right",
		"nomeidioma" => "form_right",
		"tipo" => "input",
		"valor" => "margem_right",
		"evento" => "maxlength='5'",
		"decimal" => true,
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	array(
		"id" => "form_espaco_linhas",
		"nome" => "espaco_linhas",
		"nomeidioma" => "form_espaco_linhas",
		"tipo" => "input",
		"valor" => "espaco_linhas",
		"evento" => "maxlength='5'",
		"decimal" => true,
		//"validacao" => array("required" => "nome_vazio"),
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	array(
		"id" => "form_espaco_colunas",
		"nome" => "espaco_colunas",
		"nomeidioma" => "form_espaco_colunas",
		"tipo" => "input",
		"valor" => "espaco_colunas",
		"evento" => "maxlength='5'",
		"decimal" => true,
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),

	array(
		"id" => "form_altura",
		"nome" => "altura",
		"nomeidioma" => "form_altura",
		"tipo" => "input",
		"valor" => "altura",
		"evento" => "maxlength='5'",
		"decimal" => true,
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
		"validacao" => array("required" => "altura_vazio")
	  ),

	array(
		"id" => "form_largura",
		"nome" => "largura",
		"nomeidioma" => "form_largura",
		"tipo" => "input",
		"valor" => "largura",
		"evento" => "maxlength='5'",
		"decimal" => true,
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
		"validacao" => array("required" => "largura_vazio")
	  ),
	  /*array(
		"id" => "linha_a_partir",
		"nome" => "linha_a_partir",
		"nomeidioma" => "form_linha_a_partir",
		"tipo" => "input",
		"valor" => "linha_a_partir",
		"evento" => "maxlength='5'",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "coluna_a_partir",
		"nome" => "coluna_a_partir",
		"nomeidioma" => "form_coluna_a_partir",
		"tipo" => "input",
		"valor" => "coluna_a_partir",
		"evento" => "maxlength='5'",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),*/
	  array(
		"id" => "form_etiqueta",
		"nome" => "etiqueta",
		"nomeidioma" => "form_etiqueta",
		"tipo" => "text",
		"editor" => true,
		"valor" => "etiqueta",
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_botao_variaveis_cliente",
		"nome" => "botao_variaveis_cliente",
		"nomeidioma" => "form_botao_variaveis_cliente",
		"tipo" => "php",
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_cliente" => "titulo",
			"variavel_cliente_codigo" => "[[CLIENTE][IDPESSOA]]",
			"variavel_cliente_nome" => "[[CLIENTE][NOME]]",
			"variavel_cliente_cep" => "[[CLIENTE][CEP]]",
			"variavel_cliente_endereco" => "[[CLIENTE][ENDERECO]]",
			"variavel_cliente_bairro" => "[[CLIENTE][BAIRRO]]",
			"variavel_cliente_numero" => "[[CLIENTE][NUMERO]]",
			"variavel_cliente_complemento" => "[[CLIENTE][COMPLEMENTO]]",
			"variavel_cliente_estado" => "[[CLIENTE][ESTADO]]",
			"variavel_cliente_cidade" => "[[CLIENTE][CIDADE]]",
		  )
		),
		"class" => "span4"
	  ),
	  array(
		"id" => "form_botao_variaveis_adicionais", // Id do atributo HTML
		"nome" => "botao_variaveis_adicionais", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_adicionais", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_adicionais" => "titulo",
			"variavel_matricula" => "[[MATRICULA]]",
			"variavel_data_matricula" => "[[DATA_MATRICULA]]",
			"variavel_data_geracao" => "[[DATA_GERACAO_ETIQUETA]]",
		  )
		),
		"class" => "span4" //Class do atributo HTML
	  ),
	)
  )
);