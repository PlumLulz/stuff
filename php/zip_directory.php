<?php
//Zip directory script
//By: Plum
//Visit: http://p0wersurge.com


//Just some basic settings
@ini_set("memory_limit", "9999M");
@ini_set("max_execution_time", "0");
set_time_limit(0);


//Edit these variables

//Directory to zip
//No trailing /
$mddir = "/var/www";

//Name of zip archive
$nameofzip = "test2.zip";

//Functions

//If directory is empty function
function dirIsEmpty($directory) {
    $scan = scandir($directory);
    $dirs = array();
    $files = array();
    foreach ($scan as $f) {
        if ($f != '.' && $f != '..') {
            if (is_dir($directory . '/' . $f)) {
                array_push($dirs, $f);
            } else {
                array_push($files, $f);
            }
        }
    }
    if (empty($files)) {
        return true;
    } else {
        return false;
    }
}

//Mass files funtion
function files($mass_dir) {
    if ($dh = opendir($mass_dir)) {
        $files = array();
        $inner_files = array();
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                if (is_dir($mass_dir . "/" . $file)) {
                    if (dirIsEmpty($mass_dir . '/' . $file)) {
                        array_push($files, "$mass_dir/$file");
                    }
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

//Create zip archive
$zip = new ZipArchive;
if ($zip->open($nameofzip, ZipArchive::CREATE) === TRUE) {
    foreach (files($mddir) as $key => $file) {
        $file2 = rtrim($file, ".");
        if (dirname($file2) != $mddir) {
            $newname = trim(str_replace($mddir, "", $file2), '/');
            if (is_dir($file2) && dirIsEmpty($file2)) {
                if ($zip->addEmptyDir($newname)) {
                    echo "<font color='green'><b>Created empty directory $file2 to $nameofzip</b></font><br>";
                } else {
                    echo "<font color='red'><b>Failed to create empty directory $file2 to $nameofzip</b></font><br>";
                }
            } else {
                if ($zip->addFile($file2, $newname)) {
                    echo "<font color='green'><b>Zipped $file2 to $nameofzip</b></font><br>";
                } else {
                    echo "<font color='red'><b>Failed to zip $file2 to $nameofzip</b></font><br>";
                }
            }
        } else {
            $newname = trim(str_replace($mddir, "", $file2), '/');
            if (is_dir($file2) && dirIsEmpty($file2)) {
                if ($zip->addEmptyDir($newname)) {
                    echo "<font color='green'><b>Created empty directory $file2 to $nameofzip</b></font><br>";
                } else {
                    echo "<font color='red'><b>Failed to create empty directory $file2 to $nameofzip</b></font><br>";
                }
            } else {
                if ($zip->addFile($file2, "$newname")) {
                    echo "<font color='green'><b>Zipped $file2 to $nameofzip</b></font><br>";
                } else {
                    echo "<font color='red'><b>Failed to zip $file2 to $nameofzip</b></font><br>";
                }
            }
        }
    }
    $zip->close();
} else {
    echo "<font color='red'><b>Failed to create zip archive: $nameofzip</b></font><br>";
}
?>
