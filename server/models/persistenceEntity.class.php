<?php
/**
 * Persistence entity used to represent a persistence
**/
class PersistenceEntity 
{
    /* Generation time
     * binded by user for comparison between client and server version 
     */
    public $generationTime;

    /* Data stored in persistence*/
    public $data;

    /**
     * Default constructor
    **/
    function __construct($_data, $_generationTime)
    {
        $this->data = $_data;
        $this->generationTime = $_generationTime;
    }
}
?>