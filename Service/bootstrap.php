<?php

include 'vendor/autoload.php';

use Repositories\Movies,
	Doctrine\ORM\Tools\Setup,
	Doctrine\ORM\EntityManager;

$config = Setup::createAnnotationMetadataConfiguration(['Models'], false, null, null, false);
$entityManager = EntityManager::create([
    'driver'   => 'pdo_mysql',
	'host' 	   => MYSQL_HOST,
    'user'     => MYSQL_USERNAME,
    'password' => MYSQL_PASSWORD,
    'dbname'   => MYSQL_DATABASE,
	'charset' => 'UTF8'
], $config);

?>