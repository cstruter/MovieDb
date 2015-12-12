<?php

namespace Repositories;

use Models\Movie,
	Repositories\Interfaces\IMovieRepository,
	CSTruter\Misc\Data\MySqlAdapter;

class Movies implements IMovieRepository
{
	private $adapter;
	
	public function __construct(MySqlAdapter $adapter) {
		$this->adapter = $adapter;
	}
	
	private function get($imdbId, $fbId) {
		$results = $this->adapter->execute(
			'SELECT movieId, imdbId imdbID, title Title, '.
				'year Year, plot Plot, rating Rated, poster Poster, '.
				'runtime Runtime, released Released, genre Genre, awards Awards '.
			'FROM movies WHERE imdbId = ? '.
			'AND fbId = ?', [$imdbId, $fbId]);
		return (count($results) > 0) ? new Movie($results[0]): NULL;
	}
	
	public function getAll($fbId) {
		$movies = [];
		$genreList = [];		
		$results = $this->adapter->execute(
			'SELECT movieId, imdbId imdbID, title Title, '.
				'year Year, plot Plot, rating Rated, poster Poster, '.
				'runtime Runtime, released Released, genre Genre, awards Awards '.
			'FROM movies '.
			'WHERE fbId = ? '.
			'ORDER by title', [$fbId]);
		if (count($results) == 0) {
			return NULL;
		}
		foreach($results as $result) {
			$movie = new Movie($result);
			$movies[] = $movie;
			$genre = array_map('trim', explode(',', $movie->Genre));
			$genreList = array_unique(array_merge($genreList, $genre));				
		}
		asort($genreList);
		$genreList = array_values($genreList);		
		return [
			'movies'=>$movies, 
			'genre'=> $genreList
		];
	}
	
	public function add(Movie $movie, $fbId)
	{
		$exists = $this->get($movie->imdbID, $fbId);
		if ($exists != NULL) {
			return $exists;
		}
		$this->adapter->execute(
			'INSERT into movies(imdbId, poster, title, year, plot, rating, runtime, released, genre, awards, fbId) '.
			'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',[
				 $movie->imdbID, $movie->Poster, $movie->Title, 
				 $movie->Year, $movie->Plot, $movie->Rated, 
				 $movie->Runtime, $movie->Released, $movie->Genre, 
				 $movie->Awards, $fbId]);
		return $this->get($movie->imdbID, $fbId);
	}
	
	public function remove($movieId, $fbId)
	{
		$this->adapter->execute(
			'DELETE FROM movies '.
			'WHERE movieId = ? '.
			'AND fbId = ?', [$movieId, $fbId]);
	}
}

?>