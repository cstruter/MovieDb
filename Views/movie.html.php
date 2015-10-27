<div id="movie" data-role="page" data-theme="b">
	<div data-role="header">
		<div data-role="controlgroup" data-type="horizontal" class="ui-mini ui-btn-left">
			<a ng-hide="hasMovie(movie)" data-role="button" data-icon="plus" data-iconpos="notext" ng-click="addMovie()">&nbsp;</a>
			<a ng-show="movie.movieId" data-role="button" data-icon="minus" data-iconpos="notext" ng-click="removeMovie()">&nbsp;</a>
		</div>			
		<h1><?=$_LOCAL['Movie']?></h1>
	</div>
	<div role="main" class="ui-content">
		<ul data-role="listview" data-inset="true" ng-show="movie.Title">
			<li>
				<img ng-src="{{imageSource(movie)}}" />
				<h1>{{movie.Title}} ({{movie.Year}})</h1>
			</li>
			<li class="wrap">{{movie.Rated}} | {{movie.Runtime}} | {{movie.Released}} | {{movie.Genre}} </li>
			<li class="wrap">{{movie.Plot}}</li>
			<li class="wrap">{{movie.Awards}}</li>
		</ul>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#myMovies" data-icon="video"><?=$_LOCAL['MyMovies']?></a></li>
				<li><a href="#findPage" data-icon="search"><?=$_LOCAL['Find']?></a></li>
			</ul>
		</div>
	</div>			
</div>