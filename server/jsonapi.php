<?php
include_once('classes/abstractJsonApi.class.php');
/*
                               .__            _____         .__ 
    ______  __ _______________ |  |   ____   /  _  \ ______ |__|
    \____ \|  |  \_  __ \____ \|  | _/ __ \ /  /_\  \\____ \|  |
    |  |_> >  |  /|  | \/  |_> >  |_\  ___//    |    \  |_> >  |
    |   __/|____/ |__|  |   __/|____/\___  >____|__  /   __/|__|
    |__|                |__|             \/        \/|__|       

 * API provider of purple framework
 * @version : 0.3
 * @author : eka808 - http://www.yoannmagli.ch
 * @todo : long time pooling for database store
**/
class jsonApi extends abstractJsonApi
{
    /* DAO object */
    private $dao;

    /* The files to include to play with */
    protected $dependencies =
        array(
            'classes/purpleDebug.class.php'
            ,'interfaces/IDao.interface.php'
            ,'models/FruitEntity.class.php'
            ,'models/PersistenceEntity.class.php'
            ,'classes/purpleTools.class.php'
            //,'classes/purpleUi.class.php'
            //,'classes/purpleMath.class.php'
            ,'dao/realtimeDao.class.php'
            ,'dao/databaseDao.class.php'
        );

    /**
     * Place here all your database connection stuff
    **/
    private function SetDbStuff()
    {
        $this->dao = new realtimeDao('/purpleApi/server/cache/');
        //$this->dao = new databaseDao('sqlite:../server/sqlitedb/fruits.sqlite');
    }

    /**
     * Default constructor 
    **/
    function __construct()
    {
        $this->ResolveDependencies();
        $this->SetDbStuff();
        parent::__construct();
    }

    /** 
     * AUTH : get private key
    **/
    public function getprivatekeyAction()
    {        
        $username = strtolower(purpleTools::sanitizeString($_GET['username']));
        $encryptedPassword = purpleTools::sanitizeString($_GET['encryptedPassword']);

        //TMP
        $users[] = (object)['username'=>strtolower('eka808'), 'encryptedPassword'=>sha1('foobar')];
        //purpleDebug::print_r($users);
        //purpleDebug::print_r([$username, $encryptedPassword]);
        //TMP

        foreach ($users as $value) 
        if ($value->username == $username)
        if ($value->encryptedPassword == $encryptedPassword)
        {
            $obj = new stdClass();
            $obj->PrivateKey = "1";
            return $obj;
        }
        return "IncorrectAuthParameters";
    }

    /**
     * List fruits action
    **/
    public function fruitlistAction() 
    {
        return $this->dao->listFruitEntities();
    }

    /**
     * List fruits action for long time pooling
    **/
    public function fruitlistpersistentAction() 
    {
        $clientListTime = purpleTools::sanitizeString($_GET['generationTime']);
        return $this->dao->listFruitEntitiesLongTimePooling($clientListTime);
    }
    
    /**
     * Add fruit action
    **/
    public function addfruitAction() 
    {
        $fruitName = purpleTools::sanitizeString($_POST['Name']);
        $fruitQuantity = purpleTools::sanitizeString($_POST['Quantity']);
        $fruitTypeId = purpleTools::sanitizeString($_POST['TypeId']);
        $this->dao->addFruitEntity(new FruitEntity($fruitName, $fruitQuantity, $fruitTypeId));
        return "Ok";
    }

    /**
     * Remove fruit action
    **/
    public function removefruitAction() 
    {
        $fruitId = purpleTools::sanitizeString($_POST['FruitId']);
        $this->dao->removeFruitEntity($fruitId);
        return "Ok";
    }
    
    /**
     * Get the fruit type list action (used by autocomplete)
    **/
    public function fruittypelistAction() 
    {
        $search = strtolower(purpleTools::sanitizeString($_GET['search']));

        $fruitTypeList[] = (object)['Id' => '1', 'label' => 'Acidulated'];
        $fruitTypeList[] = (object)['Id' => '2', 'label' => 'Sweet'];
        $fruitTypeList[] = (object)['Id' => '3', 'label' => 'Disgusting'];

        // Scan the array for getting results array
        $searchResults = Array();
        foreach ($fruitTypeList as $value) 
        if ($search != null && strpos(strtolower($value->label),$search) !== false)
            $searchResults[] = $value;

        return $searchResults;
    }
}
$app = new jsonApi();
?>