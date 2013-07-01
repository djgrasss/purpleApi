<?php
/**
* 
*/
class mailSender
{
	private $Title;
	private $Object;
	private $Content;
	private $Emails;

	function __construct()
	{
		return $this;
	}

	function setTitle($title)	
	{
		$this->Title = $title;
		return $this;
	}

	function setObject($object)	
	{
		$this->Object = $object;
		return $this;
	}

	function setContent($content)	
	{
		$this->Content = $content;
		return $this;
	}

	function setEmails($emails)	
	{
		$this->Emails = $emails;
		return $this;
	}

	function sendEmail()
	{
		purpleDebug::print_r($this);

	}
}
?>