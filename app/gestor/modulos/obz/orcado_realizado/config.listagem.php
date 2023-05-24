<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "nome_fantasia",
	"variavel_lang" => "tabela_polo", 
	"tipo" => "banco", 
	"valor" => 'nome_fantasia', 
	"nao_ordenar" => true,
  ),								  
  array(
	"id" => "categoria", 
	"variavel_lang" => "tabela_categoria",
	"tipo" => "banco",
	"valor" => "categoria",
	"nao_ordenar" => true,
  ),
  array(
	"id" => "subcategoria", 
	"variavel_lang" => "tabela_subcategoria",
	"tipo" => "banco",
	"valor" => 'subcategoria',
	"nao_ordenar" => true,
  ),
  array(
	"id" => "memorial", 
	"variavel_lang" => "tabela_memorial",
	"tipo" => "banco",
	"valor" => "memorial",
	"nao_ordenar" => true,
  ),  
  array(
	"id" => "orcado", 
	"variavel_lang" => "tabela_orcado", 
	"tipo" => "banco",
	"valor" => 'orcado',
	"nao_ordenar" => true,
  ) ,	
  array(
	"id" => "realizado", 
	"variavel_lang" => "tabela_realizado", 
	"tipo" => "banco",
	"valor" => 'realizado',
	"nao_ordenar" => true,
  ),
  
  array("id" => "tabela_opcoes", 
		"variavel_lang" => "tabela_opcoes", 
		"tipo" => "php", 
		"valor" => '
		if($linha["nome_fantasia"]){
		return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idorcamento"]."/justificativa\" data-placement=\"left\" rel=\"tooltip facebox\" > ".$idioma["tabela_justificativa"]."</a>";
		}',
		"busca_botao" => true,
		"tamanho"=>"110") 
				
);						   
?>