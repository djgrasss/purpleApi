<?php
/**
* Persistence object layer based on file abstract class
* @author eka808
* @version 0.2
*/
abstract class persistenceLayer
{
    /* Base path of where the class can store data */
    private $basePath;
    /* Key used to store data of application in persistence */
    private $PersistenceKey = 'DATACACHE';
    /* Persistence data */
    public $PersistenceDataObj;

    /** 
     * Default constructor
    **/
    function __construct($cacheFolder)
    {
        $this->basePath = $_SERVER['DOCUMENT_ROOT'] . $cacheFolder; 

        $this->refresh();
        if ($this->PersistenceDataObj == null)
        {
            $obj = new stdClass();
            $this->PersistenceDataObj = new PersistenceEntity($obj, time());
            $this->store($this->PersistenceKey, $this->PersistenceDataObj);
        }
    }

    /** 
     * Get the full URI of the file used for persistence
     * @param key : the key of the persistence
     * @return the full uri as string
    **/	
    function getUri($key)
    {
        return $this->basePath . $key . '.txt';
    }

    /**
     * Get the creation time of the file
     * @param key : the key of the persistence
     * @return : timestamp of file creation time
    **/
    function getCreationTime()
    {
        return filemtime($this->getUri($this->PersistenceKey));
    }

    /** 
     * Store the persistence in the file
    **/	
    function store()
    {
        $this->PersistenceDataObj->generationTime = time();
        file_put_contents($this->getUri($this->PersistenceKey), serialize($this->PersistenceDataObj));
    }

    /** 
     * Refresh the local object with persistence file
    **/	
    function refresh()
    {
        $fileUri = $this->getUri($this->PersistenceKey);

        if (file_exists($fileUri))  
        {
            $this->PersistenceDataObj = unserialize(file_get_contents($fileUri));
        }
    }
}
?>