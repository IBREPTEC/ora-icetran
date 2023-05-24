<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/workflow_reservas_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'regras_visualizar';
$config['acoes'][5] = 'regras_cadastrar_modificar';
$config['acoes'][6] = 'regras_remover';

$config['monitoramento']['onde'] = '243';
$config['monitoramento_regra']['onde'] = '244';

$config['banco'] = array(
						'tabela' => 'obz_classificacao_despesa',
						'primaria' => 'idclassificacao',
						'campos_insert_fixo' => array(
													'data_cad' => 'NOW()',
													'ativo' => '"S"'
												)
					);

$config['banco_regras'] = array(
								'tabela' => 'obz_classificacao_despesa_regras',
								'primaria' => 'idregra',
								'campos_insert_fixo' => array(
															'data_cad' => 'NOW()',
															'ativo' => '"S"'
														),
								'campos_unicos' => array(
												        array(
												            'campo_banco' => 'documento',
												            'campo_form' => 'idpolo||idcentro_custo||idcategoria||idsubcategoria',//Não pode ter repetido os 3 campos
												            'erro_idioma' => 'regra_utilizado'
												        )
												    )
							);