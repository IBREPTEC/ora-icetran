<?php
error_reporting(E_ALL ^E_NOTICE ^E_STRICT ^E_WARNING ^E_DEPRECATED);
ini_set('display_errors', 1);

$nomeDe = 'Sistema';
$emailDe = 'wallacew@cvcrm.com.br';
$nomePara = 'Wallace';
$emailPara = 'wallacew@cvcrm.com.br';
if ($_GET['para_email']) {
	$emailPara = $_GET['para_email'];
}

require_once 'classes/PHPMailer/class.phpmailer.php';
$config['email_naoresponda'] = 'naoresponda@oraculomail.com.br';
$config['email_host'] = 'mail.oraculomail.com.br';
$config['email_port'] = '465';
$config['email_secure'] = 'ssl';
$config['email_username'] = 'naoresponda@oraculomail.com.br';
$config['email_password'] = 'RvkfGc!j5q4604$1';

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_WARNING ^ E_DEPRECATED);
ini_set('display_errors', 1);

$mail = new \PHPMailer();

$mail->Encoding = "base64";
$mail->setLanguage('br');
$mail->CharSet = 'utf-8';

$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->SMTPAuth = true;

$mail->Username = $config['email_username'];
$mail->Password = $config['email_password'];

$mail->Host = $config['email_host'];
$mail->Port = $config['email_port'];
$mail->SMTPSecure = $config['email_secure'];

$mail->isHTML(true);

$layoutHTML = "E-mail de teste";

$mail->setFrom($config['email_naoresponda'], $nomeDe);
$mail->addReplyTo($emailDe, $nomeDe);
$mail->addAddress($emailPara, $nomePara);
$mail->Subject = 'E-mail de teste';
$mail->Body = $layoutHTML;

var_dump($mail->send());

$mail->ClearAllRecipients();
$mail->ClearAttachments();

echo ' ***** FIM *****';
