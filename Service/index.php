<?php 

session_start();

include '../config.php';
include 'Common/Data/MySQL.php';
include 'Common/Exceptions/LegacyException.php';
include 'Common/Sanity.php';
include 'Common/Service/Results/IHttpResult.php';
include 'Common/Service/Results/JsonResult.php';
include 'Common/Service/Results/BinaryResult.php';
include 'Common/Service/Exceptions/ServiceException.php';
include 'Common/Service/ServiceMethod.php';
include 'Common/Service/ServiceBase.php';
include 'Facebook/Exceptions/OAuthException.php';
include 'Facebook/FacebookApi.php';
include 'Facebook/Service/FacebookAuthenticatedServiceBase.php';
include 'Models/Movie.php';
include 'DAL/Movies.php';

class MovieService extends FacebookAuthenticatedServiceBase
{	
	public function addMovie($json)
	{
		$movies = new Movies($this->getUserId());
		$movie = $movies->map($json);
		$movies->Add($movie);
		return new JsonResult($movie);
	}

	public function removeMovie($movieId)
	{
		$movies = new Movies($this->getUserId());
		$movies->Remove($movieId);
	}	
	
	public function getMovies($signed_request)
	{
		if (!$this->validate($signed_request)) {
			throw new ServiceException('Access denied', 401);
		}
		$movies = new Movies($this->getUserId());
		$movies = $movies->GetAll();
		return new JsonResult($movies);
	}
	
	public function getImage($imdbId, $url)
	{
		$filename = '../Images/'.$imdbId.'.jpg';
		if (!file_exists($filename)) {
			$content = file_get_url_contents($url);
			file_put_contents($filename, $content);
		}
		return new BinaryResult($filename, 'image/jpeg', true);
	}
	
	protected function registerMethods()
	{
		$this->registerMethod('POST', 'addMovie')
			 ->RequireJson()
			 ->RequireValidation();
			 
		$this->registerMethod('DELETE', 'removeMovie')
			 ->Arguments('movieId')
			 ->RequireValidation();
			 
		$this->registerMethod('GET', 'getMovies')
			 ->Arguments('signed_request');
			 
		$this->registerMethod('GET', 'getImage')
			 ->Arguments('imdbId', 'url')
			 ->RequireValidation();
	}	
}

$movieService = new MovieService(FBAPI, FBSECRET);

?>