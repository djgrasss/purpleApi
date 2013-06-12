<?php
include_once('server/models/FruitEntity.class.php');
include_once('server/classes/purpleTools.class.php');
include_once('server/classes/purpleDebug.class.php');
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


/*
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

/*
PurpleDebug::print_r();
*/
$obj = (object)['foo' => "hi there", 'x' => [1,2]	];
$objAsArray = (array)$obj;
$objAsQueryString = http_build_query($objAsArray, '', '&amp;', PHP_QUERY_RFC3986);
$encryptedPassword="123456";
$objHash = hash_hmac('sha256', $objAsQueryString, $encryptedPassword);

echo ("ServerSide");
echo ("<br />");
echo($objAsQueryString);
echo ("<br />");
echo($objHash);
echo ("<br />");

//PurpleDebug::print_r('allolemonde');
?>
<script src="client/js/knockout-2.2.1.js"></script>
<script src="client/js/purpleFunctions.js"></script>
<script src="client/js/jquery-1.9.1.min.js"></script>
<script src="client/js/cryptojs/hmac-sha256.js"></script>
<script type='text/javascript'>
    $(function () {
        var clientQueryString = ko.Purple.serialize({foo: "hi there"/*, bar: { blah: 123, quux: [1, 2, 3] }*/})
        var hash = CryptoJS.HmacSHA256(clientQueryString, "123456");
        $('#clientstring').html(''+clientQueryString);
        $('#clienthash').html(''+hash);
    });
</script>

Client Side
<div id='clientstring'></div>
<div id='clienthash'></div>
