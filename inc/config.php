<?php
/**
*	database credentials
*/

$dbhost = "";
$dbuser = "";
$dbpass = "";
$dbname = "";

session_start();
$session_id = "id";

//define rutes
define('INCL_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(INCL_DIR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);
?>