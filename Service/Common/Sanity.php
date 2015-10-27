<?php

function session_exterminate()
{
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
}

function file_get_url_contents($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function LegacyErrorHandler($errno, $errstr, $errfile, $errline)
{
    $exception = new LegacyException($errstr, $errno);
	$exception->errfile = $errfile;
	$exception->errline = $errline;
	throw $exception;
}

set_error_handler("LegacyErrorHandler");

?>