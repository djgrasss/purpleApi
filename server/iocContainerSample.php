<?php
include_once('classes/purpleDebug.class.php');
include_once('classes/purpleIoc.class.php');

$container = new purpleIoc();

$sharedObj1 = new stdClass();
$sharedObj1->Id = 1;

$container->sharedObj = $sharedObj1;
purpleDebug::print_r($container->sharedObj);

$container->sharedObj->Id = 808;
purpleDebug::print_r($container->sharedObj);

$sharedObj1->Id = 25;
purpleDebug::print_r($container->sharedObj);
?>