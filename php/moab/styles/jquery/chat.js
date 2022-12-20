function refresh_chat() {
	$.ajax({
		type: "POST",
		url: "./ajax/chat.php",
		data: {refresh:""}
	}).done(function(result) {
		$('#ChatBoxContent').html(result);
	});
	setTimeout("refresh_chat()", 3000);
}
function clear() {
	document.getElementById("chatInput").value = '';
}
function send_message() {
	usermessage = document.getElementById("chatInput").value;
	$.ajax({
		type: "POST",
		url: "./ajax/chat.php",
		data: {sendMessage:"",message:usermessage}
	}).done(function(result) {
	});
	clear();
	refresh_chat();
	return false;
}

//Party Mode Functions
function hexToRgb(hex) {
    var bigint = parseInt(hex, 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;

    return "rgba("+r + ", " + g + ", " + b + ", .5)";
}
function change_box_color() {
	var colors = new Array("6C2DC7", "00FFFF", "00FF00", "FFFF00", "FF0000", "FF00FF", "1589FF", "8D38C9", "E3319D", "6C2DC7", "7E354D", "736AFF", "306754", "E45E9D", "000099", "33CC00", "25A0C5", "1F45FC", "3BB9FF", "4CC417", "7D0552", "F433FF", "583759", "8D38C9");
	var randomnum = Math.floor(Math.random() * colors.length)
	var random_color = colors[randomnum];
	$("#ChatBox,#chatInput").css({ 
		boxShadow: '0 0 25px 10px '+hexToRgb(random_color)+''
	});
}

function normal_shadow() {
	$("#ChatBox,#chatInput").css({ 
		boxShadow: '0 0 13px 3px rgba(247, 13, 26, .5)'
	});
}

//Sound Library From Mageek
//http://stackoverflow.com/questions/11330917/how-to-play-a-mp3-using-javascript
//With edits from myself
function change_song() {
	var song = $.get('./ajax/music.php', function(data) {
		if(window.music.checkmute()) {
			mute = true;
		} else {
			mute = false;
		}
		window.music.stop();
		window.music = new Sound(data,100,true);
		window.music.start();
		if(mute) {
			window.music.mute();
		} else {
			window.music.unmute();
		}
	});
}
function formatSecondsAsTime(secs, format) {
  var hr  = Math.floor(secs / 3600);
  var min = Math.floor((secs - (hr * 3600))/60);
  var sec = Math.floor(secs - (hr * 3600) -  (min * 60));

  if (min < 10){ 
    min = "0" + min; 
  }
  if (sec < 10){ 
    sec  = "0" + sec;
  }

  return min + ':' + sec;
}

function Sound(source,volume,loop)
{
    this.source=source;
    this.volume=volume;
    this.loop=loop;
    var son;
    this.son=son;
    this.finish=false;
    this.stop=function()
    {
		document.getElementById("music_information").innerHTML = '';
		document.getElementById("music_time").innerHTML = '';
		document.getElementById("music_controls").innerHTML = '';
        document.body.removeChild(this.son);
    }
    this.start=function()
    {
		document.getElementById("music_information").innerHTML = '<marquee behavior="scroll" direction="left" scrollamount="7">'+this.source.replace('.mp3', '')+'</marquee>';
		document.getElementById("music_controls").innerHTML = '<a onclick="window.music.mute();" id="mute_button"><img src="./styles/images/unmute_button.png"></a> <a onclick="window.music.pause();" id="pause_button"><img src="./styles/images/pause_button.png"></a> <a onclick="change_song();"><img src="./styles/images/next_button.png"></a>';
        if(this.finish)return false;
        this.son=document.createElement("audio");
		this.son.setAttribute("id","musicplayer");
        this.son.setAttribute("autoplay","autoplay");
		this.mp3 = document.createElement("source");
		this.mp3.setAttribute("type","audio/mpeg");
		this.mp3.setAttribute("src","./music/"+this.source);
		this.son.appendChild(this.mp3);
		this.ogg = document.createElement("source");
		this.ogg.setAttribute("type","audio/ogg");
		this.ogg.setAttribute("src","./music/ogg/"+this.source.replace('.mp3', '.ogg'));
		this.son.appendChild(this.ogg);
        document.body.appendChild(this.son);
		//Time stuff here.
		currentaudio = document.getElementById("musicplayer");
		currentaudio.addEventListener("loadedmetadata", function() {
			songDuration = formatSecondsAsTime(Math.floor(currentaudio.duration));
		});
		this.son.addEventListener("timeupdate", function() {
			currentaudio = document.getElementById("musicplayer");
			currentTime = formatSecondsAsTime(Math.floor(currentaudio.currentTime));
			document.getElementById("music_time").innerHTML = 'Current song: '+currentTime+' / '+songDuration;
		});
		//Add ended event to change song
		this.son.addEventListener("ended", function() {
			change_song();
		});
    }
    this.remove=function()
    {
        document.body.removeChild(this.son);
        this.finish=true;
    }
    this.init=function(volume,loop)
    {
        this.finish=false;
        this.volume=volume;
        this.loop=loop;
    }
	this.mute=function()
	{
		currentaudio = document.getElementById("musicplayer");
		currentaudio.volume=0.0;
		document.getElementById("mute_button").innerHTML = '<img src="./styles/images/mute_button.png">';
		document.getElementById("mute_button").onclick = function () { window.music.unmute(); };
		return true;
	}
	this.unmute=function() 
	{
		currentaudio = document.getElementById("musicplayer");
		currentaudio.volume=1.0;
		document.getElementById("mute_button").innerHTML = '<img src="./styles/images/unmute_button.png">';
		document.getElementById("mute_button").onclick = function () { window.music.mute(); };
	}
	this.checkmute=function()
	{
		currentaudio = document.getElementById("musicplayer");
		if(currentaudio.volume == 0.0) {
			return true;
		} else {
			return false;
		}
	}
	this.pause=function()
	{
		currentaudio = document.getElementById("musicplayer");
		currentaudio.pause();
		document.getElementById("pause_button").innerHTML = '<img src="./styles/images/play_button.png">';
		document.getElementById("pause_button").onclick = function () { window.music.play(); };
	}
	this.play=function()
	{
		currentaudio = document.getElementById("musicplayer");
		currentaudio.play();
		document.getElementById("pause_button").innerHTML = '<img src="./styles/images/pause_button.png">';
		document.getElementById("pause_button").onclick = function () { window.music.pause(); };
	}
}

