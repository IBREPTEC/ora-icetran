<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idperfil",
        "variavel_lang" => "tabela_idperfil",
        "tipo" => "php",
        "coluna_sql" => "idperfil",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idperfil"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idperfil"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80),
    array(
        "id" => "nome", // Id do atributo
        "variavel_lang" => "tabela_nome", // Referencia a variavel de idioma
        "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "evento" => "maxlength='80'",
        "coluna_sql" => "nome", // Nome da coluna no banco de dados
        "valor" => "nome", // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "ativo_painel", // Id do atributo
        "variavel_lang" => "tabela_ativo_painel", // Referencia a variavel de idioma
        "coluna_sql" => "ativo_painel",
        "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "valor" => 'if($linha["ativo_painel"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "ativo",
        "busca_metodo" => 1,
        "tamanho" => "70"
    ), // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
    array(
        "id" => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "coluna_sql" => "data_cad",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "140"
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idperfil"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
?>