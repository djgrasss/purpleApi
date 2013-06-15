<?php
include_once('classes/abstractJsonApi.class.php');
/*
                               .__            _____         .__ 
    ______  __ _______________ |  |   ____   /  _  \ ______ |__|
    \____ \|  |  \_  __ \____ \|  | _/ __ \ /  /_\  \\____ \|  |
    |  |_> >  |  /|  | \/  |_> >  |_\  ___//    |    \  |_> >  |
    |   __/|____/ |__|  |   __/|____/\___  >____|__  /   __/|__|
    |__|                |__|             \/        \/|__|       

 * 
 * API provider of purple framework
 * @version : 0.3
 * @author : eka808 - http://www.yoannmagli.ch
 * 
 * @todo : long time pooling for database store
 * @todo : finish implement authorizations
 * 
**/
class jsonApi extends abstractJsonApi
{
    /* DAO getter */
    private function dao() {
        //return new databaseDao('sqlite:../server/sqlitedb/fruits.sqlite');
        return new realtimeDao('/purpleApi/server/cache/');
    }

    /* The files to include to play with */
    protected $dependencies =
        [
            'classes/purpleDebug.class.php'
            ,'classes/purpleTools.class.php'
            ,'interfaces/IDao.interface.php'
            ,'models/FruitEntity.class.php'
            ,'models/PersistenceEntity.class.php'
            //,'classes/purpleUi.class.php'
            //,'classes/purpleMath.class.php'
            ,'dao/realtimeDao.class.php'
            ,'dao/databaseDao.class.php'
            ,'dao/userDao.class.php'
            ,'models/securedPackageEntity.class.php'
        ];

    private $usersArray;
    
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

        $userEntity = $this->userDao->getUser($username);
        if ($this->userDao->securityDao->checkCredentials($username, $encryptedPassword, $userEntity))
        {
            return (object)['PrivateKey' => $userEntity->privateKey];
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
        $secEntityData = new securedPackageEntity($_POST);
        $userEntity = $this->userDao->getUser($secEntityData->username);

        if ($this->userDao->securityDao->isauthorized($secEntityData, $userEntity))
        {
            $entity = new FruitEntity();
            $entity->setFromArray($secEntityData->data);
            $this->dao()->addFruitEntity($entity);
            return "Ok";
        }
        return "Error";
    }

    /**
     * Remove fruit action
    **/
    public function removefruitAction() 
    {
        $secEntityData = new securedPackageEntity($_POST);
        $userEntity = $this->userDao->getUser($secEntityData->username);

        if ($this->userDao->securityDao->isauthorized($secEntityData, $userEntity))
        {
            $this->dao()->removeFruitEntity($secEntityData->data['FruitId']);
            return "Ok";
        }
        return "Error";
    }
    
    /**
     * Get the fruit type list action (used by autocomplete)
    **/
    public function fruittypelistAction() 
    {
        $search = strtolower(purpleTools::sanitizeString($_GET['search']));

        $fruitTypeList[] = 
            [
                (object)['Id' => '1', 'label' => 'Acidulated']
                ,(object)['Id' => '2', 'label' => 'Sweet']
                ,(object)['Id' => '3', 'label' => 'Disgusting']
            ];

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