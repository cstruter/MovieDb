<?php 

session_start();

include 'vendor/autoload.php';

use Repositories\Movies,
	CSTruter\Misc\Data\MySqlAdapter;

$mysqlAdapter = new MySqlAdapter(MYSQL_HOST, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
$repository = new Movies($mysqlAdapter);
$movieService = new MovieService($repository, FBAPI, FBSECRET);	

?>