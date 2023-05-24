<?php		   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdocliente", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdadosdocliente", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
		array(
			"id" => "idsituacao",
			"nome" => "idsituacao",
			"nomeidioma" => "idsituacao",
			"tipo" => "input",
			"valor" => "1",
			"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
			"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados) 
			), 
		array(
			"id" => "idsindicato",
			"nome" => "idsindicato",
			"nomeidioma" => "idsindicato",
			"tipo" => "input", 
			"valor" => "idsindicato",
			"validacao" => array("required" => "instituicao_vazio"),
			"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
			"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados) 
	  ), 
	  array(
			"id" => "idescola",
			"nome" => "idescola",
			"nomeidioma" => "idescola",
			"tipo" => "input",
			"valor" => "idescola",
			"validacao" => array("required" => "polo_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "idcentro_custo",
			"nome" => "idcentro_custo", 
			"nomeidioma" => "idcentro_custo",
			"tipo" => "input",
			"valor" => "idcentro_custo",
			"validacao" => array("required" => "centro_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "idcategoria",
			"nome" => "idcategoria", 
			"nomeidioma" => "idcategoria",
			"tipo" => "input",
			"valor" => "idcategoria",
			"validacao" => array("required" => "categoria_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "idsubcategoria",
			"nome" => "idsubcategoria", 
			"nomeidioma" => "idsubcategoria",
			"tipo" => "input",
			"valor" => "idsubcategoria",
			"validacao" => array("required" => "subcategoria_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "nome",
			"nome" => "nome", 
			"nomeidioma" => "nome",
			"tipo" => "input",
			"valor" => "nome",
			"validacao" => array("required" => "titulo_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "valor",
			"nome" => "valor", 
			"nomeidioma" => "valor",
			"tipo" => "input",
			"valor" => "valor",
			"decimal" => true,
			"validacao" => array("required" => "valor_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "descricao",
			"nome" => "descricao", 
			"nomeidioma" => "descricao",
			"tipo" => "input",
			"valor" => "descricao",
			"validacao" => array("required" => "descricao_vazio"), 
			"banco" => true,
			"banco_string" => true
			),
	)
  )
);

?>