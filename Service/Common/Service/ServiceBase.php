<?php

abstract class ServiceBase
{
	protected $methods = array();
	protected $json = null;

	protected abstract function validate();
	protected abstract function isValidated();
	protected abstract function registerMethods();	
	
	public function __construct()
	{
		try {
			$json = file_get_contents("php://input");
			$this->json = json_decode($json, true);

			if (isset($_GET['method'])) {
				$method = $_GET['method'];
			} else if (isset($_POST['method'])) {
				$method = $_POST['method'];
			} else if ((isset($this->json)) && (isset($this->json['method']))) {
				$method = $this->json['method'];
			} else {
				throw new ServiceException('Method not specified', 400);
			}
			
			$this->registerMethods();
			
			if (!isset($this->methods[$method]))
				throw new ServiceException('Method '.$method.' not found', 404);
			
			$this->onInit($this->methods[$method]);
			
		} catch(Exception $ex) {
			$this->onError($ex);
		}
	}

	protected function registerMethod($request_method, $method) {
		return $this->methods[$method] = new ServiceMethod($request_method, $method);
	}	
	
	protected function onInit($serviceMethod)
	{
		if ($serviceMethod->requireValidation) {
			if (!$this->isValidated()) {
				throw new ServiceException('Access denied', 401);
			}
		}
		
		if ($serviceMethod->requireJson) {
			if (empty($this->json)) {
				throw new ServiceException('Json request expected', 400);
			}
		}
		
		if ($_SERVER['REQUEST_METHOD'] != $serviceMethod->requestMethod) {
			throw new ServiceException('Invalid Request Method '.$serviceMethod->requestMethod.' expected', 405);
		}	

		$params = array();

		if (isset($serviceMethod->args)) {
			foreach($serviceMethod->args as $arg) {
				if (isset($_GET[$arg])) {
					$params[] = $_GET[$arg];
				} else if (isset($_POST[$arg])) {
					$params[] = $_POST[$arg];
				} else {
					throw new ServiceException('Missing '.$arg.' parameter', 400);
				}
			}
		}
		
		if (!empty($this->json)) {
			$params[] = $this->json;
		}
		
		$result = call_user_func_array(array($this, $serviceMethod->method), $params);
		
		if (isset($result)) {
			if ($result instanceof IHttpResult) {
				$result->Render();
			} else {
				echo $result;
			}
		}
	}
	
	protected function onError($ex)
	{
		switch ($ex->getCode()) {
			case 400:
				header('HTTP/1.1 400 Bad Request');
			break;
			case 401:
				header('HTTP/1.0 401 Unauthorized');
			break;
			case 404:
				header("HTTP/1.0 404 Not Found");
			break;
			case 405:
				header("HTTP/1.0 405 Method Not Allowed");
			break;
			default:
				header("HTTP/1.0 500 Internal Server Error");
			break;
		}
		die($ex->getMessage());	
	}
}

?>