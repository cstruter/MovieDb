<!DOCTYPE html>
<html ng-app="App">
	<head>
		<title></title>
		<?php include 'config.php'; ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<script type="text/javascript">
			var LocalStrings = <?=json_encode($_LOCAL)?>;
			var Settings = {
				FbApi : '<?=FBAPI?>',
				Local : 'Service/',
				Remote : 'http://www.omdbapi.com/'
			};
		</script>
		<script type="text/javascript" src="js/app.js"></script>
	</head>
	<body ng-controller="MovieController">
		<?php include 'Views/movie-own.html.php'; ?>
		<?php include 'Views/movie-find.html.php'; ?>
		<?php include 'Views/movie.html.php'; ?>
	</body>
</html>