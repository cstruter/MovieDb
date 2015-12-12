<?php

use Models\Movie,
	Repositories\Interfaces\IMovieRepository,
	CSTruter\Service\Results\JsonResult,
	CSTruter\Service\Results\BinaryResult,
	CSTruter\Service\Results\ErrorResult,
	CSTruter\Facebook\Service\FacebookServiceBase,
	CSTruter\Misc\Request;
	
class MovieService extends FacebookServiceBase
{
	private $repository;
	
	public function __construct(IMovieRepository $repository, $appId, $secret) {
		$this->repository = $repository;
		parent::__construct($appId, $secret);
	}
	
	protected function registerMethods()
	{	
		$this->registerMethod('POST', 'addMovie')
			 ->requireJson()
			 ->requireValidation();
			 
		$this->registerMethod('DELETE', 'removeMovie')
			 ->requiredArguments('movieId')
			 ->requireValidation();
			 
		$this->registerMethod('GET', 'getMovies')
			 ->requiredArguments('signed_request');
			 
		$this->registerMethod('GET', 'getImage')
			 ->requiredArguments('imdbId', 'url')
			 ->requireValidation();
	}

	public function addMovie($json)
	{
		$movie = new Movie($json);
		$movie = $this->repository->add($movie, $this->getUserId());
		return new JsonResult($movie);
	}

	public function removeMovie($movieId) {
		$this->repository->remove($movieId, $this->getUserId());
	}	
	
	public function getMovies($signed_request)
	{
		if (!$this->validate($signed_request)) {
			return new ErrorResult('Access denied', 401);
		}
		$movies = $this->repository->getAll($this->getUserId());
		return new JsonResult($movies);
	}
	
	public function getImage($imdbId, $url)
	{
		$filename = '../Images/'.$imdbId.'.jpg';
		if (!file_exists($filename)) {
			$request = new Request($url);
			$content = $request->getResponse();
			file_put_contents($filename, $content);
		}
		return new BinaryResult($filename, 'image/jpeg', true);
	}	
}

?>