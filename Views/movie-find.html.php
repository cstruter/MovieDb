<div id="findPage" data-role="page" data-theme="b">
	<div data-role="header">
		<h1><?=$_LOCAL['FindMovies']?></h1>
	</div>
	<div role="main" class="ui-content">
		<div data-role="controlgroup" data-type="horizontal">
			<input type="text" ng-model="searchPhrase" data-wrapper-class="controlgroup-textinput ui-btn searchInput" />
			<button ng-click="findMovie()"><?=$_LOCAL['Find']?></button>					
		</div>
		<div ng-show="searchError">
			{{searchError}}
		</div>
		<ul id="searchResults" jqm-listview>
			<li ng-repeat="movie in moviesFound track by movie.imdbID" ng-class="hasMovie(movie) ? 'my-precious' : ''">
				<a href="#movie" jqm-on-show="viewMovie(movie)">{{movie.Title}} ({{movie.Year}})</a>
			</li>
		</ul>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#myMovies" data-icon="video"><?=$_LOCAL['MyMovies']?></a></li>
				<li><a href="#findPage" class="ui-btn-active ui-state-persist" data-icon="search"><?=$_LOCAL['Find']?></a></li>
			</ul>
		</div>
	</div>
</div>