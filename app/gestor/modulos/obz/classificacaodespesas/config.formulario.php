<?php
// Array de configuração para a formulario
$config['formulario'] = array(
                            array(
                                'fieldsetid'    => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
                                'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
                                'campos'        => array( // Campos do formulario
                                                        array(
                                                            'id' => 'form_nome',
                                                            'nome' => 'nome',
                                                            'nomeidioma' => 'form_nome',
                                                            'tipo' => 'input',
                                                            'valor' => 'nome',
                                                            'validacao' => array('required' => 'nome_vazio'),
                                                            'class' => 'span6',
                                                            'evento' => 'maxlength="30"',
                                                            'banco' => true,
                                                            'banco_string' => true,
                                                        ),

                                                        array(
                                                            'id' => 'form_descricao',
                                                            'nome' => 'descricao',
                                                            'nomeidioma' => 'form_descricao',
                                                            'tipo' => 'text',
                                                            'valor' => 'descricao',
                                                            'class' => 'span6',
                                                            'evento' => 'maxlength="300"',
                                                            'banco' => true,
                                                            'banco_string' => true,
                                                        ),

                                                        array(
                                                            'id' => 'form_ativo_painel',
                                                            'nome' => 'ativo_painel',
                                                            'nomeidioma' => 'form_ativo_painel',
                                                            'tipo' => 'select',
                                                            'array' => 'sim_nao', // Array que alimenta o select
                                                            'class' => 'span2',
                                                            'valor' => 'ativo_painel',
                                                            'validacao' => array('required' => 'painel_vazio'),
                                                            'banco' => true,
                                                            'banco_string' => true
                                                        )
                                                    )
                            )
                        );

//$sqlPolo = $_SESSION['adm_gestor_polo'] != 'S' ?  ' and idpolo in ('.$_SESSION['adm_polos'].')' : "";

$config['formulario_regra'] = array(
                                    array(
                                        'fieldsetid'    => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
                                        'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
                                        'campos'        => array( // Campos do formulario
                                                                array(
                                                                    'id' => 'form_idclassificacao',
                                                                    'nome' => 'idclassificacao',
                                                                    'tipo' => 'hidden',
                                                                    'banco' => true
                                                                ),

                                                                array(
                                                                    'id' => 'idescola',
                                                                    'nome' => 'idescola',
                                                                    'nomeidioma' => 'form_idpolo',
                                                                    'tipo' => 'select',
                                                                    'valor' => 'idescola',
                                                                    'validacao' => array('required' => 'idpolo_vazio'),
                                                                    'sql' => 'SELECT
                                                                                    idescola,
                                                                                    nome_fantasia
                                                                                FROM
                                                                                    escolas
                                                                                WHERE
                                                                                    ativo = "S" AND
                                                                                    ativo_painel = "S"
                                                                                   
                                                                                ORDER BY
                                                                                    nome_fantasia ASC', // SQL que alimenta o select
                                                                    'sql_valor' => 'idescola', // Coluna da tabela que será usado como o valor do options
                                                                    'sql_label' => 'nome_fantasia', // Coluna da tabela que será usado como o label do options
                                                                    'banco' => true
                                                                ),

                                                                array(
                                                                    'id' => 'idcentro_custo',
                                                                    'nome' => 'idcentro_custo',
                                                                    'nomeidioma' => 'form_idcentro_custo',
                                                                    'tipo' => 'select',
                                                                    'valor' => 'idcentro_custo',
                                                                    'validacao' => array('required' => 'idcentro_custo_vazio'),
                                                                    'banco' => true,
                                                                    'json' => true,
                                                                    'json_idpai' => 'idescola',
                                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/ajax_centro_custos/',
                                                                    'json_input_pai_vazio' => 'form_selecione_idpolo',
                                                                    'json_input_vazio' => 'form_selecione_idcentro_custo',
                                                                    'json_campo_exibir' => 'nome',
                                                                ),

                                                                array(
                                                                    'id' => 'idcategoria',
                                                                    'nome' => 'idcategoria',
                                                                    'nomeidioma' => 'form_idcategoria',
                                                                    'tipo' => 'select',
                                                                    'valor' => 'idcategoria',
                                                                    'validacao' => array('required' => 'idcategoria_vazio'),
                                                                    'banco' => true,
                                                                    'json' => true,
                                                                    'json_idpai' => 'idescola',
                                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/ajax_categorias/',
                                                                    'json_input_pai_vazio' => 'form_selecione_idpolo',
                                                                    'json_input_vazio' => 'form_selecione_idcategoria',
                                                                    'json_campo_exibir' => 'nome',
                                                                ),

                                                                array(
                                                                    'id' => 'idsubcategoria',
                                                                    'nome' => 'idsubcategoria',
                                                                    'nomeidioma' => 'form_idsubcategoria',
                                                                    'tipo' => 'select',
                                                                    'valor' => 'idsubcategoria',
                                                                    'validacao' => array('required' => 'idsubcategoria_vazio'),
                                                                    'banco' => true,
                                                                    'json' => true,
                                                                    'json_idpai' => 'idcategoria',
                                                                    'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/ajax_subcategorias/',
                                                                    'json_input_pai_vazio' => 'form_selecione_idpolo',
                                                                    'json_input_vazio' => 'form_selecione_idsubcategoria',
                                                                    'json_campo_exibir' => 'nome',
                                                                )
                                                            )
                                    ),
                            );
