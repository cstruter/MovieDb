<?php 

session_start();

include 'bootstrap.php';

$repository = $entityManager->getRepository('Models\Movie');
$movieService = new MovieService($repository, FBAPI, FBSECRET);

?>