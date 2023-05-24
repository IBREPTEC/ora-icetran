<?php

$sqlSindicatos = 'select idsindicato, nome_abreviado as nome from sindicatos where ativo = "S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlSindicatos .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';

$sqlPolo = 'SELECT idescola, nome_fantasia as nome FROM escolas where ativo="S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlPolo .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';

//if($_SESSION['adm_gestor_polo'] != 'S'){
//    $sqlPolo .= ' and idpolo in ('.$_SESSION['adm_polos'].')';
//}

$sqlCentroCusto = 'SELECT cc.idcentro_custo, cc.nome FROM centros_custos cc ';
$sqlCentroCusto .= ' where cc.ativo="S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlCentroCusto .= '
//				AND (
//						EXISTS (
//								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = cc.idcentro_custo and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
//						) OR NOT EXISTS (
//								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = cc.idcentro_custo and ativo="S" LIMIT 1
//						)
//					)
//	';
//
//


$sqlCategoria = 'SELECT c.idcategoria, c.nome
					FROM categorias c
					 INNER JOIN categorias_subcategorias cs ON (c.idcategoria = cs.idcategoria)
					WHERE c.ativo="S" and cs.ativo="S" ';
//
//if($_SESSION['adm_gestor_instituicao'] != 'S'){
//	$sqlCategoria .= 'AND (
//						EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
//						) OR NOT EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
//						)
//					)';
//}
$sqlCategoria .= ' GROUP BY c.idcategoria';

$sqlSubCategoria = 'SELECT cs.idsubcategoria, cs.nome
					FROM categorias_subcategorias cs
					WHERE  cs.ativo="S" ';

//if($_SESSION['adm_gestor_instituicao'] != 'S'){
//	$sqlSubCategoria .= 'AND (
//						EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
//						) OR NOT EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
//						)
//					)';
//}



		// Array de configuração para a listagem
		$config["listagem"] = array(
					  		array("id" => "idordemdecompra",
							  	  "variavel_lang" => "tabela_idordemdecompra",
							  	  "tipo" => "php",
							  	  "coluna_sql" => "oc.idordemdecompra",
								  "valor" => '
												$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
												if($diferenca > 24) {
													return "<span title=\"$diferenca\">".$linha["idordemdecompra"]."</span>";
												} else {
													return "<span title=\"$diferenca\">".$linha["idordemdecompra"]."</span> <i class=\"novo\"></i>";
												}
												',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 50),

							array("id" => "titulo",
							  	  "variavel_lang" => "tabela_titulo",
								  "coluna_sql" => "oc.nome",
								  "tipo" => "banco",
								  "valor" => 'nome',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2,
								   "tamanho" => 180),

							/*array("id" => "descricao",
							  	  "variavel_lang" => "tabela_descricao",
							  	  "tipo" => "banco",
								  "coluna_sql" => "oc.descricao",
							  	  "valor" => "descricao",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),*/

						   array("id" => "sindicato",
							  	  "variavel_lang" => "tabela_sindicato",
							  	  "tipo" => "banco",
								  "coluna_sql" => "oc.idsindicato",
							  	  "valor" => "sindicato",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlSindicatos,
								  //"busca_sql" => "SELECT idsindicato,nome FROM sindicatos where ativo='S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1,
								 ),

							array("id" => "escola",
							  	  "variavel_lang" => "tabela_escola",
							  	  "tipo" => "banco",
								  "coluna_sql" => "e.idescola",
							  	  "valor" => "cfc",
								  "busca" => true,
								  "tamanho" => 150,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlPolo, // SQL que alimenta o select
								  "busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),

							array("id" => "centrodecusto",
							  	  "variavel_lang" => "tabela_centrodecusto",
							  	  "tipo" => "banco",
								  "coluna_sql" => "cc.idcentro_custo",
							  	  "valor" => "centrodecusto",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlCentroCusto, // SQL que alimenta o select
								  "busca_sql_valor" => "idcentro_custo", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1,
								  "tamanho" => 180),


							array("id" => "categoria",
							  	  "variavel_lang" => "tabela_categoria",
							  	  "tipo" => "banco",
								  "coluna_sql" => "c.idcategoria",
							  	  "valor" => "categoria",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlCategoria, // SQL que alimenta o select
								  "busca_sql_valor" => "idcategoria", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1,
								  "tamanho" => 180),

							array("id" => "subcategoria",
							  	  "variavel_lang" => "tabela_subcategoria",
							  	  "tipo" => "banco",
								  "coluna_sql" => "sc.idsubcategoria",
							  	  "valor" => "subcategoria",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlSubCategoria, // SQL que alimenta o select
								  "busca_sql_valor" => "idsubcategoria", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1,
								  "tamanho" => 180),


							array("id" => "situacao",
								  "variavel_lang" => "tabela_situacao",
								  "tipo" => "php",
								  "coluna_sql" => "oc.idsituacao",
								  "tamanho" => "120",
								  "valor" => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => "SELECT idsituacao, nome FROM obz_ordem_workflow WHERE ativo = 'S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),

  						    array("id" => "valor",
								  "variavel_lang" => "tabela_valor",
								  "coluna_sql" => "oc.valor",
								  "tipo" => "php",
								  "valor" => 'if($linha["valor"]) { return number_format($linha["valor"],2,",","."); } else { return "-"; }',
								  "busca" => true,
								  "tamanho" => "80",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 4),

  						    array("id" => "data_cad",
								  "variavel_lang" => "tabela_datacad",
								  "coluna_sql" => "oc.data_cad",
								  "tipo" => "php",
								  "valor" => 'if($linha["data_cad"]) { return formataData($linha["data_cad"],"br",0); } else { return "-"; }',
								  "busca" => true,
								  "tamanho" => "80",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3),

							array("id" => "opcoes",
								  "variavel_lang" => "tabela_opcoes",
								  "tipo" => "php",
								  "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idordemdecompra"]."/visualiza\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_abrir"]."</a>"',
								  "busca_botao" => true,
								  "tamanho" => "100")

						   );
?>
