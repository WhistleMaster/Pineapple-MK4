<?php
	
include('includes/db_connect.php'); 
include('includes/functions.php'); 
include('includes/vars.php');

sec_session_start();

$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
session_destroy();
header('Location: ./');
	
?>