<?php

header('Content-type: text/html; charset=UTF-8');

require_once "email.php";

$email = new Email();
$email->setFrom("from_email_address", "from_name");
$email->addReplyTo("reply_to_email_address", "reply_to_name");
$email->addTo("to_email_address", "to_name");
$email->addCc("cc_email_address");
$email->addBcc("bcc_email_address");

$email->setSubject("Email PHP library");
$email->setMessage("
Hello,

This is a test email sent by the Email PHP library.

Have a wonderful day!
");

$r = $email->send();
echo $r;


