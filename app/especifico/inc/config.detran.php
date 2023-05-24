<?php

//Dados do detran
$config['detran'] = [
    'SE' => [
        'urlSoap' => 'http://172.28.64.58:8089/wsIntegracaoEAD',
        'urlWsl' => 'http://172.28.64.58:8089/wsIntegracaoEAD?WSDL',
        'pUsuario' => 'DET53002',
        'pSenha' => 'DET2017',
        'pAmbiente' => 'P',//D = Desenvolvimento, P = Produção
        'registro_instrutor' => '03477780053',//Registro da CNH
    ],
    'PE' => [
        'urlJSON' => 'http://200.238.67.2:51175/JsonEAD.svc',
        'registro_empresa' => '02968119000188',//Registro da CNPJ
        'registro_instrutor' => '56555040653',//Registro do CPF
    ],
    'RS' => [
        'urlJSON' => 'https://mgfc.detran.rs.gov.br/gfc/rest/gfcmobile/cursoEAD',
        'organizacao' => 'SISTEMA',
        'matricula' => '4434',
        'senha' => '75ic0330',
        'codEmpresa' => 'EAD00006',
        'registro_instrutor' => '56555040653',
    ],
    'PR' => [
        'urlJSON' => 'http://www.wsutils.detran.pr.gov.br/detran-wsutils',
        'id' => 292568, //para autenticação
        'chave' => 'cf7df70847b751e78a55e8b0075d4be8', //para autenticação
        'registro_instrutor' => '56555040653',
        'registro_empresa' => '02968119000188',//Registro da CNPJ
    ],
    'RJ' => [
        // 'urlWsl' => 'http://charon:8080/wsstack/services/ENSINODI.wsdl', // DESENVOLVIMENTO
        'urlWsl' => 'http://leao:8080/wsstack/services/ENSINODI.wsdl', // PRODUÇÃO
        'pUsuario' => 'COENSIBR',
        // 'pSenha' => 'COENS123',  // DESENVOLVIMENTO
        'pSenha' => 'IBREP201',  // PRODUÇÃO
        'registro_empresa' => '02968119000188',//Registro da CNPJ
        'registro_instrutor' => '56555040653',//Registro da CNH
        'caer' => 'RJ52039',
    ],
    'MA' => [
        'urlWsl' => 'http://wsprodwebapp.detran.ma.gov.br:10010/wsstack/services/wsRegistroCursoEAD?wsdl', // PRODUÇÃO
        'usuario' => 'IBREP',
        'senha' => 'DT1@5Y82RE',  // PRODUÇÃO
        'cnpj_entidade' => '02968119000188',//Registro da CNPJ
        'cpf_instrutor' => '56555040653',//Registro da CNH
    ],
    'ES' => [
        'urlWsl' => 'https://renach2.es.gov.br/WebServices/EAD/wsEAD.asmx?WSDL',
        'login' => 'IBREP',
        'cpf_instrutor' => '56555040653',
        'chave' => '}Ghx-#5$EoA;yx9QkCw[U5b|1BPq=TZd'
    ],
    'MS' => [
        'urlLiberacao' => 'http://www2.detran.ms.gov.br/detranet/ead/consCond/consCond.asp',
        'urlConclusao' => 'http://www2.detran.ms.gov.br/detranet/ead/regCond/regCond.asp',
        'cnpjInst' => '02968119000188',
        'ip' => '177.47.183.75'
    ],
    'MT' => [
        'urlWsl' => 'http://ws.detrannet.mt.gov.br:8080/wsEventoCurso/wsEventoCurso.asmx?wsdl',
        'integrador' => 'ICET2',
        'chave' => '46c7ac3b12a323fd3dab',
        'cpf_instrutor' => '56555040653',
        'cnpj_entidade' => '02968119000188',
    ],
    'AL' => [
        // 'urlWslLiberacao' => 'https://wshml01.detran.al.gov.br/wsstack/services/CFCNW020?wsdl', // DESENVOLVIMENTO
        // 'urlWslConclusao' => 'https://wshml01.detran.al.gov.br/wsstack/services/CFCNW045?wsdl', // DESENVOLVIMENTO
        'urlWslLiberacao' => 'https://wsprd02.detran.al.gov.br/wsstack/services/CFCNW020?wsdl', // PRODUÇÃO
        'urlWslConclusao' => 'https://wsprd02.detran.al.gov.br/wsstack/services/CFCNW045?wsdl', // PRODUÇÃO
        'matricula' => '95750',
        'cpf_instrutor' => '56555040653',
        'cnpjInst' => '02968119000188',
        'codigo_cfc_e' => '480'
    ],
    'SC' => [
//        'urlJSON' => 'https://dev.apim.ciasc.sc.gov.br:8243/credenciamento-api/v1', // DESENVOLVIMENTO
        'urlJSON' => 'https://apim.ciasc.sc.gov.br:8243/credenciamento-api', // PRODUÇÃO
        'cpf_instrutor' => '56555040653',
        'cnpjInst' => '02968119000188',
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IlltSmhPVEV5TURka01UQmpaREU1Tm1Wak4yVTRPRGs1WVRJM1ptUXlORFJsWTJNeU5UWmlOUT09In0.eyJhdWQiOiJodHRwOlwvXC9vcmcud3NvMi5hcGltZ3RcL2dhdGV3YXkiLCJzdWIiOiJhZG1pbkBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImFkbWluIiwidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsInRpZXIiOiJVbmxpbWl0ZWQiLCJuYW1lIjoiQXBwLUNyZWRlbmNpYW1lbnRvIiwiaWQiOjc1LCJ1dWlkIjpudWxsfSwic2NvcGUiOiJhbV9hcHBsaWNhdGlvbl9zY29wZSBkZWZhdWx0IiwiaXNzIjoiaHR0cHM6XC9cL2FjZXNzby5jaWFzYy5zYy5nb3YuYnI6NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiVW5saW1pdGVkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9fSwia2V5dHlwZSI6IlBST0RVQ1RJT04iLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJDcmVkZW5jaWFtZW50b0FQSSIsImNvbnRleHQiOiJcL2NyZWRlbmNpYW1lbnRvLWFwaVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9XSwiY29uc3VtZXJLZXkiOiJrdTdvVHN5Nm9qd0piMWlNdnlpaTIxZ0lOOUlhIiwiZXhwIjoxMDY0MDgwNDE0NywiaWF0IjoxNjQwODA0MTQ3LCJqdGkiOiIzOTI5YTQ3MC1kZTMzLTRiNDMtODliNi04YjVkZGEwOGY2MzQifQ.dLd8e9LLa46lSoNxhIGu6KBWH766pvG548pCB4rXWgcVb__p0FRlNvxOSw_x2x5EjadIqE8aBQH6A5rFx-oZ6JRPGAEKFdJlGgoAyDS9bOMyiDWCTBId_v5M3Vb-kw_zME62G3d2x-LpMpRqzA_f6jJXd5K0UCvF5Mvs4rEq3Zw42A9spi6txcaNDbr-SYENmVG3mWRKw3nyiVdMKxkd5r9S4-G2XnlJes1ERQWIo3-2hlH9h-ZNZSmU4aauy6Q-in3sp-M4OufoNqCreJavVMZznZauU0RK3YRvWXpibEpgsd44LQKizntdddTUrLxiYvhtroVpPp6q8MBd7elkpg'
    ],
    'CE' => [
        'urlJSON' => 'https://dtirchservice.detran.ce.gov.br/integradorcfc/cursosEad',
        'chaveAcesso' => '7ff2576002083e5647f7f2293547b6f87960d8c0'
    ],
];

