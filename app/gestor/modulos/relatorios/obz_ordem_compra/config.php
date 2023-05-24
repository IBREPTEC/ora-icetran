<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlInstituicao = 'SELECT idsindicato, nome_abreviado FROM sindicatos WHERE ativo = "S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlInstituicao .= ' AND idsindicato IN ('.$_SESSION['adm_sindicatos'].')';
//$sqlInstituicao .= ' ORDER BY nome_abreviado';

$sqlSit = "SELECT idsituacao,nome FROM obz_ordem_workflow WHERE ativo = 'S' ";

// Array de configuração para a formulario
$config['formulario'] = array(
                            array(
                                'fieldsetid' => 'dadosdoobjeto',
                                'legendaidioma' => 'legendadadosdados',
                                'campos' => array(
                                                array(
                                                    'id' => 'form_tipo_data_filtro',
                                                    'nome' => 'vencimento_conta',
                                                    'tipo' => 'select',
                                                    'array' => 'tipo_data_filtro',
                                                    'class' => 'span3',
                                                    'valor' => 'tipo_data_filtro',
                                                    'banco' => true,
                                                    'iddiv' => 'de',
                                                    'iddiv2' => 'ate',
                                                    'iddivs' => array(
                                                                    'de',
                                                                    'ate'
                                                                ),
                                                    'iddiv_obr' => true,
                                                    'iddiv2_obr' => true,
                                                    'validacao' => array(
                                                                        'required' => 'tipo_data_filtro_vazio'
                                                                    ),
                                                    'nomeidioma' => 'form_tipo_data_filtro',
                                                    'ajudaidioma' => 'form_tipo_data_filtro_ajuda',
                                                    'botao_hide' => true,
                                                    'sql_filtro' => 'array',
                                                    'banco_string' => true,
                                                    'sql_filtro_label' => 'tipo_data_filtro'
                                                ),

                                                array(
                                                    'id' => 'form_de',
                                                    'nome' => 'de',
                                                    'valor' => 'de',
                                                    'tipo' => 'input',
                                                    'class' => 'span2',
                                                    'nomeidioma' => 'form_de',
                                                    "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                                                    "validacao" => array("required" => "de_vazio"),
                                                    'mascara' => '99/99/9999',
                                                    'datepicker' => true,
                                                    'input_hidden' => true
                                                ),

                                                array(
                                                    'id' => 'form_ate',
                                                    'nome' => 'ate',
                                                    'valor' => 'ate',
                                                    'tipo' => 'input',
                                                    'class' => 'span2',
                                                    'nomeidioma' => 'form_ate',
                                                    "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                                                    "validacao" => array("required" => "ate_vazio"),
                                                    'mascara' => '99/99/9999',
                                                    'datepicker' => true,
                                                    'input_hidden' => true
                                                ),

                                                array(
                                                    'id' => 'idsindicato',
                                                    'sql' => $sqlInstituicao,
                                                    'nome' => 'q[1|i.idsindicato]',
                                                    'tipo' => 'select',
                                                    'valor' => 'idsindicato',
                                                    'class' => 'span5',
                                                    'sql_valor' => 'idsindicato',
                                                    'sql_label' => 'nome_abreviado',
                                                    'validacao' => array('required' => 'idsindicato_vazio'),
                                                    'nomeidioma' => 'form_instituicao_filtro',
                                                    'sql_filtro' => 'SELECT * FROM sindicatos WHERE ativo = "S" AND idsindicato = %',
                                                    'sql_filtro_label' => 'nome_abreviado'
                                                ),

                                                array(
                                                    'id' => 'idescola',
                                                    'nome' => 'q[1|ooc.idescola]',
                                                    'nomeidioma' => 'form_polo',
                                                    'tipo' => 'select',
                                                    'valor' => 'idescola',
                                                    'class' => 'span5',
                                                    'json' => true,
                                                    'json_idpai' => 'idsindicato',
                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_polos/',
                                                    'json_input_pai_vazio' => 'form_selecione_idsindicato',
                                                    'json_input_vazio' => 'form_selecione_idpolo',
                                                    'json_campo_exibir' => 'nome',
                                                    'sql_filtro' => 'SELECT * FROM escolas WHERE ativo = "S" AND idescola = %',
                                                    'sql_filtro_label' => 'nome_fantasia'
                                                ),

                                                array(
                                                    'id' => 'idcentro_custo',
                                                    'nome' => 'q[1|ooc.idcentro_custo]',
                                                    'nomeidioma' => 'form_idcentro_custo',
                                                    'tipo' => 'select',
                                                    'valor' => 'idcentro_custo',
                                                    'class' => 'span5',
                                                    'banco' => true,
                                                    'json' => true,
                                                    'json_idpai' => 'idsindicato',
                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_centro_custos/',
                                                    'json_input_pai_vazio' => 'form_selecione_idsindicato',
                                                    'json_input_vazio' => 'form_selecione_idcentro_custo',
                                                    'json_campo_exibir' => 'nome',
                                                    'sql_filtro' => 'SELECT * FROM centros_custos WHERE ativo = "S" AND idcentro_custo = %',
                                                    'sql_filtro_label' => 'nome'
                                                ),
                                                
                                                array(
                                                    'id' => 'idcategoria',
                                                    'nome' => 'q[1|ooc.idcategoria]',
                                                    'nomeidioma' => 'form_idcategoria',
                                                    'tipo' => 'select',
                                                    'valor' => 'idcategoria',
                                                    //'validacao' => array('required' => 'idcategoria_vazio'),
                                                    'sql_filtro' => 'SELECT * FROM categorias WHERE ativo = "S" AND idcategoria = %',
                                                    'sql_filtro_label' => 'nome'
                                                ),

                                                array(
                                                    'id' => 'idsubcategoria',
                                                    'nome' => 'q[1|ooc.idsubcategoria]',
                                                    'nomeidioma' => 'form_idsubcategoria',
                                                    'tipo' => 'select',
                                                    'valor' => 'idsubcategoria',
                                                    //'validacao' => array('required' => 'idsubcategoria_vazio'),
                                                    'sql_filtro' => 'SELECT * FROM categorias_subcategorias WHERE ativo = "S" AND idsubcategoria = %',
                                                    'sql_filtro_label' => 'nome'
                                                ),
                                    
                                                array(
                                                    "id"           => "form_anexo",
                                                    "nome"         => "anexo",
                                                    "nomeidioma"   => "form_anexo",
                                                    "tipo"         => "select",
                                                    "array"        => "sim_nao", // Array que alimenta o select
                                                    "class"        => "span2",
                                                    "valor"        => "ativo_painel",
                                                    //"validacao"    => array("required" => "ativo_vazio"),
                                                    "ajudaidioma"  => "form_ativo_ajuda",
                                                    'sql_filtro' => 'array',
                                                    'sql_filtro_label' => 'sim_nao'
                                                ),
                                    
                                                array(
                                                    'id' => 'idsituacao',
                                                    'sql' => $sqlSit,
                                                    'nome' => 'q[1|ooc.idsituacao]',
                                                    'tipo' => 'select',
                                                    'valor' => 'idsituacao',
                                                    'class' => 'span5',
                                                    'sql_valor' => 'idsituacao',
                                                    'sql_label' => 'nome',
                                                    //'validacao' => array('required' => 'idsindicato_vazio'),
                                                    'nomeidioma' => 'form_idsituacao',
                                                    'sql_filtro' => 'SELECT * FROM obz_ordem_workflow WHERE ativo = "S" AND idsituacao = %',
                                                    'sql_filtro_label' => 'nome'
                                                )
                                    
                                            )
                            )
                        );

$colunas = array(
                'idordemdecompra',
                'sindicato',
                'cfc',
                'centro_custo',
                'categoria',
                'subcategoria',
                'ordem_compra',
                'valor',
                'anexo',
                'descricao',
                'situacao',
            );
