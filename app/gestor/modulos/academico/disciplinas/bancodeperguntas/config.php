<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'visualizar_disciplinas';
$config['acoes'][5] = 'associar_disciplinas';
$config['acoes'][6] = 'remover_disciplinas';

$config['monitoramento']['onde'] = '40';

// Array de configuração de banco de dados 
//(nome da tabela, chave primaria, 
//campos com valores fixos, campos unicos)
$config['banco'] = array(
  'tabela' => 'perguntas',
  'primaria' => 'idpergunta',
  'campos_insert_fixo' => array(
	'data_cad' => 'now()', 
	'ativo' => '"S"'
  ),
  /*'campos_unicos' => array(
	array(
	  'campo_banco' => 'nome', 
	  'campo_form' => 'nome||iddisciplina', 
	  'erro_idioma' => 'nome_utilizado'
	)
  ),*/
);