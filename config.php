<?php

error_reporting(E_ALL);

include 'locale/en.php';

define('MYSQL_HOST', 'localhost');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_DATABASE', 'movies');
define('FBAPI', '1585875358368256');
define('FBSECRET', 'de82896093b14e1050e4bfc7641df4b4');

function LegacyErrorHandler($errno, $errstr, $errfile, $errline)
{
    $exception = new Exceptions\LegacyException($errstr, $errno);
	$exception->errfile = $errfile;
	$exception->errline = $errline;
	throw $exception;
}

set_error_handler("LegacyErrorHandler");

?>