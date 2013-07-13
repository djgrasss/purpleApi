<?php
class sendFile
{
	private $postFileEntity;
	private $path;
	private $filename;

	function __construct($postFileEntity)
	{
		$this->postFileEntity = $postFileEntity;
	}

	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	public function setFileName($filename)
	{
		$this->filename = $filename;
		return $this;
	}

	public function uploadFile()
	{
		return
			move_uploaded_file(
				$this->postFileEntity['tmp_name'],
				$this->path . $this->filename
			);
	}
	
}
?>