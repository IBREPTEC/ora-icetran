<?php

$config["tituloEmpresa"] = "ICETRAN";
$config["websiteEmpresa"] = "http://www.alfamaoraculo.com.br";
$config["tituloSistema"] = "Oráculo Trânsito";
$config["urlSistema"] = "https://icetran.alfamaoraculo.com.br";
$config["urlSistemaFixa"] = "icetran.alfamaoraculo.com.br";
$config["urlSite"] = "http://www.alfamaoraculo.com.br/";
$config['urlSiteCliente'] = 'https://icetran.com.br/';
$config["emailEsqueci"] = "oraculo@alfamaoraculo.com.br";
$config["chaveLogin"] = "oraculo.ibreptran#2016";
$config["tamanho_upload_padrao"] = 8388608;
$config['cadastrar_sindicato'] = true;
$config['cadastrar_mantenedora'] = true;
$config['integrado_com_sms'] = true;
/// DADOS DE ACESSO API SMS
$config["loginSMS"] = "oraculo_ibrep";
$config["tokenSMS"] = "68f46efbfb8a1293c300a0ff6a92925490e52648b1158c18f85a88e89ec70bcbe23e97608949bdb71ade8e3f2e42428efbf17362938747b2a343972740d28255";
$config["linkapiSMS"] = "http://sms.alfamaweb.com.br/";
$config['limite_emails_mailing'] = NULL;
//$config["link_integracao_cielo"] = "https://s.alfamaweb.com.br/oraculo/pagamento/";

$config["emailSistema"] = "pedagogico@icetran.com.br";
$config["emailMailing"] = "monitoria1@icetran.com.br";

//CONFIGURAÇÃO DE HOST USUARIO E SENHA DO BANCO DO LOG DE EMAILS
$config["host_log"]     = "localhost";
$config["usuario_log"]  = "icetran_ora";
$config["senha_log"]    = "7s^m6y8X";
$config["database_log"] = "icetran_oraculo_log";

//$config["link_chat"] = "https://chat.directtalk.com.br/static/hi-chat/chat.js?widgetId=866b43e7-a7e9-4114-a776-82f47872142d&callback=hiChatCallback";
//$config["link_chat_cfc"] = "https://chat.directtalk.com.br/static/hi-chat/chat.js?widgetId=068d0062-1c91-4a04-ad19-67934fc61a6e&callback=hiChatCallback";

$config["link_whatsapp"] = "https://api.whatsapp.com/send/?phone=558000069090&text=Ol%C3%A1&app_absent=0";
$config["link_whatsapp_cfc"] = "https://api.whatsapp.com/send/?phone=558000069090&text=Ol%C3%A1&app_absent=0";

//Vídeos virão de servidor externo quando false
$config['videoteca_local'] = false;

//Array que traz os endereços, as url's serão escolhidas randomicamente no momento de exibir os vídeos
$config['videoteca_endereco'] = array(
    0 => "http://www.ondemand.alfamaoraculo.com/icetran",
);

// Nova configuração de Servidor de Email
/*$config['email_naoresponda'] = 'naoresponda@oraculomail.com.br';
$config['email_host'] = 'mail.oraculomail.com.br';
$config['email_port'] = '465';
$config['email_secure'] = 'ssl';
$config['email_username'] = 'naoresponda@oraculomail.com.br';
$config['email_password'] = 'RvkfGc!j5q4604$1';*/

$config['email_naoresponda'] = 'naoresponda@icetran.com.br';
$config['email_host'] = 'smtp.sendgrid.net';
$config['email_port'] = '587';
$config['email_secure'] = 'tls';
$config['email_username'] = 'apikey';
$config['email_password'] = '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';

/*
$config['email_naoresponda'] = 'naoresponda@oraculomail.com.br';
$config['email_host'] = 'email-smtp.us-west-2.amazonaws.com';
$config['email_port'] = '587';
$config['email_secure'] = 'tls';
$config['email_username'] = 'AKIAITZYCUM7H5VFXKKA';
$config['email_password'] = 'BADlJ2Q5ME9/ZuRGDGJfF5ZgGIFE4nSH+apCFaQQd6SM';
*/
$config['filtro_matricula_boleto_semana'] = false;//Filtra os matriculados da semana anterior
$config['dias_vencimento_conta'] = '5';//Dias para o vencimento da conta da matrícula

