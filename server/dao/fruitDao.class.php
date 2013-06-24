<?php 
/**
 * 
**/
class fruitDao extends abstractDao
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

    	return new PersistenceEntity($returnObj, $this->getEntityListTimestamp());
	}

	/**
	 * Refresh the cache and return the entities using a long time pooling time limit
	**/
	public function listFruitEntitiesLongTimePooling($clientListTime)
	{
	    //Long time polling stuff : user wait if his version is up to date
	     do {
	        $localTime = $this->getEntityListTimestamp();
	        clearstatcache();
	        usleep(300000);
	     } while ($clientListTime == $localTime);
	    return $this->listFruitEntities();
	}	


	/**
	 * Add an entity
	**/
	public function addFruitEntity($FruitEntity)
	{
		$this->context->addEntity($this->tableName, $FruitEntity);
		$this->setEntityListTimestamp();
	}

	
	/**
	 * Remove an entity
	**/
	public function removeFruitEntity($fruitId)
	{
		$this->context->removeEntity($this->tableName, $fruitId);
		$this->setEntityListTimestamp();
	}




	//
	// TODO : this is crap, extract $_SESSION to use a persistence store abstraction
	//
	public function getEntityListTimestamp()
	{
		/*if ($this->persistence->fruitListTimeStamp == null)
			$this->setEntityListTimestamp();*/
		return $this->persistence->fruitListTimeStamp;
	}

	private function setEntityListTimestamp()
	{
		return $this->persistence->fruitListTimeStamp = time();
	}
}
?>