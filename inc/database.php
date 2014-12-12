<?php
/**
*   database connection object and included files
*/
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'session.php');
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'functions.php');

require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'users.php');
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'events.php');
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'simple-php-captcha.php');

	try{
		$db = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $ex){
		die($ex->getMessage());
	}

//initialize objects
$users = new users($db);
$events = new events($db);
$session = new session();

//error/announce arrays
$errors = array();
$announces = array();
?>