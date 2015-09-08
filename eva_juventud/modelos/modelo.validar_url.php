<?php
class validar_url
{
	public $url;
	public function __construct($url)
	{
		$this->url=$url;
	}
	//metodo para validar la url
	function url_exists()
	{
		$file_headers = @get_headers($this->url);
		if(strpos($file_headers[0],"200 OK")==false)
		{
			$exists = false;
			return $exists;
		}
		else
		{
			$exists = true;
			return $exists;
		}
		
	}
}	
?>