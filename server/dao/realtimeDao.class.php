<?php
//Resolve the abstract class
include_once('classes/persistenceLayer.class.php');

/**
 * DAO implementation for using persistence layer
 * @author eka808
**/
class realtimeDao extends persistenceLayer implements IDao
{

	/**
	 * Refresh the cache and return the entities
	**/
	public function listFruitEntities()
	{
		$this->refresh();
		return $this->PersistenceDataObj;
	}

	/**
	 * Refresh the cache and return the entities using a long time pooling time limit
	**/
	public function listFruitEntitiesLongTimePooling($clientListTime)
	{
	    //Long time polling stuff : user wait if his version is up to date
	    do {
	        $localTime = $this->getCreationTime();
	        clearstatcache();
	        usleep(10000);
	    } while ($clientListTime == $localTime);
	    
	    return $this->listFruitEntities();
	}

	/**
	 * Add an entity and save to the cache
	**/
	public function addFruitEntity($FruitEntity)
	{
		$FruitEntity->Id = $this->GetNextId();
		$this->PersistenceDataObj->data->FruitList[] = $FruitEntity;
        $this->store();
	}

	/**
	 * Remove entity and save to the cache
	**/
	public function removeFruitEntity($fruitId)
	{
		foreach($this->PersistenceDataObj->data->FruitList as $key => $value)
		if ($value->Id == $fruitId)
		{
			unset($this->PersistenceDataObj->data->FruitList[$key]);
			$this->store();
			break;
		}
	}

	/** Get the next entity id available **/
	private function GetNextId()
	{
		$max = 0;
		foreach ($this->PersistenceDataObj->data->FruitList as $key => $value) 
		if ($value->Id > $max)
			$max = $value->Id;
		return ++$max;
	}	
}
?>