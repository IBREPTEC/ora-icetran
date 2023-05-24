<?php


// Array de configuração para a formulario
$config["formulario_subcategoria"] = array(
    array("fieldsetid"    => "dadosdoobjeto",
          "legendaidioma" => "legendadadosdados",
          "campos"        => array(
//              array(
//                  "id"           => "form_nome",
//                  "nome"         => "nome",
//                  "nomeidioma"   => "form_nome",
//                  "tipo"         => "input",
//                  "valor"        => "nome",
//                  "validacao"    => array("required" => "nome_vazio"),
//                  "class"        => "span6",
//                  "banco"        => true,
//                  "banco_string" => true,
//              ),
//              array(
//                  "id"         => "idcategoria",
//                  "nome"       => "idcategoria",
//                  "nomeidioma" => "form_categoria",
//                  "tipo"       => "select",
//                  "sql"        => "SELECT idcategoria, nome FROM categorias WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
//                  "sql_valor"  => "idcategoria",
//                  "sql_label"  => "nome",
//                  "valor"      => "idcategoria",
//                  "validacao"  => array("required" => "categoria_vazio"),
//                  "banco"      => true
//              ),
//              array(
//                  "id"           => "form_ativo_painel",
//                  "nome"         => "ativo_painel",
//                  "nomeidioma"   => "form_ativo_painel",
//                  "tipo"         => "select",
//                  "array"        => "ativo",
//                  "class"        => "span2",
//                  "valor"        => "ativo_painel",
//                  "validacao"    => array("required" => "ativo_vazio"),
//                  "ajudaidioma"  => "form_ativo_ajuda",
//                  "banco"        => true,
//                  "banco_string" => true
//              ),
              array(
                  "id"         => "form_idunidade",
                  "nome"       => "idunidade",
                  "nomeidioma" => "form_idunidade",
                  "tipo"       => "select",
                  "sql"        => "SELECT
                                    nome,idunidade
                                  FROM
                                    obz_unidade_rateio
                                  WHERE 
                                    ativo = 'S' 
                                  AND ativo_painel = 'S' 
                                  ORDER BY nome", // SQL que alimenta o select
                  "sql_valor"  => "idunidade",
                  "sql_label"  => "nome",
                  "valor"      => "idunidade",
                  //"validacao"  => array("required" => "rateio_vazio"),
                  "banco"      => true,
                  "banco_string" => true
              ),
              array(
                  "id"         => "form_tipo_despesas",
                  "nome"       => "tipo_despesas",
                  "nomeidioma" => "form_tipo_despesas",
                  "tipo"       => "select",
                  "array"      => "tipos_despesas",
                  "valor"      => "tipo_despesas",
                  //"validacao"  => array("required" => "rateio_vazio"),
                  "banco"      => true,
                  "banco_string" => true
              ),
              array(
                  "id"           => "form_idsubcategoria",
                  "nome"         => "idsubcategoria",
                  "nomeidioma"   => "form_idsubcategoria",
                  "tipo"         => "hidden",
                  "valor"        => "return $url[3]",
                  "validacao"    => array("required" => "nome_vazio"),
                  "class"        => "span6",
                  "banco"        => true,
                  "banco_string" => true,
                  )
          )
    )
);