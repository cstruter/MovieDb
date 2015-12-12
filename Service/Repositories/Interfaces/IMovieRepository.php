<?php

namespace Repositories\Interfaces;

use Models\Movie;

interface IMovieRepository
{
	function getAll($fbId);
	function add(Movie $movie, $fbId);
	function remove($movieId, $fbId);
}

?>