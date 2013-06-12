<?php
/**
 * 
 */
class purpleSecure
{
	public $authSalt = 'aMJhljaB1cD2eF3GkHNlklkb564464';


	public function isauthorized($userName, $clientHash, $entity, $userEntity)
    {
        $entityQueryString = purpleTools::arrayToQueryString($entity);
        $serverHash = $this->getHashForQueryString($entityQueryString, $userName, $userEntity);
        return $serverHash == $clientHash;
    }

    public function setPrivateKeysForUsers($usersArray)
    {
    	foreach ($usersArray as $key => $value) 
            $usersArray[$key]->privateKey = hash_hmac('sha256', $value->encryptedPassword, $this->authSalt);
        return $usersArray;
    }

    public function getPrivateKeyIfCoherent($username, $encryptedPassword, $userEntity)
    {
        if ($userEntity->username == $username && $userEntity->encryptedPassword == $encryptedPassword)        
        	return $userEntity->privateKey;
       	return false;
    }


    private function getPrivateKeyForUser($userEntity)
    {
            return hash_hmac('sha256', $userEntity->encryptedPassword, $this->authSalt);
    }
    private function getHashForQueryString($queryString, $userName, $userEntity)
    {
        $clientPrivateKey = $this->getPrivateKeyForUser($userEntity);

        return hash_hmac('sha256', $queryString, $clientPrivateKey);
    }
}


?>