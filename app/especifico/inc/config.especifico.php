<?php
$config["tituloEmpresa"] = "ICETRAN";
$config["websiteEmpresa"] = "http://www.alfamaoraculo.com.br";
$config["tituloSistema"] = "Oráculo Cursos (DEV)";
$config["urlSistema"] = "http://localhost.transito.com.br/";
$config["urlSistemaFixa"] = "https://dev-transito.alfamaoraculo.com.br/";
$config["url"] = "https://dev-transito.alfamaoraculo.com.br";
$config["urlSite"] = "https://dev-transito.alfamaoraculo.com.br/";
$config["urlSiteCliente"] = "http://localhost.transito.com.br/aluno/";
$config["emailEsqueci"] = "oraculo@alfamaoraculo.com.br";
$config["emailLoja"] = "oraculo@alfamaoraculo.com.br";
$config["chaveLogin"] = "oraculo.ibreptran#2016";
$config["tamanho_upload_padrao"] = 8388608;
$config['cadastrar_sindicato'] = true;
$config['cadastrar_mantenedora'] = true;
$config['integrado_com_sms'] = true;

/// DADOS DE ACESSO API SMS
$config["loginSMS"] = "oraculo_desenvolvimento";
$config["tokenSMS"] = "3624eba5b9c94171b996eeed06d609da1ac24902a6219f6132f22be82d9bcc4375331b38759166c3396d97771f396b02d90a0419a9d7014c807840864bd12ff1";
$config["linkapiSMS"] = "http://sms.alfamaweb.com.br/";
$config['limite_emails_mailing'] = NULL;

//$config["link_integracao_cielo"] = "https://s.alfamaweb.com.br/oraculo/pagamento/";
$config["emailSistema"] = "oraculo@alfamaoraculo.com.br";
$config["emailMailing"] = "monitoria1@ibreptran.com.br";

//CONFIGURAÇÃO EMAIL DE BOLETO DISPONÍVEL
$config["emailParceria"] = "parceria.ead@ibreptran.com.br";
$config["telefone"] = "0800 400 2982";

//CONFIGURAÇÃO DE HOST USUARIO E SENHA DO BANCO DO LOG DE EMAILS
$config["host_log"]     = "localhost";
$config["usuario_log"]  = "devtransito_log";
$config["senha_log"]    = "0mK99pm?6";
$config["database_log"] = "devtransito_log";
//$config["link_chat"] = "https://chat.directtalk.com.br/static/hi-chat/chat.js?widgetId=9129cad0-7c8e-4a28-8e6e-359eb105a189&callback=hiChatCallback";
//$config["link_chat_cfc"] = "https://chat.directtalk.com.br/static/hi-chat/chat.js?widgetId=068d0062-1c91-4a04-ad19-67934fc61a6e&callback=hiChatCallback";
$config["link_whatsapp"] = "https://api.whatsapp.com/send/?phone=554888111125&text&app_absent=0";
$config["link_whatsapp_cfc"] = "https://api.whatsapp.com/send/?phone=554888111125&text&app_absent=0";
//Vídeos virão de servidor externo quando false
$config['videoteca_local'] = false;

//Array que traz os endereços, as url's serão escolhidas randomicamente no momento de exibir os vídeos
$config['videoteca_endereco'] = array(
    0 => "",
);

$config['marcaDaguaIframeVideo'] = true; //Indica se exibirá marca d'água no vídeo do youtube/vimeo no painel do aluno
$config['bloqueioConteudo'] = true; //Indica se bloqueia o conteúdo da rota contra cópia.

$config['filtro_matricula_boleto_semana'] = true;

$config['reconhecimento']['matriculas_liberadas'] = ['11303', '11314', '11321', '11071', '11674', '11006', '11533', '11783', '11639', '11541', '11821', '11779', '12335', '12429', '11496', '12549', '13082', '12830'];

// Nova configuração de Servidor de Email
$config['email_naoresponda'] = 'naoresponda@oraculomail.com.br';
$config['email_host'] = 'smtp.gmail.com';
$config['email_port'] = '587';
$config['email_secure'] = 'tls';
$config['email_username'] = 'jessihelem.santos@gmail.com';
$config['email_password'] = 'ynemqwwoftgaspie';

$config['dias_vencimento_conta'] = '30';//Dias para o vencimento da conta da matrícula

