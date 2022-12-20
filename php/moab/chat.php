<?php
/*
Page: Home
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
$sidebarlinks = array();

//Display main boostrap HTML
//First value is name of page
//Second value is array of secondary side bar links
display_bootstrap("Chat", $sidebarlinks);

//Main body of page
echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">'.$sitename.'</h1>
          <div class="row placeholders" style="text-align: left;">';
if(user_logged_in()) {
	echo "Logged in<br>";
  echo <<<html
      <script src='./styles/jquery/chat.js'></script>
      <script>refresh_chat();</script>
      <form onsubmit="return send_message();">
        <input id="chatInput" type="text" name="message" class="styledInput" style="width: 500px;" autocomplete="off">
      </form>
      <br>
      <div id="ChatBox">
        <div id="ChatBoxContent">
        
        </div>
      </div>
html;
	//display_random_ascii();
} else {
	echo "Logged out<br>";
	//display_random_ascii();
}
echo '</div>
</div>';
?>