//Estados integrados com o Detran
$estadosDetran = [
    "PE" => 17,
    "SE" => 26,
    'RS' => 21,
    'PR' => 16,
    'RJ' => 19,
    'MA' => 10,
    'ES' => 8,
    'MS' => 12,
    'MT' => 11,
    'AL' => 2,
    'SC' => 24,
    'CE' => 6,
];
$detran_tipo_aula_motociclista = [
    'PE' => [
        50 => ['codigo' => 55, 'modulo' => 2],   // MOTOFRETISTA SEMIPRESENCIAL
        51 => ['codigo' => 57, 'modulo' => 2],   // MOTOFRETISTA SEMIPRESENCIAL
        29 => ['codigo' => 59, 'modulo' => 2],   // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => ['codigo' => 58, 'modulo' => 2],   // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
    ]
];

//Código dos cursos no detran
$detran_tipo_aula = [
    'SE' => [
        /*
        51 => 51, // MOTOTAXISTA SEMIPRESENCIAL
        50 => 50, // MOTOFRETISTA SEMIPRESENCIAL
        49 => 49, // PREVENTIVO DE RECICLAGEM EAD*/
        29 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => []
        ], // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => []
        ], // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
        22 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                124 => 'D',   // MÓDULO- DIREÇÃO DEFENSIVA ARCNH
                125 => 'S',   // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS ARCNH
            ]
        ], // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD
        20 => [
            'CODIGO' => 'R',
            'DISCIPLINAS' => [
                197 => 'L',   // MÓDULO- LEGISLAÇÃO DE TRÂNSITO RCI
                195 => 'D',   // MÓDULO- DIREÇÃO DEFENSIVA RCI
                196 => 'S',   // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS RCI
                76 => 'R',    // MÓDULO- RELACIONAMENTO INTERPESSOAL RCI
            ]
        ], // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        18 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                115 => 'L',   // MÓDULO- LEGISLAÇÃO DE TRÂNSITO RCI
                116 => 'D',   // MÓDULO- DIREÇÃO DEFENSIVA RCI
                117 => 'S',   // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS RCI
                118 => 'R',   // MÓDULO- RELACIONAMENTO INTERPESSOAL RCI
            ]
        ], // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        17 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                15 => 'L',   // MÓDULO- LEGISLAÇÃO DE TRÂNSITO ACVE
                83 => 'D',   // MÓDULO- DIREÇÃO DEFENSIVA ACVE
                110 => 'S', // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS, RESPEITO AO MEIO AMBIENTE E CONVÍVIO SOCIAL NO TRÂNSITO ACVE
                111 => 'R', // MÓDULO- RELACIONAMENTO INTERPESSOAL ACVE
            ]
        ], // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                106 => 'L', // MÓDULO- LEGISLAÇÃO DE TRÂNSITO ACVTPP
                107 => 'D', // MÓDULO- DIREÇÃO DEFENSIVA ACVTPP
                108 => 'S', // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS, RESPEITO AO MEIO AMBIENTE E CONVÍVIO SOCIAL ACVTPP
            ]
        ], // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                119 => 'L', // MÓDULO - LEGISLAÇÃO DE TRÂNSITO ACVTE
                120 => 'D', // MÓDULO- DIREÇÃO DEFENSIVA ACVTE
                121 => 'S', // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS, RESPEITO AO MEIO AMBIENTE E CONVÍVIO SOCIAL ACVTE
                122 => 'R', // MÓDULO- RELACIONAMENTO INTERPESSOAL ACVTE
            ]
        ], // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => [
            'CODIGO' => 'A',
            'DISCIPLINAS' => [
                112 => 'L', // MÓDULO- LEGISLAÇÃO DE TRÂNSITO ACVTCP
                113 => 'D', // MÓDULO- DIREÇÃO DEFENSIVA ACVTCP
                221 => 'S', // MÓDULO- NOÇÕES DE PRIMEIROS SOCORROS, RESPEITO AO MEIO AMBIENTE E CONVÍVIO SOCIAL ACVTCP
                114 => 'R', // MÓDULO- RELACIONAMENTO INTERPESSOAL ACVTCP
            ]
        ], // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
        /*13 => 13, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => 12, // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => 11, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => 10, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => 9,   // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD*/
    ],
    'PE' => [
        51 => ['codigo' => 57, 'modulo' => 1],   // MOTOTAXISTA SEMIPRESENCIAL - MÓDULO PRESENCIAL
        50 => ['codigo' => 55, 'modulo' => 1],   // MOTOFRETISTA SEMIPRESENCIAL
        /*49 => 49, // PREVENTIVO DE RECICLAGEM EAD*/
        29 => ['codigo' => 59, 'modulo' => 1],   // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => ['codigo' => 58, 'modulo' => 1],   // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
        22 => ['codigo' => 30, 'modulo' => 1],   // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD - SEM MÓDULO
        18 => ['codigo' => 17, 'modulo' => 1],   // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        17 => ['codigo' => 14, 'modulo' => 1],   // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => ['codigo' => 11, 'modulo' => 1],   // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => ['codigo' => 12, 'modulo' => 1],   // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => ['codigo' => 13, 'modulo' => 1],   // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
        20 => ['codigo' => 20, 'modulo' => 0],   // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        13 => ['codigo' => 7, 'modulo' => 0],    // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => ['codigo' => 4, 'modulo' => 0],    // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => ['codigo' => 1, 'modulo' => 0],    // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => ['codigo' => 2, 'modulo' => 0],    // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => ['codigo' => 3, 'modulo' => 0],     // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
    ],
    'RS' => [
        /*51 => 51, // MOTOTAXISTA SEMIPRESENCIAL
        50 => 50, // MOTOFRETISTA SEMIPRESENCIAL*/
        49 => 10, // PREVENTIVO DE RECICLAGEM EAD
        /*29 => 18, // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => 18, // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL*/
        22 => 18, // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD
        20 => 10, // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        18 => 19, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        17 => 44, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => 14, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => 15, // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => 16, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
        13 => 7, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => 43, // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => 11, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => 12, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => 13,  // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
		118 => 10,// RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'PR' => [
        20 => 10, //RECICLAGEM DE CONDUTORES INFRATORES
		118 => 10,//RECICLAGEM PARA CONDUTORES INFRATORES 2023
        /*6 => 10, //RECICLAGEM DE CONDUTORES INFRATORES 2.0,
        7 => 10,*/
    ],
    'RJ' => [
        4 =>  'R2',//RECICLAGEM DE CONDUTORES INFRATORES
    ],
    'MA' => [
        4 => 20, 	//RECICLAGEM DE CONDUTORES INFRATORES
		118 => 20,	//RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'ES' => [
        6 => 20, 	//RECICLAGEM DE CONDUTORES INFRATORES 2.0
		118 => 20,	//RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'MS' => [
        /*
        51 => 51, // MOTOTAXISTA SEMIPRESENCIAL
        50 => 50, // MOTOFRETISTA SEMIPRESENCIAL*/
        49 => 'P', // PREVENTIVO DE RECICLAGEM EAD
        /* 29 => 30, // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => 30, // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
        22 => 30, // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD */
        20 => 'R', // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        // 18 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        /* 17 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => 30, // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD */
        /*13 => 13, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => 12, // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => 11, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => 10, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => 9,   // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD*/
		118 => 'R', //RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'MT' => [
        /*
        51 => 51, // MOTOTAXISTA SEMIPRESENCIAL
        50 => 50, // MOTOFRETISTA SEMIPRESENCIAL
        49 => 49, // PREVENTIVO DE RECICLAGEM EAD*/
        /*  29 => 30, // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
         28 => 30, // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
        22 => 30, // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD */
        20 => 20, // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        /* 18 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        17 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => 30, // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => 30, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD */
        /*13 => 13, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => 12, // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => 11, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => 10, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => 9,   // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD*/
		118 => 20, //RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'AL' => [

        51 => 8, // MOTOTAXISTA SEMIPRESENCIAL
        50 => 9, // MOTOFRETISTA SEMIPRESENCIAL
        //49 => 49, // PREVENTIVO DE RECICLAGEM EAD
        29 => 19, // ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL
        28 => 18, // ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL
        22 => 30, // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH EAD
        20 => 20, // RECICLAGEM PARA CONDUTORES INFRATORES EAD
        18 => 17, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        17 => 14, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD
        16 => 11, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        15 => 12, // ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD
        14 => 13, // ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
        13 => 7, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD
        12 => 4, // CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD
        11 => 1, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD
        10 => 2, // CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD
        9 => 3,   // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD
		118 => 20, //RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'CE' => [
        3 => 0, //RECICLAGEM DE CONDUTORES INFRATORES,
		118 => 0 //RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ],
    'SC' => [
    //ICETRAN
    51 => 8,     //	MOTOTAXISTA SEMIPRESENCIAL	SEMIPRESENCIAL
    50 => 9,     //	MOTOFRETISTA SEMIPRESENCIAL	SEMIPRESENCIAL
    29 => 18,    //	ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL	SEMIPRESENCIAL
    28 => 19,    //	ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL	SEMIPRESENCIAL
    20 => 20,    //	RECICLAGEM PARA CONDUTORES INFRATORES EAD	EAD
    18 => 17,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD	EAD
    17 => 14,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD	EAD
    16 => 11,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD	EAD
    15 => 12,    //	ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD	EAD
    14 => 13,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD	EAD
    13 => 7,     //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD	EAD
    12 => 4,     //	CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD	EAD
    11 => 1,     //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD	EAD
    10 => 2,     //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD	EAD
    9 => 3,      //	CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD	EAD
	122 => 3,	 // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS 2022
	22 => 30,    // ATUALIZACAO PARA RENOVACAO DA CNH
	118 => 20	 // RECICLAGEM PARA CONDUTORES INFRATORES 2023
    ]
];

