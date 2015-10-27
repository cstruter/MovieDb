<?php

class Movies
{
	private $fbId;
	
	public function __construct($fbId)
	{
		$this->fbId = $fbId;
	}
	
	public function map(array $result)
	{
		$movie = new Movie();
		$movie->movieId = (isset($result['movieId'])) ? $result['movieId'] : null;
		$movie->imdbID = $result['imdbID'];
		$movie->Title = $result['Title'];
		$movie->Year = $result['Year'];
		$movie->Plot = $result['Plot'];
		$movie->Rated = $result['Rated'];
		$movie->Runtime = $result['Runtime'];
		$movie->Released = $result['Released'];
		$movie->Genre = $result['Genre'];
		$movie->Awards = $result['Awards'];
		$movie->Poster = $result['Poster'];
		return $movie;
	}
	
	public function Get($imdbId) {
		$results = MySQL::Create('SELECT movieId, imdbId imdbID, title Title, '.
								 'year Year, plot Plot, rating Rated, poster Poster, '.
								 'runtime Runtime, released Released, genre Genre, awards Awards '.
								'FROM movies WHERE imdbId = ?imdbId '.
								'AND fbId = ?fbId')
						->Parameter('imdbId', $imdbId)
						->Parameter('fbId', $this->fbId)
						->Query();
		return (isset($results)) ? $this->map($results[0]): NULL;
	}
	
	public function GetAll() {
		$movies = array();
		$genreList = array();
		$results = MySQL::Create('SELECT movieId, imdbId imdbID, title Title, '.
								 'year Year, plot Plot, rating Rated, poster Poster, '.
								 'runtime Runtime, released Released, genre Genre, awards Awards '.
								'FROM movies '.
								'WHERE fbId = ?fbId '.
								'ORDER by title')
						->Parameter('fbId', $this->fbId)
						->Query();
		if (isset($results)) {
			foreach($results as $result) {
				$movie = $this->map($result);
				$movies[] = $movie;
				$genre = array_map('trim', explode(',', $movie->Genre));
				$genreList = array_unique(array_merge($genreList, $genre));				
			}
			asort($genreList);
			$genreList = array_values($genreList);
		}
		return array(
			'movies'=>$movies, 
			'genre'=> $genreList
		);
	}
	
	public function Add(Movie $movie)
	{
		if ($this->Get($movie->imdbID) == NULL) {
			MySQL::Create('INSERT into movies(imdbId, poster, title, year, plot, rating, runtime, released, genre, awards, fbId) '.
							'VALUES (?imdbId, ?poster, ?title, ?year, ?plot, ?rating, ?runtime, ?released, ?genre, ?awards, ?fbId)')
				->Parameter('imdbId', $movie->imdbID)
				->Parameter('poster', $movie->Poster)
				->Parameter('title', $movie->Title)
				->Parameter('year', $movie->Year)
				->Parameter('plot', $movie->Plot)
				->Parameter('rating', $movie->Rated)
				->Parameter('runtime', $movie->Runtime)
				->Parameter('released', $movie->Released)
				->Parameter('genre', $movie->Genre)
				->Parameter('awards', $movie->Awards)
				->Parameter('fbId', $this->fbId)
				->NonQuery();
			$movie->movieId = MySQL::LastId();
			return $movie;
		}
		return null;
	}
	
	public function Remove($movieId)
	{
		MySQL::Create('DELETE FROM movies '.
						'WHERE movieId = ?movieId '.
						'AND fbId = ?fbId')
			->Parameter('movieId', $movieId)
			->Parameter('fbId', $this->fbId)
			->NonQuery();
	}
	
}

?>