<?php

class FacebookApi
{
	private $appId;
	private $token;
	private $secret;
	
	public function __construct($appId, $secret, $token = NULL) {
		$this->appId = $appId;
		$this->secret = $secret;
		$this->token = $token;
	}
	
	public function GetUser($user_id = 'me') {
		if (empty($this->token)) {
			throw new Exception('Token Required');
		}	
		return $this->request('https://graph.facebook.com/'.$user_id.'/?access_token='.$this->token);
	}
	
	public function ValidateSignedRequest($code) {
		return $this->request('https://graph.facebook.com/oauth/access_token?client_id='.$this->appId.'&redirect_uri=&client_secret='.$this->secret.'&code='.$code);
	}

	public function ParseSignedRequest($signed_request) {
		$segments = explode('.', $signed_request); 
		
		if (count($segments) > 1) {
			list($encoded_signature, $payload) = $segments;
		} else {
			throw new Exception('Bad signed request');
		}
		
		$json = json_decode($this->base64_url_decode($payload), true);
		
		if (empty($json)) {
			throw new Exception('Bad payload');
		}
		
		$signature = $this->base64_url_decode($encoded_signature);
		$expected_signature = hash_hmac('sha256', $payload, $this->secret, $raw = true);
		
		if ($signature !== $expected_signature) {
			throw new Exception('Bad signed JSON signature');
		}
		
		return  $json;
	}
	
	private function request($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$response = curl_exec($ch);
		if(curl_errno($ch)) {
			$errorMessage = curl_error($ch);
			curl_close($ch);
			throw new Exception($errorMessage);
		} 
		curl_close($ch);
		$jsonResponse = json_decode($response, true);
		if (isset($jsonResponse['error'])) {
			throw new OAuthException($jsonResponse['error']['message'], $jsonResponse['error']['code']);
		}
		return $jsonResponse;
	}
	
	private function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}	
}

?>