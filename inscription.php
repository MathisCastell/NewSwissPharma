<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Inscription GSB</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="./js/jquery.validate.js"></script>
<script src="./js/bootstrap-button.js"></script>
<script src="./js/bootstrap-alert.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	
		var ajaxpath = "http://localhost/PROJET-GSB/xmlhttp.php";
	
		$("#signup").validate({
			
			rules:{
				nom: "required",
				prenom: "required",
				adresse: "required",
				cp: {
					required: true,
					number: true,
					minlength: 5,
					maxlength: 5
				},
				ville: "required",
				email:{
					required: true,
					email: true
				},
				type:" required",
				pseudo: "required",
				passwd:{
					required: true,
					minlength: 8
				},
				conpasswd:{
					required: true,
					equalTo: "#passwd"
				}
			},
			
			errorClass: "help-inline"
		
		});
		
		function register()
		{
			var nom = $("#nom").val(), prenom = $("#prenom").val(), adresse = $("#adresse").val(), cp = $("#cp").val(), ville = $("#ville").val(), email = $("#email").val(), pseudo = $("#pseudo").val(), pass = $("#passwd").val(), conpass = $("#conpasswd").val(), type = $('.btn-group > button.btn.active').html();
			
			$.post(ajaxpath+"?a=register", {nom: nom, prenom: prenom, adresse: adresse, cp: cp, ville: ville, email: email, pseudo: pseudo, pass: pass, comfpass: conpass, type: type}, function(data)
			{
					var obj;
					$('#alertbox').html('');	
				
					try
					{
						obj = jQuery.parseJSON(data);
					}
					catch(err)
					{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Il y a eu une erreur, merci d\'essayer ultérieurement !</div> ');
						return false;
					}	
					
					if (obj.valid == false)
					{
						var e = obj.error;
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> '+e+'</div> ');
					}		
					
					if (obj.valid == true)
					{
						$('#alertbox').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong>Bravo !</strong> Vous pouvez vous connecter à l\'aide de votre pseudo et de votre mot de passe <a href="./index.php">ici</a></div> ');
					}	
			});
		}
		
		$("#signup").submit(function(){
			if ($('#signup').valid()) 
			{
				register();
				return false;
			}
		})
		
	});
</script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/localization/messages_fr.js"></script>
<link href="./css/bootstrap.css" rel="stylesheet">
<link href="./css/fontawesome.css" rel="stylesheet">
<style type="text/css">
body {background: transparent url('img/loginfullbckground.png')}
#loginfootbar {position: fixed; bottom: 0; width: 100%; height: 5px; border-top: 1px solid #bababa; background-color: #FFFFFF; box-shadow: 0 0 5px #BABABA; padding: 10px 0 15px 0; font-family: Straight; color: #515151; font-size: 12px; line-height: 12px;}
#loginfootbar .leftmsg {float: left; margin-left: 20px;}
#loginfootbar .rightmsg {float: right; margin-right: 20px;}
</style>
</head>
<body>
	<div style="clear: both; margin: 50px 0"></div>
    <div class="container">
      <div id="alertbox"></div> 
      <div class="well" style="box-shadow: 0 0 5px #BABABA">
        <form id="signup" class="form-horizontal" method="post" action="">
          <legend>Créer un compte GSB</legend>
          <div class="control-group">
            <label class="control-label">Nom</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-user"></i></span>
                <input type="text" class="input-xlarge" id="nom" name="nom" placeholder="Nom">
              </div>
            </div>
          </div>
          <div class="control-group ">
            <label class="control-label">Prénom</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-user"></i></span>
                <input type="text" class="input-xlarge" id="prenom" name="prenom" placeholder="Prénom">
              </div>
            </div>
          </div>
          <div class="control-group ">
            <label class="control-label">Adresse</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-home"></i></span>
                <input type="text" class="input-xlarge" id="adresse" name="adresse" placeholder="Adresse">
              </div>
            </div>
          </div>
          <div class="control-group ">
            <label class="control-label">Code Postal</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-home"></i></span>
                <input type="text" class="input-xlarge" id="cp" name="cp" placeholder="Code postal">
              </div>
            </div>
          </div>
          <div class="control-group ">
            <label class="control-label">Ville</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-home"></i></span>
                <input type="text" class="input-xlarge" id="ville" name="ville" placeholder="Ville">
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Email</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
                <input type="text" class="input-xlarge" id="email" name="email" placeholder="Email">
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" style="margin-top: 10px;">Utilisateur</label>
            <div class="controls">
              <p>
              <div id="type" name="type" class="btn-group" data-toggle="buttons-radio">
                <button type="button" class="btn btn-info active">Visiteur</button>
                <button type="button" class="btn btn-info">Comptable</button>
              </div>
              </p>
            </div>
          </div>
          <div class="control-group ">
            <label class="control-label">Pseudo</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-user"></i></span>
                <input type="text" class="input-xlarge" id="pseudo" name="pseudo" placeholder="Pseudo">
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Mot de passe</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-lock"></i></span>
                <input type="Password" id="passwd" class="input-xlarge" name="passwd" placeholder="Mot de passe">
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Confirmer mot de passe</label>
            <div class="controls">
              <div class="input-prepend"> <span class="add-on"><i class="icon-lock"></i></span>
                <input type="Password" id="conpasswd" class="input-xlarge" name="conpasswd" placeholder="Retaper mot de passe">
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
              <button onclick="window.location = './index.php'; return false;" class="btn btn-inverse" >Retour</button>
              <button type="submit" class="btn btn-success" >Créer mon compte</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <div style="clear: both; margin: 55px 0"></div>
    

    
</body>
</html>