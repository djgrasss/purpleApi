<?php
//
// Let's try to reflect some stuff
//
include_once('jsonapi.php');
include_once('classes/purpleDebug.class.php');


class purpleReflect
{

	private $reflectObj;

	private function getMethodsArray($className, $type)
	{	
		$reflect = new ReflectionClass($className);	

		$methods = [];

		foreach ($reflect->getMethods($type) as $functionObj)
		if ($functionObj->class == $className)
		{
			$methods[$functionObj->name] = $this->getParametersArray($functionObj);
		}

		return $methods;
	}

	private function getParametersArray($functionObj)
	{
		$returnObj = [];
		foreach ($functionObj->getParameters() as $paramObj)
			$returnObj[] = $paramObj->name;
		return $returnObj;
	}

	private function getPropertiesArray($className, $type)
	{
		$reflect = new ReflectionClass($className);	

		$properties = [];

		foreach ($reflect->getProperties($type) as $value)
		if ($value->class == $className)	
			$properties[$value->name] = '';
		return $properties;
	}


	//
	// Public
	//

	function __construct()
	{
	}

	public function getClassInfos($className)
	{
		//echo '<pre>'.$reflect.'<pre>';
		$returnObj = new stdClass();
		$returnObj->publicMethods = $this->getMethodsArray($className, ReflectionMethod::IS_PUBLIC);
		$returnObj->privateMethods = $this->getMethodsArray($className, ReflectionMethod::IS_PRIVATE);
		$returnObj->staticMethods = $this->getMethodsArray($className, ReflectionProperty::IS_STATIC);
		$returnObj->publicProperties = $this->getPropertiesArray($className, ReflectionProperty::IS_PUBLIC);
		$returnObj->privateProperties = $this->getPropertiesArray($className, ReflectionProperty::IS_PRIVATE);
		$returnObj->staticProperties = $this->getPropertiesArray($className, ReflectionProperty::IS_STATIC);
		return $returnObj;
	}

}






$reflect = new purpleReflect();

purpleDebug::print_r($reflect->getClassInfos('jsonApi'));






?>