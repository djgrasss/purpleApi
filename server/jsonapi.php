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
 * @todo : finish implement authorizations
**/
class jsonApi extends abstractJsonApi
{
    /* DAO getter */
    private function dao() {
        return new realtimeDao('/purpleApi/server/cache/');
        //return new new databaseDao('sqlite:../server/sqlitedb/fruits.sqlite');
    }

    /* The files to include to play with */
    protected $dependencies =
        [
            'classes/purpleDebug.class.php'
            ,'interfaces/IDao.interface.php'
            ,'models/FruitEntity.class.php'
            ,'models/PersistenceEntity.class.php'
            ,'classes/purpleTools.class.php'
            ,'classes/purpleSecure.class.php'
            //,'classes/purpleUi.class.php'
            //,'classes/purpleMath.class.php'
            ,'dao/realtimeDao.class.php'
            ,'dao/databaseDao.class.php'
        ];

    private function getUsersArray()
    {
        $usersArray =
            [
                (object)['username'=>strtolower('eka808'), 'encryptedPassword'=>sha1('foobar'), 'privateKey'=>  null],
                (object)['username'=>strtolower('kaZ'), 'encryptedPassword'=>sha1('foodsfdbar'), 'privateKey'=>  null]
            ];
        $usersArray = $this->securityDao->setPrivateKeysForUsers($usersArray);
        return $usersArray;
    }

    private function getUser($userName)
    {
        foreach ($this->getUsersArray() as $key => $value)
        if ($value->username == $userName)
            return $value;
    }
    
    /**
     * Default constructor 
    **/
    function __construct()
    {        
        parent::__construct();
    }


    /** 
     * AUTH : get private key
    **/
    public function getprivatekeyAction()
    {        
        $username = strtolower(purpleTools::sanitizeString($_GET['username']));
        $encryptedPassword = purpleTools::sanitizeString($_GET['encryptedPassword']);

        $userEntity = $this->getUser($username);
        
        $privateKey = $this->securityDao->getPrivateKeyIfCoherent($username, $encryptedPassword, $userEntity);
        if ($privateKey != false)
        {
            $obj = new stdClass();
            $obj->PrivateKey = $privateKey;
            return $obj;
        }
        return "IncorrectAuthParameters";
    }


    /**
     * List fruits action
    **/
    public function fruitlistAction() 
    {
        return $this->dao()->listFruitEntities();
    }

    /**
     * List fruits action for long time pooling
    **/
    public function fruitlistpersistentAction() 
    {
        $clientListTime = purpleTools::sanitizeString($_GET['generationTime']);
        return $this->dao()->listFruitEntitiesLongTimePooling($clientListTime);
    }
    
    /**
     * Add fruit action
    **/
    public function addfruitAction() 
    {
        $userName = purpleTools::sanitizeString($_POST['username']);
        $clientHash = purpleTools::sanitizeString($_POST['hash']);
        $entity = purpleTools::sanitizeArray($_POST['data']);

        if ($this->securityDao->isauthorized($userName, $clientHash, $entity, $this->getUser($userName)))
        {
            $fruitName = $entity['Name'];    
            $fruitQuantity = $entity['Quantity'];
            $fruitTypeId = $entity['TypeId'];
            $this->dao()->addFruitEntity(new FruitEntity($fruitName, $fruitQuantity, $fruitTypeId));
            return "Ok";
        }
        return "Error";
    }

    /**
     * Remove fruit action
    **/
    public function removefruitAction() 
    {
        $fruitId = purpleTools::sanitizeString($_POST['FruitId']);
        $this->dao()->removeFruitEntity($fruitId);
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