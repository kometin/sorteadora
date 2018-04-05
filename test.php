<?php

ini_set('display_errors', 1);

//require_once('lib/ext.php');

require_once('lib/Mail.php');

echo "TEST MAIL "; 

$mail = new Mail();
$mail->address_from = "admin@ingeniumservices.com.mx";
$mail->name_from = "Ingenium Services";
$mail->smpt_port = 465;
$mail->smtp_host = "email-smtp.us-east-1.amazonaws.com";
$mail->smtp_user = "AKIAJZEN4MTJE2FOKAEQ";
$mail->smtp_pwd = "AlpLF8lrQRcgKQ7htbmi1RTM4Z9VyktC9JlmTRWq5Ibx";
$mail->smtp_secure = "tls";
$mail->subject = "new test";
$mail->text = "please work";

$mail->addAdrress("cbaltazarc@guanajuato.gob.mx");

echo $mail->Send();



