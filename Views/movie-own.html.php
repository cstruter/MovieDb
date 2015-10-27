<div id="myMovies" data-role="page" data-theme="b">
	<div data-role="panel" data-position-fixed="true" id="settings">
		<p>{{profile.first_name}} {{profile.last_name}}</p>
	</div>
	<div data-role="header">
		<div data-role="controlgroup" data-type="horizontal" class="ui-mini ui-btn-left">
			<a href="#settings" ng-hide="!profile.first_name" data-role="button" data-icon="gear" data-iconpos="notext">&nbsp;</a>
		</div>		
		<h1 ng-show="profile.first_name"><?=$_LOCAL['MyMovies']?> ({{movies.length}})</h1>
	</div>
	<div role="main" class="ui-content">
		<input type="text" ng-model="myMoviePhrase" />
		<jqm-select ng-model="selectedGenres" jqm-options="genre for genre in genres track by genre" 
			jqm-theme="b" jqm-native-menu="false" jqm-multiple="true"></jqm-select>
		<div ng-show="!movies.length && hasLoaded">
			<?=$_LOCAL['NoMoviesAdded']?>
		</div>
		<ul id="myMovieList" jqm-listview>
			<li ng-repeat="movie in movies | byPropertyInArray:'Genre':selectedGenres | filter:myMoviePhrase track by movie.imdbID">
				<a href="#movie" jqm-on-show="viewMovie(movie)">
				<img ng-src="{{imageSource(movie)}}" class="ui-li-thumb" />
				<h2>{{movie.Title}} ({{movie.Year}})</h2></a>
			</li>
		</ul>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#myMovies" class="ui-btn-active ui-state-persist" data-icon="video"><?=$_LOCAL['MyMovies']?></a></li>
				<li><a href="#findPage" data-icon="search"><?=$_LOCAL['Find']?></a></li>
			</ul>
		</div>
	</div>	
</div>