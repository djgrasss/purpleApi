<?php
class purpleIoc
{
	private $dependencies = [];

	public function __construct($iocDeclarationsArray = null)
	{
		if ($iocDeclarationsArray != null)
		foreach ($iocDeclarationsArray as $key => $value)
            $this->$key = $value;
	}

	public function __set($key, $objToAdd)
	{
		$this->dependencies[$key] = $objToAdd;
	}

	public function __get($key)
    {
        return $this->dependencies[$key];
    }

}
?>