function OMDBService($http) {
	
	return {
		findMovie : findMovie,
		getMovie: getMovie
	};
	
	function findMovie(title)
	{
		return $http.get(Settings.Remote, {
			params : {
				s : title,
				y : '',
				plot: 'short',
				r: 'json'
			},
			cache: true
		});
	}
	
	function getMovie(imdbID)
	{
		return $http.get(Settings.Remote, {
			params : {
				i : imdbID,
				y : '',
				plot: 'full',
				r: 'json'
			},
			cache: true
		});
	}
	
}