<?php 
include_once('classes/purpleSecure.class.php');
class userDao extends abstractDao
{
	//
	// Private
	//

	private $usersArray;
	private $securityDao;

	function __construct()
	{
		parent::__construct();
		/*$tableName = 'users';
		$sql = "
    		--DROP TABLE " . $tableName . ";
	    	CREATE TABLE IF NOT EXISTS " . $tableName . " ( 
	    		Id INTEGER PRIMARY KEY, 
	    		username VARCHAR(50),
	    		encryptedPassword TEXT
	    	);";
		$this->context->executeSqlQuery($sql);*/

		/*purpleDebug::print_r(
			$this->context->listEntities('users')
		);*/

		// Security layer
        $this->securityDao = new purpleSecure();

		$this->usersArray = 
	        [
	            (object)['username'=>strtolower('eka808'), 'encryptedPassword'=>sha1('foobar'), 'privateKey'=>  null],
	            (object)['username'=>strtolower('kaZ'), 'encryptedPassword'=>sha1('foodsfdbar'), 'privateKey'=>  null]
	        ];
        $this->usersArray = $this->securityDao->setPrivateKeysForUsers($this->usersArray);
	}

	//
	// Public
	//

	public function ListUsers()
	{
		return $this->usersArray;
	}

	public function ListUsersPublic()
	{
		$projection = [];
		foreach ($this->ListUsers() as $value) {
			$cur = new stdClass();
			$cur->username = $value->username;
			$projection[] = $cur;
		}
		return $projection;
	}

    public function getUser($userName)
    {
        foreach ($this->usersArray as $key => $value)
        if ($value->username == $userName)
            return $value;
    }

    public function checkCredentials($username, $encryptedPassword)
    {
    	$userEntity = $this->getUser($username);
    	if ($this->securityDao->checkCredentials($username, $encryptedPassword, $userEntity))
    		return $userEntity->privateKey;
    	return false;
    }

    public function isauthorized($secEntityData)
    {
    	$userEntity = $this->getUser($secEntityData->username);
    	return $this->securityDao->isauthorized($secEntityData, $userEntity);
    }
}
?>