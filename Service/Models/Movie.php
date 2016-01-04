<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="movies")
 * @ORM\Entity(repositoryClass="Repositories\Movies")
 */
class Movie
{
	/**
     * @ORM\Column(type="integer", name="movieId")
     * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
     */
	public $movieId;
	
	/** 
	 * @ORM\Column(type="string", name="imdbId", length=50)
	 */
	public $imdbID;
	
	/** 
	 * @ORM\Column(type="string", name="title", length=255)
	*/
	public $Title;
	
	/** 
	 * @ORM\Column(type="integer", name="year")
	 */
	public $Year;
	
	/** 
	 * @ORM\Column(type="string", name="plot")
	*/	
	public $Plot;
	
	/** 
	 * @ORM\Column(type="string", name="rating", length=50)
	*/	
	public $Rated;
	
	/** 
	 * @ORM\Column(type="string", name="runtime", length=50)
	 */	
	public $Runtime;
	
	/** 
	 * @ORM\Column(type="string", name="released", length=50)
	 */	
	public $Released;
	
	/** 
	 * @ORM\Column(type="string", name="genre", length=255)
	 */	
	public $Genre;
	
	/** 
	 * @ORM\Column(type="string", name="awards", length=255)
	*/	
	public $Awards;
	
	/** 
	 * @ORM\Column(type="string", name="poster", length=320)
	 */	
	public $Poster;
	
	/** 
	 * @ORM\Column(type="bigint", name="fbId")
	 */
	public $FbId;
	
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