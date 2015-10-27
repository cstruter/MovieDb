<?php

class BinaryResult implements IHttpResult
{
	private $filename;
	private $contentType;
	private $cached;
	
	public function __construct($filename, $contentType, $cached = false)
	{
		$this->filename = $filename;
		$this->contentType = $contentType;
		$this->cached = $cached;
	}
	
	public function Render()
	{
		header('Content-Type: '.$this->contentType);
		if (!$this->cached) {
			$content = file_get_contents($this->filename);
			echo $content;		
		} else {
			header('Cache-control: max-age='.(60*60*24*365));
			header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
			header('Last-Modified: '.gmdate(DATE_RFC1123,filemtime($this->filename)));		
			if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			   header('HTTP/1.1 304 Not Modified');
			} else {
				$content = file_get_contents($this->filename);
				echo $content;
			}
		}
	}
}

?>