//Dados do Pagar.me
$config['pagarme']['habilitar_checkout'] = false;
$config['pagarme']['postback_url'] = $config['urlSistema'] . '/api/set/pagarme';
$config['pagarme']['api_key'] = 'ak_test_B2YGP3B9IpIvjr1L7wz9nSHdRA2iG6';
$config['pagarme']['encryption_key'] = 'ek_test_ZY99QXhNnf3UyC5wOHkE4uKrKl5bhg';
$config['pagarme']['dias_vencimento'] = 5;
$config['pagarme']['multa_atraso'] = 2;//multa por atraso em %
$config['pagarme']['juros_atraso'] = 1/30;//Juros por dia de atraso em %

// Datavalid validações
$config['datavalid']['probabDefault'] = 0.85;


//Datavalid Credenciais DEV
$config['datavalid']['demo'] = true;
$config['datavalid']['consumer_key'] = 'CTTslODqzXejXcw8WIBOQWmp5aka';
$config['datavalid']['consumer_secret'] = 'Hdw903ht38B0L8HFDLhp4bQuL0ga';


/* //Datavalid Credenciais PRODUÇÃO ICETRAN
$config['datavalid']['demo'] = false;
$config['datavalid']['consumer_key'] = 'KWVrN0NX3aGcBKPsfU3HgafWU18a';
$config['datavalid']['consumer_secret'] = 'Dg0Tjc4d2rVEpwI3sTBzWQJHSq4a';
$config["datavalid"]["limite_tentativas"] = 20; */

$config["datavalid"]["plano"] = 'entidade';
$config["datavalid"]["versao"] = 2;

// Datavalid URLs
$config['datavalid']['urlToken'] = "https://apigateway.serpro.gov.br/token";
$config['datavalid']['urlProducao'] = "https://gateway.apiserpro.serpro.gov.br/datavalid-entidade/";
$config['datavalid']['urlDemostracao'] = "https://gateway.apiserpro.serpro.gov.br/datavalid-demonstracao/";

// Datavalid Limites
$config['datavalid']['limite_tentativas'] = 3;

// Datavalig no logout
$config['datavalid']['logout'] = true;

// Datavalid Imagem
$config['datavalid']['caminho_imagem_tutorial'] = "/assets/aluno/img/reconhecimento-tutorial-ibrep.jpg";
// Dados do PagSeguro
$config['pagSeguro']['url'] = 'https://sandbox.pagseguro.uol.com.br';
$config['pagSeguro']['urlWs'] = 'https://ws.sandbox.pagseguro.uol.com.br';
$config['pagSeguro']['urlStc'] = 'https://stc.sandbox.pagseguro.uol.com.br';
$config['pagSeguro']['redirectURL'] = $config['urlSistema'] . '/api/set/pagseguro/retorno';
$config['pagSeguro']['notificationURL'] = $config['urlSistema'] . '/api/set/pagseguro';

// Dados do FastConnect
$config['fastConnect']['url'] = 'https://api-sandbox.fpay.me';
$config['fastConnect']['url_producao'] = 'https://api.fpay.me';
$config['fastConnect']['url_sandbox'] = 'https://api-sandbox.fpay.me';
$config['fastConnect']['url_retorno'] = 'https://api.fpay.me';
$config['fastConnect']['cliente_code'] = 'FC-363';
$config['fastConnect']['client_key'] = 'a843cd883bd27a55d58b602f5faf7574';

$config['validacao_cron_faturas']['semana'] = false;

//Dados do API da reconhecimento facial
$config['reconhecimento']['range_minimo'] = 0.7;
$config['https'] = false;
$config['cfc_aviso'] = true;
//URL que a loja irá redirecionar quando preciso selecionar outro CFC
$config['urlSiteLoja'] = 'https://dev-transito.alfamaoraculo.com.br/';

$config['matricula_cfc']['idoferta'] = 8;
$config['matricula_cfc']['idatendente'] = 7;
$config['matricula_cfc']['idturma'] = 7;

$config['alerta_requisitos_alunos'] = true; //Ativa alertas no painel aluno referente requisitos pendentes(Meus cursos e Ambiente de estudos)

// Altera código do curso "28" na emissão do XML para evitar conflito
$config['conflitoCodigoCurso'] = true;

require_once 'config.detran.php';
/*
error_reporting( E_ALL & ~E_NOTICE & ~E_USER_NOTICE );

// Habilita a exibição de erros
ini_set("display_errors", 1);
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);