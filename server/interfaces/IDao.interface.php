<?php
/**
 * Interface used to define the methods that the DAO have to implement
**/
interface IDao
{
	public function listFruitEntitiesLongTimePooling($clientListTime);
	public function listFruitEntities();
	public function addFruitEntity($FruitEntity);
	public function removeFruitEntity($fruitName);
}
?>