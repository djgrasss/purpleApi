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