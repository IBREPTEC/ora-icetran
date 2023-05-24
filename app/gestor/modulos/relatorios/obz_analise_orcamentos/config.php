<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlInstituicao = 'SELECT idsindicato, nome FROM sindicatos WHERE ativo = "S"';
//if($_SESSION['adm_gestor_instituicao'] != 'S')
//	$sqlInstituicao .= ' AND idinstituicao IN ('.$_SESSION['adm_instituicoes'].')';
//$sqlInstituicao .= ' ORDER BY nome_abreviado';

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
                                                    'sql_label' => 'nome',
                                                    'validacao' => array('required' => 'idinstituicao_vazio'),
                                                    'nomeidioma' => 'form_instituicao_filtro',
                                                    'sql_filtro' => 'SELECT * FROM sindicatos WHERE ativo = "S" AND idsindicato = %',
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

                                                array(
                                                    'id' => 'idcentro_custo',
                                                    'nome' => 'idcentro_custo',
                                                    'nomeidioma' => 'form_idcentro_custo',
                                                    'tipo' => 'checkbox',
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
                                                )
                                            )
                            )
                        );

$colunas = array(
                'sindicato',
                'cfc',
                'centro_custo',
                'responsavel',
                'dono',
                'categoria',
                'subcategoria',
                'descricao',
                'memorial',
                'ano_anterior_realizado',
                'ano_atual_orcado',
                'variacao_porcentagem',
                'variacao_dinheiro'
            );
