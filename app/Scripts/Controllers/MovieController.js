function MovieController($scope, $q, OMDBService, DBService, FacebookService) {
	
	$scope.profile = {};
	$scope.hasLoaded = false;
	$scope.searchError = '';
	$scope.myMoviePhrase = '';
	$scope.searchPhrase = '';
	$scope.moviesFound = [];
	$scope.movie = { };
	$scope.movies = [];
	$scope.genres = [];
	$scope.selectedGenres = [];
	
	$scope.hasMovie = function(movie) { 
		return (movie.Title === undefined) || (movie.movieId !== undefined); 
	}	

	$scope.imageSource = function(movie) { 
		return ((movie.Poster !== undefined) && 
				(movie.Poster !== 'N/A')) 
			? Settings.Local + '?method=getImage&url=' + movie.Poster + '&imdbId='+ movie.imdbID
			: 'Images/no-image.jpg'; 
	}

	$scope.findMovie = function() {
		$scope.searchError = '';
		$scope.moviesFound = [];
		OMDBService.findMovie($scope.searchPhrase).then(function(response) {
			var data = response.data.Search;
			if (data === undefined) {
				$scope.searchError = LocalStrings.NotFound + ' "' + $scope.searchPhrase + '"';
			} else {
				data = _.uniq(data, function(item) { return item.imdbID; });
				angular.forEach(data, function(searchResult, index) {
					var movie = _.findWhere($scope.movies, { imdbID : searchResult.imdbID });
					if (movie !== undefined) {
						data[index] = movie;
					}
				});
				$scope.moviesFound = data;
			}						
		}, function() {
			$scope.searchError = LocalStrings.ErrorSearchingFor + ' "' + $scope.searchPhrase + '"';
		});
	}
	
	$scope.viewMovie = function(movie) {
		$scope.movie = {};
		if (movie.movieId === undefined) {
			OMDBService.getMovie(movie.imdbID).then(function(response) {
				$scope.movie = response.data;
			}, function(error) {
				Helpers.Dialog.Alert({
					title : LocalStrings.ErrorLoadingMovie,
					message : error.statusText
				});
			});
		} else {
			$scope.movie = movie;
		}
	}

	$scope.addMovie = function() {
		DBService.addMovie($scope.movie).then(function(response) {
			var movie = response.data;
			var genres = movie.Genre.split(',');
			var searchResult = _.findWhere($scope.moviesFound, { imdbID : movie.imdbID });
			$scope.movie = movie;
			$scope.movies.push(movie);
			if (searchResult !== undefined) {
				angular.copy(movie, searchResult);
			}
			angular.forEach(genres, function(genre) {
				genre = $.trim(genre);
				if (!_.contains($scope.genres, genre)) {
					$scope.genres.push(genre);
					$scope.selectedGenres.push(genre);
				}
			});
		}, function(error) {
			Helpers.Dialog.Alert({
				title : LocalStrings.ErrorAddingMovie,
				message : error.data
			});
		});
	}
	
	$scope.removeMovie = function() {
		DBService.removeMovie($scope.movie).then(function(response) {
			var searchResult = _.findWhere($scope.moviesFound, { imdbID : $scope.movie.imdbID });
			if(searchResult !== undefined) {
				searchResult.movieId = undefined;
			}
			$scope.movie.movieId = undefined;
			$scope.movies.splice($scope.movies.indexOf($scope.movie), 1);
		}, function(error) {
			Helpers.Dialog.Alert({
				title : LocalStrings.ErrorRemovingMovie,
				message : error.data
			});
		});
	}
	
	Helpers.App.Delegate.Init($q).then(function() {  
		Helpers.Loader.Show();
		FacebookService.GetProfile().then(function(profile) {
			$scope.profile = profile;
			_loadMyMovies();		
		}, function(error) {
			Helpers.Loader.Hide();
			Helpers.Dialog.Alert({
				title : LocalStrings.ErrorLoadingFacebook,
				message : error
			});
		});
	});
	
	function _loadMyMovies()
	{
		DBService.getMovies().then(function(response) {
			$scope.hasLoaded = true;
			$scope.genres = response.data.genre;
			angular.copy(response.data.genre, $scope.selectedGenres);
			$scope.movies = response.data.movies, $scope.movies;
		}, function(response) {
			Helpers.Dialog.Prompt({
				title : LocalStrings.ErrorLoadingMovies,
				message : response.data,
			}, function() {
				Helpers.Page.Delegate.Navigate('#myMovies', function() {
					_loadMyMovies();				
				});
			});
		});
	}
}