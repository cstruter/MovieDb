<?php

namespace Repositories;

use Models\Movie,
	Repositories\Interfaces\IMovieRepository,
	Doctrine\ORM\EntityRepository;
	
class Movies extends EntityRepository implements IMovieRepository
{
	public function getAll($fbId) {
		$movies = $this->findBy(['FbId' => $fbId]);
		$genreList = [];
		if (count($movies) > 0) {
			foreach($movies as $movie) {
				$genre = array_map('trim', explode(',', $movie->Genre));
				$genreList = array_unique(array_merge($genreList, $genre));				
			}
			asort($genreList);
			$genreList = array_values($genreList);		
		}
		return [
			'movies'=>$movies, 
			'genre'=> $genreList
		];
	}
	
	public function add(Movie $movie, $fbId)
	{
		$exists = $this->findOneBy(['FbId' => $fbId, 'imdbID' => $movie->imdbID]);
		if ($exists != NULL) {
			return $exists;
		}
		$movie->FbId = $fbId;
		$this->_em->persist($movie);
		$this->_em->flush();
		return $this->findOneBy(['FbId' => $fbId, 'imdbID' => $movie->imdbID]);
	}
	
	public function remove($movieId, $fbId)
	{
		$movie = $this->findOneBy(['FbId' => $fbId, 'movieId' => $movieId]);
		if (!empty($movie)) {
			$this->_em->remove($movie);
			$this->_em->flush();
		}		
	}
}

?>