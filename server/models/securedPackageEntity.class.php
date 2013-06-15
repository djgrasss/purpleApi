<?php
class securedPackageEntity 
{
	/**
	 * The username used as a public key between the client and the server
	 */
	public $username;

	
	/**
	 * Data sent by the client
	 */
	public $data;
	
	/**
	 * The hash of the data sent by the client between the private key and the data
	 */
	public $hash;
	
	/**
	 * Default constructor
	 * @param [type] $dataArray $_POST array
	 */
	function __construct($dataArray)
	{
		$this->username = purpleTools::sanitizeString($_POST['username']);
        $this->hash = purpleTools::sanitizeString($_POST['hash']);
        $this->data = purpleTools::sanitizeArray($_POST['data']);
	}

	/**
	 * Get a query string format of the data property
	 */
	public function getDataQueryString()
	{
		return purpleTools::arrayToQueryString($this->data);
	}
	
}
?>