//Código das disciplinas no detran
$detran_codigo_materia = [
    'PE' => [
        15 =>  'L',//Legislação de Trânsito
        18 =>  'D',//Direção Defensiva
        16 =>  'S',//Noções de Primeiros Socorros
        25 =>  'R'//Relacionamento Interpessoal
    ],
    'RJ' => [
        15 =>  'L',//Legislação de Trânsito
        18 =>  'D',//Direção Defensiva
        16 =>  'S',//Noções de Primeiros Socorros
        25 =>  'R'//Relacionamento Interpessoal
    ],
];

//Horas aulas de cada disciplina para cada curso no detran
$detran_horas_aula = [
    'SE' => [
        'R' => [//RECICLAGEM DE CONDUTORES INFRATORES
            'L' =>  12,//Legislação de Trânsito
            'D' =>  8,//Direção Defensiva
            'S' =>  4,//Noções de Primeiros Socorros
            'R' =>  6//Relacionamento Interpessoal
        ],
        'A' => [//ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH
            'D' =>  10,//Direção Defensiva
            'S' =>  5//Noções de Primeiros Socorros
        ]
    ],
    'PE' => [
    ],
    'RJ' => [
        7 =>  'L',//Legislação de Trânsito
        8 =>  'D',//Direção Defensiva
        9 =>  'S',//Noções de Primeiros Socorros
        10 =>  'R'//Relacionamento Interpessoal
    ],
];

