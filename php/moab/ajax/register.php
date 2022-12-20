<?php
/*
Page: Ajax Register
*/
require_once('../includes/functions.php');
if(isset($_POST['register'])) {
	if(user_logged_in()) {
		echo "<center>You are already logged in!</center>";
	} else {
		register($_POST['username'], $_POST['password'], $_POST['password2'], $_POST['email'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
	}
}
?>