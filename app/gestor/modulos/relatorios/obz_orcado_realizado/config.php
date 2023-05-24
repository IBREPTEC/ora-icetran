<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlInstituicao = 'select idsindicato, nome from sindicatos where ativo = "S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlInstituicao .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
$sqlInstituicao .= ' order by nome';

// Array de configuração para a formulario
$config['formulario'] = array(
                            array(
                                'fieldsetid' => 'dadosdoobjeto',
                                'legendaidioma' => 'legendadadosdados',
                                'campos' => array(
                                                array(
                                                    'id' => 'form_tipo_data_filtro',
                                                    'nome' => 'de_ate',
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
                                                    'validacao' => array(
                                                                        'required' => 'tipo_data_filtro_vazio'
                                                                    ),
                                                    'nomeidioma' => 'form_tipo_data_filtro',
                                                    'botao_hide' => true,
                                                    'iddiv2_obr' => true,
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
                                                    'id' => 'form_meta',
                                                    'nome' => 'meta',
                                                    'valor' => "meta",
                                                    "valor_php" => "return '0,00'",
                                                    'tipo' => 'input',
                                                    'legenda' => '%',
                                                    'evento' => "maxlength='6'",
                                                    'class' => 'span1',
                                                    'decimal' => 'true',
                                                    'nomeidioma' => 'form_meta',
                                                    "validacao" => array("required" => "meta_vazio")
                                                ),
                                                array(
                                                    'id' => 'idsindicato',
                                                    "sql" => $sqlInstituicao,
                                                    "nome" => "q[1|i.idsindicato]",
                                                    "tipo" => "select",
                                                    "valor" => "idsindicato",
                                                    "class" => "span5",
                                                    "sql_valor" => "idsindicato",
                                                    "sql_label" => "nome",
                                                    "validacao" => array("required" => "idinstituicao_vazio"),
                                                    "nomeidioma" => "form_instituicao_filtro",
                                                    "sql_filtro" => "select * from sindicatos where ativo='S' and idsindicato=%",
                                                    "sql_filtro_label" => "nome"
                                                ),
                                                array(
                                                    'id' => 'idcentro_custo',
                                                    'nome' => 'idcentro_custo',
                                                    'nomeidioma' => 'form_idcentro_custo',
                                                    'tipo' => 'checkbox',
                                                    'valor' => 'idcentro_custo',
                                                    'class' => 'span5',
                                                    'banco' => true,
                                                    'json' => true,
                                                    "validacao" => array("required" => "centro_custo_vazio"),
                                                    'json_idpai' => 'idsindicato',
                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_centro_custos/',
                                                    'json_input_pai_vazio' => 'form_selecione_idsindicato',
                                                    'json_input_vazio' => 'form_selecione_idcentro_custo',
                                                    'json_campo_exibir' => 'nome',
                                                    'sql_filtro' => 'SELECT * FROM centros_custos WHERE ativo = "S" AND idcentro_custo = %',
                                                    'sql_filtro_label' => 'nome'
                                                ),
                                                array(
                                                    'id' => 'idescola',
                                                    'nome' => 'idescola',
                                                    'nomeidioma' => 'form_polo',
                                                    'tipo' => 'checkbox',
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

                                            )
                            )
                        );

$colunas = array(
  1 => "centro_custo",
  2 => "responsavel",
  3 => "dono",
  4 => "cfc",
  5 => "categoria",
  6 => "subcategoria",
  7 => "ordem_de_compra",  
  8 => "memorial",
  9 => "orcado",
  10 => "meta",
  11 => "realizado",
  12 => "variacao_porcento",
  13 => "variacao_real",
  14 => "justificativa_texto",
);