$situacaoDetran['pt_br'] = array(
    'LI' =>  'Liberado',
    'NL' =>  'Não liberado',
    'AL' =>  'Aguardando liberação',
);


$detran_carga_horaria = [

    'SC' => [
        // ICETRAN
        51 => 30,    //	MOTOTAXISTA SEMIPRESENCIAL	SEMIPRESENCIAL
        50 => 30,    //	MOTOFRETISTA SEMIPRESENCIAL	SEMIPRESENCIAL
        29 => 10,    //	ATUALIZAÇÃO PARA MOTOTAXISTA SEMIPRESENCIAL	SEMIPRESENCIAL
        28 => 10,    //	ATUALIZAÇÃO PARA MOTOFRETISTA SEMIPRESENCIAL	SEMIPRESENCIAL
        20 => 30,    //	RECICLAGEM PARA CONDUTORES INFRATORES EAD	EAD
        18 => 16,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE CARGA INDIVISÍVEL EAD	EAD
        17 => 16,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE EMERGÊNCIA EAD	EAD
        16 => 16,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD	EAD
        15 => 16,    //	ATUALIZAÇÃO PARA CONDUTORES VEÍCULO DE TRANSPORTE DE ESCOLARES EAD	EAD
        14 => 16,    //	ATUALIZAÇÃO PARA CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD	EAD
        13 => 50,    //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE CARGA INDIVISÍVEL EAD	EAD
        12 => 50,    //	CONDUTORES DE VEÍCULOS DE EMERGÊNCIA EAD	EAD
        11 => 50,    //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE PRODUTOS PERIGOSOS EAD	EAD
        10 => 50,    //	CONDUTORES DE VEÍCULOS DE TRANSPORTE DE ESCOLARES EAD	EAD
        9 => 50,     //	CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS EAD	EAD
		122 => 50,	 // CONDUTORES DE VEÍCULO DE TRANSPORTE COLETIVO DE PASSAGEIROS 2022
		22 => 30,    // ATUALIZAÇÃO PARA RENOVAÇÃO DA CNH
		118 => 30,	 // [2023] RECICLAGEM PARA CONDUTORES INFRATORES
    ]
];