<?php

class JsonResult implements IHttpResult
{
	private $body;
	
	public function __construct($body) 
	{
		$this->body = $body;
	}
	
	public function Render()
	{
		header('Content-Type: application/json');
		echo json_encode($this->body);
	}
}

?>