<?php
/*
Page: Ajax Login
*/
require_once('../includes/functions.php');
if(isset($_POST['login'])) {
	login($_POST['username'], $_POST['password'], $_POST['rememberme'], "./index.php");
}
if(isset($_GET['logout'])) {
	logout();
}
?>