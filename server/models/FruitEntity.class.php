<?php
/**
 * Define what is a fruit :)
**/
class FruitEntity
{
    /**
     * Max id of the activities
     * Used by constructor to generate current Id
     */
    public $Id;
    public $Name;
    public $Quantity;
    public $TypeId;

    function __construct($_name=null, $_quantity=null, $_typeId=null, $_id=null)
    {
        if ($_id != null)
            $this->Id = $_id;
        if ($_name != null)
            $this->Name = $_name;
        if ($_quantity != null)
            $this->Quantity = $_quantity;
        if ($_typeId != null)
            $this->TypeId = $_typeId;
    }

    function setFromArray($_array)
    {
        //$this->Id = $_array['Id'];
        $this->Name = $_array['Name'];
        $this->Quantity = $_array['Quantity'];
        $this->TypeId = $_array['TypeId'];
    }
}
?>