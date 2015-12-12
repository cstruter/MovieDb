<?php

namespace Models;

class Movie
{
	public $movieId;
	public $imdbID;
	public $Title;
	public $Year;
	public $Plot;
	public $Rated;
	public $Runtime;
	public $Released;
	public $Genre;
	public $Awards;
	public $Poster;
	
	public function __construct(array $values = NULL)
	{
		if (empty($values)) {
			return;
		}
		$this->movieId = (isset($values['movieId'])) ? $values['movieId'] : null;
		$this->imdbID = $values['imdbID'];
		$this->Title = $values['Title'];
		$this->Year = $values['Year'];
		$this->Plot = $values['Plot'];
		$this->Rated = $values['Rated'];
		$this->Runtime = $values['Runtime'];
		$this->Released = $values['Released'];
		$this->Genre = $values['Genre'];
		$this->Awards = $values['Awards'];
		$this->Poster = $values['Poster'];
	}
	
}

?>