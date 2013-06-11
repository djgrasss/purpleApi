<?php
include_once('models/FruitEntity.class.php');
include_once('classes/purpleTools.class.php');
include_once('classes/purpleDebug.class.php');
purpleTools::initDebug();

/*

$obj = new FruitEntity('Banana',15);
$obj2 = new stdClass();

foreach ($obj as $key => $value)
if ($key != 'Id')
	$obj2->$key = $value;	

PurpleDebug::print_r($obj);
PurpleDebug::print_r($obj2);

 */



class foo {
	public function bar() { return 'bar'; }
	public function qux($tring) { echo 'bar'.$tring; }
}


$foo = new foo();

//call_user_method('c',$self);
$return1 = purpleTools::functionCaller($foo,'bar');
$return2 = purpleTools::functionCaller($foo,'qux','zeljlk');


PurpleDebug::print_r('return1:'.$return1);
PurpleDebug::print_r('return2:'.$return2);

/*
$routing['ADDFRUIT'] = addfruitAction;
$routing['FRUITLIST'] = fruitlistAction;

$action = 'FRUITLIST';
$actionMethod = $routing[$action];

try
{
	purpleTools::functionCaller($actionMethod);
}
catch (PurpleUnknownRouteException $e)
{
	echo 'Unknown action';
}
*/


//purpleTools::functionCaller(addfruitAction, 'helloWorld!');
//purpleTools::functionCaller(fruitlistAction);
//purpleTools::functionCaller('fruitlistAction','helloWorld2');

//PurpleDebug::print_r('allolemonde');
/*
PurpleDebug::print_r();
*/
?>