<?php

abstract class FacebookAuthenticatedServiceBase extends ServiceBase
{
	private $api;
	
	public function __construct($appId, $secret) { 
		$this->api = new FacebookApi($appId, $secret);
		parent::__construct(); 
	}
	
	protected function getUserId() {
		return $_SESSION['id'];
	}
	
	protected function isValidated() {
		return (isset($_SESSION['id']));
	}
	
	protected function validate($signed_request = NULL)
	{
		try
		{
			$json = $this->api->ParseSignedRequest($signed_request);
			if ((!isset($_SESSION['signed_request'])) || ($_SESSION['signed_request'] != $signed_request)) 
			{
				$this->api->ValidateSignedRequest($json['code']);
				$_SESSION['signed_request'] = $signed_request;	
			}
			$_SESSION['id'] = $json['user_id'];
			return true;
		}
		catch(OAuthException $ex)
		{
			session_exterminate();
			return false;
		}
	}
}

?>