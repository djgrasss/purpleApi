<?php
include_once('classes/purpleDebug.class.php');
include_once('classes/purpleTools.class.php');
include_once('classes/mailSender.class.php');

$email = new mailSender();
$email
	->setTitle('foo')
	->setObject('bar')
	->setContent('lékdjlkéf
jsàlkjsalkéjdc
$xél<jkl')
	->setEmails(['foo@bar.com','qux@baz.com'])
	->sendEmail();

?>