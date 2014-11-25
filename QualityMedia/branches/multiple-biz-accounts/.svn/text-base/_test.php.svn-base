<?php 
$dsn = 'mysql:host=qmdbm.cbmovomozg13.us-west-2.rds.amazonaws.com;port=3306;dbname=qmdb';
$username = 'qm_billing';
$password = 'nbEambdlG0Li';

try {
	$dbh = new PDO($dsn, $username, $password);
	print "Connected.";
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
}

die();