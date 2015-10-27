function DBService($http, FacebookService) {

	return {
		addMovie : addMovie,
		getMovies: getMovies,
		removeMovie : removeMovie
	};
	
	function addMovie(movie) {
		movie.method = 'addMovie';
		return $http.post(Settings.Local, movie);
	}
	
	function removeMovie(movie)
	{
		return $http.delete(Settings.Local, {
			params: {
				method : 'removeMovie',
				movieId : movie.movieId
			}
		});
	}
	
	function getMovies() {
		return $http.get(Settings.Local, {
			params: {
				method : 'getMovies',
				signed_request : FacebookService.signedRequest
			},
			cache : false
		});
	}
	
}