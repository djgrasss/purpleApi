<?php
/**
 * Persistence layer used to make application scope variables
 * @version 0.1
 * @author eka808 - http://yoannmagli.ch
 */
class persistenceDao
{
	private $persistenceArray;
	private $filename;

	/**
	 * Default constructor
	 * @param string $filename persistence file uri
	 */
	public function __construct($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * Set a persistence property
	 * @param string $key property name
	 * @param string $property property value
	 */
	public function __set($key, $obj)
	{
		$this->persistenceArray[$key] = $obj;
		$this->saveFile();
	}

	/**
	 * Get a persistence property
	 * @param  string $key property name
	 * @return property value if existing or null if not
	 */
	public function __get($key)
    {
    	$this->loadFile();
    	if (array_key_exists($key, $this->persistenceArray))
        	return $this->persistenceArray[$key];
        return null;
    }

	/**
	 * Save the persistence to file 
	 */
	private function saveFile()
	{
		file_put_contents($this->filename, serialize($this->persistenceArray));
	}

	/**
	 * Get the persistence if existing from file or define empty array
	 */
	private function loadFile()
	{
		if (file_exists($this->filename))
			$this->persistenceArray = unserialize(file_get_contents($this->filename));
		else
			$this->persistenceArray = [];
	}
}
?>