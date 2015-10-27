<?php

class ServiceMethod
{
	public $requestMethod;
	public $method;
	public $args = NULL;
	public $requireValidation = false;
	public $requireJson = false;
	
	public function __construct($requestMethod, $method)
	{
		$this->requestMethod = $requestMethod;
		$this->method = $method;
	}
	
	public function Arguments() {
		$this->args = func_get_args();
		return $this;
	}
	
	public function RequireValidation() {
		$this->requireValidation = true;
		return $this;
	}
	
	public function RequireJson() {
		$this->requireJson = true;
		return $this;
	}
}

?>