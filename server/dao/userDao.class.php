<?php 
include_once('classes/purpleSecure.class.php');
class userDao 
{

	private $usersArray;
	public $securityDao;

	function __construct()
	{
		// Security layer
        $this->securityDao = new purpleSecure();

		$this->usersArray = 
	        [
	            (object)['username'=>strtolower('eka808'), 'encryptedPassword'=>sha1('foobar'), 'privateKey'=>  null],
	            (object)['username'=>strtolower('kaZ'), 'encryptedPassword'=>sha1('foodsfdbar'), 'privateKey'=>  null]
	        ];
        $this->usersArray = $this->securityDao->setPrivateKeysForUsers($this->usersArray);
	}

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
}
?>