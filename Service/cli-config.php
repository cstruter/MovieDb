<?php

include 'bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner,
	Doctrine\ORM\EntityManager;
	
try {
	$entityManager->getConnection()->connect();
}
catch(Exception $ex) {
	$connection = EntityManager::create([
		'driver'   => 'pdo_mysql',
		'host' 	   => MYSQL_HOST,
		'user'     => MYSQL_USERNAME,
		'password' => MYSQL_PASSWORD,
		'charset' => 'UTF8'
	], $config)->getConnection();
	$connection->executeUpdate('CREATE DATABASE '.MYSQL_DATABASE.' CHARACTER SET utf8 COLLATE utf8_general_ci');
}

return ConsoleRunner::createHelperSet($entityManager);

?>