<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'modificar';

$config['monitoramento']['onde'] = '237';

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config['banco'] = array(
                        'tabela' => 'obz_sindicatos',
                        'primaria' => 'idsindicato',
                        'campos_insert_fixo' => array(
                                                    'data_cad' => 'NOW()',
                                                    'idsindicato' => $url[3]
                                                ),
                    );