<?php 
/**
 * 
**/
class fruitDao extends abstractDao implements IDao
{

	private $tableName = 'fruits';
	/**
	 * Refresh the cache and return the entities
	**/
	public function listFruitEntities() 
	{

		$fruitList = $this->context->listEntities($this->tableName);

		// Projection
		$returnObj = new stdClass();
		$returnObj->FruitList = Array();
		foreach ($fruitList as $key => $value) {
			$returnObj->FruitList[] = 
				new FruitEntity(
					$value->Name, 
					$value->Quantity, 
					$value->TypeId,
					$value->Id
				);
		}

    	return new PersistenceEntity($returnObj, time());


	}

	/**
	 * Refresh the cache and return the entities using a long time pooling time limit
	**/
	public function listFruitEntitiesLongTimePooling($clientListTime)
	{
	    /*//Long time polling stuff : user wait if his version is up to date
	    do {
	        $localTime = $this->context->getCreationTime();
	        clearstatcache();
	        usleep(10000);
	    } while ($clientListTime == $localTime);
	    
	    return $this->listFruitEntities();*/
	    return 'NOTAVAILABLE';
	}	


	/**
	 * Add an entity
	**/
	public function addFruitEntity($FruitEntity)
	{
		$this->context->addEntity($this->tableName, $FruitEntity);
	}

	
	/**
	 * Remove an entity
	**/
	public function removeFruitEntity($fruitId)
	{
		$this->context->removeEntity($this->tableName, $fruitId);
	}
}
?>