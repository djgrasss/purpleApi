<?php
include_once('classes/idiorm.php');

/**
 * DAO implementation for using database storing via Idiorm (https://github.com/j4mie/idiorm)
 * @author eka808
**/
class databaseDao implements IDao
{
	/* Name of the table used */
	private $TableName = 'fruits';

	/**
	 * Default constructor
	 * Configure the idiorm db connection
	 * @param pdoConnectionString : the connection string of PDF to access the db
	**/
	function __construct($pdoConnectionString)
	{
		// Connect to the demo database file
    	ORM::configure($pdoConnectionString);
    	$db = ORM::get_db();

    	// Create the table if this one not exists in the db
    	$db->exec("
    		--DROP TABLE " . $this->TableName . ";
	    	CREATE TABLE IF NOT EXISTS " . $this->TableName . " ( 
	    		Id INTEGER PRIMARY KEY, 
	    		Name TEXT,
	    		Quantity INTEGER,
	    		TypeId INTEGER
	    	);
    	");
	}

	/**
	 * Return the entities
	**/
	public function listFruitEntities()
	{
		$fruitList = ORM::for_table($this->TableName)->find_many();
		
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
	 * Return the entities using a long time pooling time limit
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
	 * Add an entity and save to db
	**/	
	public function addFruitEntity($FruitEntity) 
	{
		$fruit = ORM::for_table($this->TableName)->create();

		// Fetch properties of source object into the one to store in db
		foreach ($FruitEntity as $key => $value)
		if ($key != 'Id')
			$fruit->$key = $value;	

		$fruit->save();
	}

	/**
	 * Remove entity from db
	**/	
	public function removeFruitEntity($fruitId) 
	{
		$fruit = ORM::for_table($this->TableName)->where_equal('Id', $fruitId)->delete_many();
	}
}
?>