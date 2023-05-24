<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/assuntos_atendimento_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'associar_centro';
$config['acoes'][4] = 'remover_centro';

$config['monitoramento']['onde'] = '249';

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)

$config['banco_subcategoria'] = array(
    'tabela'             => 'obz_subcategorias',
    'primaria'           => 'idsubcategoria',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
    ),
    
);