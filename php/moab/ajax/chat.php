<?php
/*
Page: Ajax Chat
*/
require_once('../includes/functions.php');
if(isset($_POST['refresh'])) {
	refresh_chat();
}

if(isset($_POST['sendMessage'])) {
	send_message($_POST['message']);
}
?>