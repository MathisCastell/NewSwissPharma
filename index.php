<?php 

require_once('./lib/functions.php');

// Check if 'a' key is set in $_GET
$action = isset($_GET['a']) ? $_GET['a'] : '';

if ($action == "logout")
{
	logout();	
}
elseif (islogged())
{
	header('location:frais.php');	
}
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Login GSB</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<style type="text/css">
	body {background: transparent url('img/loginfullbckground.png')}
	html, body {margin: 0; padding: 0;}
	#login {margin: 100px auto; height: 300px; width: 312px; position: relative; background: transparent url('img/loginbckground.png') no-repeat center center; font-family: Straight; color: #515151;}
	#loginmsg {position: absolute; top: 50px; left: 5px; text-align: center; width: 300px;}
	#loginform {position: absolute; top: 90px; left: 5px; height: 150px; width: 280px; padding: 10px;}
	#loginform .inputwrap {background: transparent url('img/logininput.png') no-repeat center center; height: 30px; width: 275px; position: relative; padding: 5px 0}
	#loginform .inputwrap:last-child {margin-top: 0px;}
	#loginform input.input {position: absolute; height: 19px; top: 8px; left: 30px; width: 219px; border: 0; background-color: transparent;}
	#loginform .submitwrap {background: transparent url('img/loginbutton.png') no-repeat center center; width: 275px; height: 35px; position: relative; margin-top: 15px;}
	#loginform input.submit {border: 0;  cursor: pointer; position: absolute; left: 95px; top: 3px; height: 28px; width: 85px; background-color: transparent;}
	#loginform .tickwrap {text-align: center; margin-top: 15px;}
	#loginform .tickwrap a {text-decoration: none; color: inherit;}
	#loginform .tickwrap label {margin-left: 10px; font-size: 14px; line-height: 14px; cursor: pointer;}
	#loginfootbar {position: fixed; bottom: 0; width: 100%; height: 5px; border-top: 1px solid #bababa; background-color: #FFFFFF; box-shadow: 0 0 5px #BABABA; padding: 10px 0 15px 0; font-family: Straight; color: #515151; font-size: 12px;}
	#loginfootbar .leftmsg {float: left; margin-left: 20px;}
	#loginfootbar .rightmsg {float: right; margin-right: 20px;}
</style>

<script type="text/javascript">
	var ajaxpath = "http://localhost/PROJET-GSB/xmlhttp.php";
	var a, b;
	
	$(document).ready(function()
	{
		$('#loginform label').click(function(){$('input[type=checkbox]').trigger('click');})
		$('#loginform').submit(function(){login(); return false;})
		
		function login()
		{
			loginmsg('working');
			
			var username = $('#username').val(), password = $('#password').val(), remember = $('#remember').is(":checked");
			
			if (!username || !password)
			{
				loginmsg('empty');
				return false;
			}
			
			a = setInterval(function(){loginmsg('wait'); window.clearInterval(a)}, 2500);
			b = setInterval(function(){loginmsg('timeout'); window.clearInterval(b)}, 5000);
			
			$.post(ajaxpath+"?a=login", {username: username, password: password, remember: remember}, function(data)
				{
					window.clearInterval(a);
					window.clearInterval(b);
					
					var obj;
					
					try
					{
						obj = jQuery.parseJSON(data);
					}
					catch(err)
					{
						loginmsg();
						return false;
					}
					
					if (obj.error || obj.success == false)
					{
						// $("#loginform").effect("bounce", { times:6, distance:20, direction:'left' }, 800);
						loginmsg("bad");
						return false;
					}
					if (obj.success == true)
					{
						loginmsg("welcome");
						window.location.href="frais.php";	
					}
				});	
		}
		
		function loginmsg(reason)
		{
			var msg = $('#loginmsg').html(), color, font;
			
			if (!reason)
			{
				color = "red";
				msg = "Une erreur est survenue. Merci d'essayer plus tard";
			}	
			if (reason == "empty")
			{
				msg = "Tous les champs sont requis";
				color = "red";
				font = "bold";	
			}
			if (reason == "working")
			{
				msg = "Chargement ...";	
			}
			if (reason == "bad")
			{
				msg = "Mauvais identifiants !";
				color = "red";
				font = "bold";	
			}
			if (reason == "wait")
			{
				msg = "Toujours en chargement.. Attendez SVP";
				color = "#C09853";	
			}
			if (reason == "timeout")
			{
				msg = "Le serveur ne répond pas. Merci d'essayer plus tard";	
				color = "red";
			}
			if (reason == "welcome")
			{
				msg = "Bienvenue !";
				color = "#468847";
			}
			
			msg = !msg ? "Bonjour, vous savez quoi faire" : msg;
			font = !font ? "normal" : font;
			color = !color ? "#515151" : color;
			
			$('#loginmsg').html('<style>#loginmsg {color: '+color+'; font-style: '+font+';}</style>'+msg);
		}
	})
</script>

</head>
<body>

<div class="container">

	<div id="login">
    	<div id="loginmsg">
        	Swisspharma
        </div>
        <form id="loginform" method="post" action="">
        	<div class="inputwrap">
            	<input type="text" name="username" placeholder="Identifiant" class="input" id="username" autocomplete="off"/>
            </div>
        	<div class="inputwrap">
            	<input type="password" name="password" placeholder="Mot de passe" class="input" id="password" autocomplete="off"/>
            </div>
            <div class="tickwrap">
            	<a href="inscription.php">Pas encore inscrit ?</a>
            </div>
            <div class="submitwrap">
            	<input type="submit" name="login" class="submit" value=""/>
            </div>
        </form>
    </div>

</div>

<div id="loginfootbar">
	<span class="leftmsg">Heure du serveur: <?php echo date('H:i', time());?></span>
	
</div>

<body>
</body>
</html>