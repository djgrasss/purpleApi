<?php
abstract class abstractDao
{
	protected $context; //context stored in container reference
	protected $persistence; // persistence context

	function __construct()
	{
		// Make a REFERENCE between  the data context stored in IoC container and local variable
		$this->context = &jsonApi::$container->context;
		$this->persistence = &jsonApi::$container->persistence;
	}
}
?>