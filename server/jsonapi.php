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
 * @version : 0.4
 * @author : eka808 - http://www.yoannmagli.ch
 * 
**/
class jsonApi extends abstractJsonApi
{
    /* Files to include */
    protected $dependencies =
        [
            'classes/purpleDebug.class.php'
            ,'classes/purpleTools.class.php'
            ,'classes/purpleIoc.class.php'
            //,'classes/mailSender.class.php'
            //,'classes/purpleUi.class.php'
            //,'classes/purpleMath.class.php'
            ,'models/FruitEntity.class.php'
            ,'models/PersistenceEntity.class.php'
            ,'models/securedPackageEntity.class.php'
            ,'dao/persistenceDao.class.php'
            ,'dao/databaseDao.class.php'
            ,'dao/abstractDao.class.php'
            ,'dao/userDao.class.php'
            ,'dao/fruitDao.class.php'
        ];

    /**
     * Ioc container used for classes persistence 
     * Kept public for being unsed application scope
     */
    public static $container;

    /**
     * Default constructor 
    **/
    function __construct()
    {
        parent::__construct();

        // 
        // IOC binding Declaration
        // 
        self::$container = new purpleIoc();
        // Set the db context and the persistence object
        self::$container->context = new databaseDao('sqlite:../server/sqlitedb/fruits.sqlite');
        self::$container->persistence = new persistenceDao('cache/persistence.txt');
        // Set the dao's
        self::$container->fruitEntityDao = new fruitDao();
        self::$container->userDao = new userDao();
        
        //Routing
        $this->route();
    }

    //
    // FRUITS
    //

    /** 
     * AUTH : get private key
    **/
    public function getprivatekeyAction()
    {        
        $privateKeyIfCorrect = 
            self::$container->userDao->checkCredentials(
                strtolower(purpleTools::sanitizeString($_GET['username'])),
                purpleTools::sanitizeString($_GET['encryptedPassword'])
            );

        return $privateKeyIfCorrect
            ? (object)['PrivateKey' => $privateKeyIfCorrect]
            : "IncorrectAuthParameters";
    }

    /**
     * List fruits action
    **/
    public function fruitlistAction() 
    {
        return self::$container->fruitEntityDao->listFruitEntities();
    }

    /**
     * List fruits action for long time pooling
    **/
    public function fruitlistpersistentAction() 
    {
        $clientListTime = purpleTools::sanitizeString(isset($_GET['generationTime']) ? $_GET['generationTime'] : '');
        return self::$container->fruitEntityDao->listFruitEntitiesLongTimePooling($clientListTime);
    }
    
    /**
     * Add fruit action
    **/
    public function addfruitAction() 
    {
        $secEntityData = new securedPackageEntity($_POST);
        if (self::$container->userDao->isauthorized($secEntityData))
        {
            $entity = new FruitEntity();
            $entity->setFromArray($secEntityData->data);
            self::$container->fruitEntityDao->addFruitEntity($entity);
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
        if (self::$container->userDao->isauthorized($secEntityData))
        {
            self::$container->fruitEntityDao->removeFruitEntity($secEntityData->data['FruitId']);
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

        $fruitTypeList = 
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


    //
    // USER
    //
    
    public function userlistAction()
    {
        return self::$container->userDao->ListUsersPublic();
    }

    //
    // UPLOAD
    //
    public function fruituploadAction()
    {
        if (isset($_FILES))
        foreach($_FILES as $file)
        if (move_uploaded_file($file['tmp_name'], '../upload/'.$file['name']))
        {
             return "Ok";
        }
        return "Error";
    }
}
$app = new jsonApi();
//echo 'disabled autoload';
?>