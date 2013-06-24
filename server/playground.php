<?php
include_once('classes/purpleDebug.class.php');
include_once('dao/persistenceDao.class.php');

$persistence = new persistenceDao('foobar.txt');
//$persistence->foo = 'bar';
purpleDebug::print_r($persistence->foo);
?>