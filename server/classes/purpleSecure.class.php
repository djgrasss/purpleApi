<?php
/**
 * Implementation of client security
 * @basedon : http://www.thebuzzmedia.com/designing-a-secure-rest-api-without-oauth-authentication/
 * @author : eka808
 */
class purpleSecure
{
    /** Salt used for encrpytion **/
	public $authSalt = 'aMJhljaB1cD2eF3GkHNlklkb564464';


    //
    // Authentication
    //

    /**
     * Define, for a set do users, the private keys
     * @param array of users : $usersArray : The users array to modify
     */
    public function setPrivateKeysForUsers($usersArray)
    {
        foreach ($usersArray as $key => $value) 
            $usersArray[$key]->privateKey = $this->getPrivateKeyForUser($usersArray[$key]);
        return $usersArray;
    }

    /**
     * Return the private key for a user entity
     * @param  [type] $userEntity [description]
     * @return [type]             [description]
     */
    private function getPrivateKeyForUser($userEntity)
    {
        return hash_hmac('sha256', $userEntity->encryptedPassword, $this->authSalt);
    }

    

    public function checkCredentials($username, $encryptedPassword, $userEntity)
    {
        return $username == $username && $userEntity->encryptedPassword == $encryptedPassword;
    }

    //
    // Check if authorized
    //

	public function isauthorized($securedPackageEntity, $userEntity)
    {
        $serverHash = $this->getHashForQueryString($securedPackageEntity, $userEntity);
        return $serverHash == $securedPackageEntity->hash;
    }

    private function getHashForQueryString($securedPackageEntity, $userEntity)
    {
        return hash_hmac('sha256', $securedPackageEntity->getDataQueryString(), $userEntity->privateKey);
    }
}


?>