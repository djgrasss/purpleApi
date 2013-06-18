<?php
/** 
 * Abstract code of the json API
 * Seems logical to note that no code related to a project should be here ...
**/
abstract class abstractJsonApi
{
    
	/**
	 * Default constructor
	**/
    function __construct()
    {
        // Dependencies
        $this->ResolveDependencies();

        // Debug init
        purpleTools::initDebug();

        // Render type
        header('Content-type: application/json');
    }

    /**
     * Main routing function
     */
    protected function route()
    {
        $action = strtoupper(purpleTools::sanitizeString($_GET['action']));
        //$actionMethod = $this->routing[$action]; //for strong routing
        $actionMethod = strtolower($action).'Action';

        try
        {
            $objectToReturn = purpleTools::functionCaller($this,$actionMethod);
        }
        catch (PurpleUnknownRouteException $e)
        {
            $objectToReturn = purpleTools::functionCaller($this,$routing['FRUITLIST']);
        }

        echo json_encode($objectToReturn);
    }

	/**
	 * Resolve dependencies of the api
	**/
    private function ResolveDependencies()
    {
        foreach ($this->dependencies as $value)
            include_once($value);
    }
}
?>