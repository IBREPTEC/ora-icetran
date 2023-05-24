<?php
// Array de configuração para a formulario
$config['formulario'] = array(
                            array(
                                'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
                                'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
                                'campos' => array( // Campos do formulario
                                                array(
                                                    'id' => 'form_nome',
                                                    'nome' => 'nome',
                                                    'nomeidioma' => 'form_nome',
                                                    'tipo' => 'input',
                                                    'valor' => 'nome',
                                                    'class' => 'span6',
                                                    "evento" => "disabled='disabled'"
                                                ),

                                                array(
                                                    'id' => 'form_nome_abreviado',
                                                    'nome' => 'nome_abreviado',
                                                    'nomeidioma' => 'form_nome_abreviado',
                                                    'tipo' => 'input',
                                                    'valor' => 'nome_abreviado',
                                                    'class' => 'span2',
                                                    "evento" => "disabled='disabled'"
                                                ),

                                                array(
                                                    'id' => 'form_periodo_de',
                                                    'nome' => 'periodo_de',
                                                    'nomeidioma' => 'form_periodo_de',
                                                    'ajudaidioma' => 'form_periodo_de_ajuda',
                                                    'tipo' => 'input',
                                                    'valor' => 'periodo_de',
                                                    'validacao' => array('required' => 'form_periodo_de_vazio'),
                                                    'valor_php' => 'if ($dados["periodo_de"] && $dados["periodo_de"] != "0000-00-00") {
                                                                        return formataData("%s", "br", 0);
                                                                    }',
                                                    'class' => 'span2',
                                                    'mascara' => '99/99/9999',
                                                    'datepicker' => true,
                                                    'banco' => true, 
                                                    'banco_php' => 'return formataData("%s", "en", 0)',
                                                    'banco_string' => true
                                                ),

                                                array(
                                                    'id' => 'form_periodo_ate',
                                                    'nome' => 'periodo_ate',
                                                    'nomeidioma' => 'form_periodo_ate',
                                                    'ajudaidioma' => 'form_periodo_ate_ajuda',
                                                    'tipo' => 'input',
                                                    'valor' => 'periodo_ate',
                                                    'validacao' => array('required' => 'form_periodo_ate_vazio'),
                                                    'valor_php' => 'if ($dados["periodo_ate"] && $dados["periodo_ate"] != "0000-00-00") {
                                                                        return formataData("%s", "br", 0);
                                                                    }',
                                                    'class' => 'span2',
                                                    'mascara' => '99/99/9999',
                                                    'datepicker' => true,
                                                    'banco' => true, 
                                                    'banco_php' => 'return formataData("%s", "en", 0)',
                                                    'banco_string' => true
                                                ),
                                            )
                            )
                        );