//Dados do Pagar.me
$config['azure']['url'] = 'https://oraculo-icetran-face.cognitiveservices.azure.com/';
$config['azure']['api_key'] = '15576487553444c4bd7c8d442c27bcc6

';

//Dados do Pagar.me
$config['pagarme']['postback_url'] = $config['urlSistema'] . '/api/set/pagarme';
$config['pagarme']['api_key'] = 'ak_live_SCgwI0nte2ElgeAmpc1eSedHTGnnQb';
$config['pagarme']['encryption_key'] = 'ek_live_1rzYyjMZ9hPhR70npZeM4rcOxCSXlZ';
$config['pagarme']['habilitar_checkout'] = false;
$config['pagarme']['dias_vencimento'] = 5;
$config['pagarme']['multa_atraso'] = 2;//multa por atraso em %
$config['pagarme']['juros_atraso'] = 1/30;//Juros por dia de atraso em %

//Datavalid Credenciais
$config['datavalid']['demo'] = false;
$config['datavalid']['consumer_key'] = 'KWVrN0NX3aGcBKPsfU3HgafWU18a';
$config['datavalid']['consumer_secret'] = 'Dg0Tjc4d2rVEpwI3sTBzWQJHSq4a';
$config["datavalid"]["limite_tentativas"] = 20;
$config["datavalid"]["plano"] = 'entidade';
$config["datavalid"]["versao"] = 2;
$config['datavalid']['probabDefault'] = 0.85;
// Datavalid Imagem
$config['datavalid']['caminho_imagem_tutorial'] = "/assets/aluno/img/reconhecimento-tutorial-ibrep.jpg";
// Datavalig no logout
$config['datavalid']['logout'] = false;

//Dados do PagSeguro
$config['pagSeguro']['url'] = 'https://pagseguro.uol.com.br';
$config['pagSeguro']['urlWs'] = 'https://ws.pagseguro.uol.com.br';
$config['pagSeguro']['urlStc'] = 'https://stc.pagseguro.uol.com.br';
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
$config['reconhecimento']['matriculas_liberadas'] = ['310','309','313','332','1142'];

//URL que a loja irá redirecionar quando preciso selecionar outro CFC
$config['urlSiteLoja'] = 'http://www.icetran.com.br/';

$config['cfc_aviso'] = true; 
$config["emailParceria"] = "parceria.ead@icetran.com.br";
$config["telefone"] = "0800 006 9090";

$config['alerta_requisitos_alunos'] = true; //Ativa alertas no painel aluno referente requisitos pendentes(Meus cursos e Ambiente de estudos)

require_once 'config.detran.php';

//if (extension_loaded('newrelic'))
//{ // && strpos($_SERVER['SERVER_NAME'], 'desenvolvimento') !== false
//    $url = strip_tags(rawurldecode($_SERVER["REQUEST_URI"]));
//    $get_array = explode("?", $url);
//    $url = explode("/", $get_array[0]);
//    $newrelic_appname = 'ORA/' . strtoupper($url[1]);
//    newrelic_set_appname($newrelic_appname);
//    newrelic_name_transaction($get_array[0]);
//    //newrelic_start_transaction($get_array[0]);
//    $conta = explode(".", $config["urlSistema"]);
//    $conta[0] = str_replace('http://', '', $conta[0]);
//    $conta[0] = str_replace('https://', '', $conta[0]);
//    newrelic_add_custom_parameter('AMBIENTE', $conta[0]);
//	newrelic_add_custom_parameter('POST', $_POST);
//	newrelic_add_custom_parameter('GET', $_GET);
//}
if (mb_strpos($_SERVER["REQUEST_URI"], 'debug') !== false) {
    error_reporting(E_ALL ^ E_NOTICE);

    /* Habilita a exibição de erros */
    ini_set("display_errors", 1);
}