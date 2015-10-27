<?php

class LegacyException extends Exception
{
	public $errno; 
	public $errstr;
	public $errfile;
	public $errline;
	
    public function __construct($message, $code = 0, Exception $previous = null) {
		$this->errstr = $message;
		$this->errno = $code;
        parent::__construct($message, $code, $previous);
    }
}

?>