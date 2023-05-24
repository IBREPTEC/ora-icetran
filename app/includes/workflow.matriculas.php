<?php

$workflow_parametros_matriculas = array(
    0 => array(
        'idopcao' => 1,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Alterar situação da matrícula',
        'parametros' => array(),
    ),
    1 => array(
        'idopcao' => 2,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Alterar dados da matrícula',
        'parametros' => array(),
    ),
    2 => array(
        'idopcao' => 3,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Adicionar associados',
        'parametros' => array(),
    ),
    3 => array(
        'idopcao' => 4,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Remover associados',
        'parametros' => array(),
    ),
    4 => array(
        'idopcao' => 5,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Modificar financeiro da matrícula',
        'parametros' => array(),
    ),
    5 => array(
        'idopcao' => 6,
        'tipo' => 'prerequisito',
        'nome' => 'Ter o valor das mensalidades igual ao valor do contrato',
        'parametros' => array(),
    ),
    6 => array(
        'idopcao' => 7,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Enviar documentos',
        'parametros' => array(),
    ),
    7 => array(
        'idopcao' => 8,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Alterar situação dos documentos',
        'parametros' => array(),
    ),
    8 => array(
        'idopcao' => 9,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Remover documentos',
        'parametros' => array(),
    ),
    9 => array(
        'idopcao' => 10,
        'tipo' => 'prerequisito',
        'nome' => 'Ter documentos obrigatórios anexados',
        'parametros' => array(),
    ),
    10 => array(
        'idopcao' => 11,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Gerar / Enviar contratos',
        'parametros' => array(),
    ),
    11 => array(
        'idopcao' => 12,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Informar assinatura do contrato',
        'parametros' => array(),
    ),
    12 => array(
        'idopcao' => 13,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Informar validade do contrato',
        'parametros' => array(),
    ),
    13 => array(
        'idopcao' => 14,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Cancelar contrato',
        'parametros' => array(),
    ),
    14 => array(
        'idopcao' => 15,
        'tipo' => 'prerequisito',
        'nome' => 'Ter contrato gerado',
        'parametros' => array(),
    ),
    15 => array(
        'idopcao' => 16,
        'tipo' => 'prerequisito',
        'nome' => 'Ter contrato assinado',
        'parametros' => array(),
    ),
    16 => array(
        'idopcao' => 17,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Aprovar matrícula',
        'parametros' => array(),
    ),
    17 => array(
        'idopcao' => 18,
        'tipo' => 'prerequisito',
        'nome' => 'Ter matrícula aprovada',
        'parametros' => array(),
    ),
    18 => array(
        'idopcao' => 19,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Gerar declarações',
        'parametros' => array(),
    ),
    19 => array(
        'idopcao' => 20,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Renegociar parcelas',
        'parametros' => array(),
    ),
    20 => array(
        'idopcao' => 21,
        'tipo' => 'acao',
        'combo' => 'Gestor',
        'nome' => 'Atualizar Cofeci',
        'parametros' => array(),
    ),
    21 => array(
        'idopcao' => 22,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Alterar CFC da matrícula',
        'parametros' => array(),
    ),
    22 => array(
        'idopcao' => 23,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Lançar notas',
        'parametros' => array(),
    ),
    23 => array(
        'idopcao' => 24,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Remover notas',
        'parametros' => array(),
    ),
    24 => array(
        'idopcao' => 25,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Modificar notas',
        'parametros' => array(),
    ),
    25 => array(
        'idopcao' => 26,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Transferir parcelas',
        'parametros' => array(),
    ),
    26 => array(
        'idopcao' => 27,
        'tipo' => 'visualizacao',
        'combo' => 'Aluno',
        'nome' => 'Visualizar AVA',
        'parametros' => array(),
    ),
    27 => array(
        'idopcao' => 28,
        'tipo' => 'visualizacao',
        'combo' => 'Aluno',
        'nome' => 'Agendar prova presencial',
        'parametros' => array(),
    ),
    28 => array(
        'idopcao' => 29,
        'tipo' => 'acao',
        'combo' => 'Gestor',
        'nome' => 'Atualizar data de conclusão do curso',
        'parametros' => array(),
    ),
    29 => array(
        'idopcao' => 30,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Transferir aluno de turma.',
        'parametros' => array(),
    ),
    30 => array(
        'idopcao' => 31,
        'tipo' => 'acao',
        'combo' => 'Gestor',
        'nome' => 'Atualizar data de comissão',
        'parametros' => array(),
    ),
    31 => array(
        'idopcao' => 32,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Alterar porcentagem do aluno',
        'parametros' => array(),
    ),
    32 => array(
        'idopcao' => 33,
        'tipo' => 'acao',
        'nome' => 'Enviar SMS de bem vindo ao curso',
        'parametros' => array(
            0 => array(
                'idparametro' => 1,
                'tipo' => 'textarea',
                'nome' => 'Texto do SMS:',
                'valor' => 'Bem vindo (a) ao Curso [[CURSO][NOME]]! Seus dados de acesso foram enviados para o e-mail [[ALUNO][EMAIL]] Qualquer dúvida entre em contato conosco ava@ibrep.com.br'
            ),
        ),
    ),
    33 => array(
        'idopcao' => 34,
        'tipo' => 'acao',
        'nome' => 'Enviar SMS de aprovação ao curso',
        'parametros' => array(
            0 => array(
                'idparametro' => 2,
                'tipo' => 'textarea',
                'nome' => 'Texto do SMS:',
                'valor' => 'Olá! Parabéns pela aprovação no Curso [[CURSO][NOME]]! Desejamos sucesso e esperamos revê-lo(a) em breve em outro curso do [[EMPRESA]].'
            ),
        ),
    ),
    34 => array(
        'idopcao' => 35,
        'tipo' => 'acao',
        'nome' => 'Enviar SMS ao mudar situação',
        'parametros' => array(
            0 => array(
                'idparametro' => 3,
                'tipo' => 'textarea',
                'nome' => 'Texto do SMS:',
                'valor' => 'Olá! Sua matrícula #[[MATRICULA][ID]], no Curso [[CURSO][NOME]], foi modificada no [[EMPRESA]].'
            ),
        ),
    ),
    35 => array(
        'idopcao' => 36,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Alterar situação da matrícula',
        'parametros' => array(),
    ),
    36 => array(
        'idopcao' => 37,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Alterar dados da matrícula',
        'parametros' => array(),
    ),
    37 => array(
        'idopcao' => 38,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Alterar dados do aluno',
        'parametros' => array(),
    ),
    38 => array(
        'idopcao' => 39,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Adicionar / Modificar associados',
        'parametros' => array(),
    ),
    39 => array(
        'idopcao' => 40,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Remover associados',
        'parametros' => array(),
    ),
    40 => array(
        'idopcao' => 41,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Modificar financeiro da matrícula',
        'parametros' => array(),
    ),
    41 => array(
        'idopcao' => 42,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Enviar documentos',
        'parametros' => array(),
    ),
    42 => array(
        'idopcao' => 43,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Alterar situação dos documentos',
        'parametros' => array(),
    ),
    43 => array(
        'idopcao' => 44,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Remover documentos',
        'parametros' => array(),
    ),
    44 => array(
        'idopcao' => 45,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Gerar contratos',
        'parametros' => array(),
    ),
    45 => array(
        'idopcao' => 46,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Informar assinatura do contrato',
        'parametros' => array(),
    ),
    46 => array(
        'idopcao' => 47,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Informar validade do contrato',
        'parametros' => array(),
    ),
    47 => array(
        'idopcao' => 48,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Cancelar contrato',
        'parametros' => array(),
    ),
    48 => array(
        'idopcao' => 49,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Zerar tentativas de prova',
        'parametros' => array(),
    ),
    49 => array(
        'idopcao' => 50,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Alterar situação da matrícula',
        'parametros' => array(),
    ),
    50 => array(
        'idopcao' => 51,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Alterar dados da matrícula',
        'parametros' => array(),
    ),
    51 => array(
        'idopcao' => 52,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Adicionar associados',
        'parametros' => array(),
    ),
    52 => array(
        'idopcao' => 53,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Remover associados',
        'parametros' => array(),
    ),
    53 => array(
        'idopcao' => 54,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Modificar financeiro da matrícula',
        'parametros' => array(),
    ),

    54 => array(
        'idopcao' => 55,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Gerar / Enviar contratos',
        'parametros' => array(),
    ),
    57 => array(
        'idopcao' => 56,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Informar assinatura do contrato',
        'parametros' => array(),
    ),
    58 => array(
        'idopcao' => 57,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Informar validade do contrato',
        'parametros' => array(),
    ),
    59 => array(
        'idopcao' => 58,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Cancelar contrato',
        'parametros' => array(),
    ),
    60 => array(
        'idopcao' => 59,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Aprovar matrícula',
        'parametros' => array(),
    ),
    61 => array(
        'idopcao' => 60,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Gerar declarações',
        'parametros' => array(),
    ),
    62 => array(
        'idopcao' => 61,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Renegociar parcelas',
        'parametros' => array(),
    ),
/*    63 => array(
        'idopcao' => 62,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Lançar notas digital',
        'parametros' => array(),
    ),*/
    64 => array(
        'idopcao' => 63,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Remover notas',
        'parametros' => array(),
    ),
/*    65 => array(
        'idopcao' => 64,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Modificar notas digital',
        'parametros' => array(),
    ),*/
    66 => array(
        'idopcao' => 65,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Transferir parcelas',
        'parametros' => array(),
    ),
    67 => array(
        'idopcao' => 66,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Transferir aluno de turma.',
        'parametros' => array(),
    ),
    68 => array(
        'idopcao' => 67,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Alterar porcentagem do aluno',
        'parametros' => array(),
    ),
    69 => array(
        'idopcao' => 68,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Transferir aluno de turma.',
        'parametros' => array(),
    ),
    70 => array(
        'idopcao' => 69,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Enviar documentos',
        'parametros' => array(),
    ),
    71 => array(
        'idopcao' => 70,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Remover documentos',
        'parametros' => array(),
    ),
    72 => array(
        'idopcao' => 71,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Lançar notas',
        'parametros' => array(),
    ),
    73 => array(
        'idopcao' => 72,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Modificar notas',
        'parametros' => array(),
    ),
    74 => array(
        'idopcao' => 73,
        'tipo' => 'acao',
        'nome' => 'Enviar e-mail ao alterar a situação',
        'parametros' => array(
            0 => array(
                'idparametro' => 2,
                'tipo' => 'textarea',
                'nome' => 'Texto do EMAIL:',
                'valor' => 'Olá! Parabéns pela aprovação no Curso [[CURSO][NOME]]! Desejamos sucesso e esperamos revê-lo(a) em breve em outro curso do [[EMPRESA]].'
            ),
        ),
    ),
    75 => array(
        'idopcao' => 74,
        'tipo' => 'visualizacao',
        'combo' => 'Aluno',
        'nome' => 'Gerar certificado',
        'parametros' => array(),
    ),
    76 => array(
        'idopcao' => 75,
        'tipo' => 'acao',
        'nome' => 'Remover Aprovação Financeira',
        'parametros' => array(),
    ),
    77 => array(
        'idopcao' => 76,
        'tipo' => 'acao',
        'nome' => 'Adicionar Aprovação Financeira',
        'parametros' => array(),
    ),
    78 => array(
        'idopcao' => 77,
        'tipo' => 'visualizacao',
        'combo' => 'CFC',
        'nome' => 'Gerar certificado',
        'parametros' => array(),
    ),
    79 => array(
        'idopcao' => 78,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Gerar certificado',
        'parametros' => array(),
    ),
    80 => array(
        'idopcao' => 79,
        'tipo' => 'visualizacao',
        'combo' => 'Atendente',
        'nome' => 'Gerar declarações',
        'parametros' => array(),
    ),
    81 => array(
        'idopcao' => 80,
        'tipo' => 'visualizacao',
        'combo' => 'Gestor',
        'nome' => 'Gerar XML',
        'parametros' => array(),
    ),
);
