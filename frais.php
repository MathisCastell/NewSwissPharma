<?php

	require_once('./lib/functions.php');

	if (!islogged())
	{
		header('location: index.php');
	}
	
	$username = ucfirst($_SESSION['username']);
	$type = strtolower($_SESSION['type']);

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Swisspharma:: Gestion des Frais</title>
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/fontawesome.css" rel="stylesheet">
    <link href="./css/bootstrap-switch.css" rel="stylesheet">
    <link href="./css/datepicker.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="./js/bootstrap-tab.js"></script>
    <script src="./js/bootstrap-dropdown.js"></script>
    <script src="./js/bootstrap-switch.js"></script>
    <script src="./js/bootstrap-datepicker.js"></script>
    <script src="./js/bootstrap-alert.js"></script>
    <script src="./js/bootstrap-tooltip.js"></script>
    <script src="./js/bootstrap-popover.js"></script>
    <script src="./js/dateformat.js"></script>
    <script src="./js/jquery.validate.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/localization/messages_fr.js"></script>
    <style type="text/css">
	.navbar-inner .container {width:960px;} 
	body {background: transparent url('img/loginfullbckground.png')}
	#loginfootbar {position: fixed; bottom: 0; width: 100%; height: 5px; border-top: 1px solid #bababa; background-color: #FFFFFF; box-shadow: 0 0 5px #BABABA; padding: 10px 0 15px 0; font-family: Straight; color: #515151; font-size: 12px; line-height: 12px;}
	#loginfootbar .leftmsg {float: left; margin-left: 20px;}
	#loginfootbar .rightmsg {float: right; margin-right: 20px;}
	table td:not(.nocenter), table th {text-align: center !important;}
	.plusminus {display: inline; margin-left: 15px;}
	.plusminus .badge {cursor: pointer;}
	#infoover {margin-left: 10px; color: #3A87AD; vertical-align: middle;}
	.tablestrip th {background-color: #FFFFFF;}
	.tablestrip {border: 1px solid #DDDDDD; border-top: 0;}
	.hiddenrow {text-align: left;}
	table label:not(.switch-mini) {display: block; float: left; line-height: 20px; margin-bottom: 5px; padding-top: 5px; text-align: right; width: 160px;}
    table .controls {margin-left: 180px;}
	table .input-prepend {display: inline;}
	.closeedit {color: #3A87AD; margin-left: 10px; cursor: pointer;}
	.closeedit:hover {color: #F00;}
    </style>
    <script type="text/javascript">
		$(document).ready(function()
		{
			var ajaxpath = "http://localhost/gsb/xmlhttp.php";
			
			$("#formajouter").validate({
				
				rules:{
					mois: {
						required: true,
						number: true,
						minlength: 2,
						maxlength: 2
					},
					annee: {
						required: true,
						number: true,
						minlength: 4,
						maxlength: 4
					},
					repas: {
						required: true,
						number: true
					},
					nuitees: {
						required: true,
						number: true
					},
					etapes: {
						required: true,
						number: true
					},
					km: {
						required: true,
						number: true
					},
					nbpieces: {
						required: true,
						number: true
					},
					total: {
						required: true,
						number: true
					}
				},
				
				errorClass: "help-inline"
			
			});
			
			$("#formmodifier").validate({
				
				rules:{
					m_repas: {
						required: true,
						number: true
					},
					m_nuitees: {
						required: true,
						number: true
					},
					m_etapes: {
						required: true,
						number: true
					},
					m_km: {
						required: true,
						number: true
					},
					m_nbpieces: {
						required: true,
						number: true
					},
					m_total: {
						required: true,
						number: true
					}
				},
				
				errorClass: "help-inline"
			
			});
			
			$('body').on('click', '.plus', function()
			{
				var span = $(this).parent().find('span.counthere');
				var input = $(this).parent().find('input.dathidden');
				var current = parseInt($(span).html());
				$(span).html(current + 1);
				$(input).val(current + 1);
			})
			
			$('body').on('click', '.minus', function()
			{
				var span = $(this).parent().find('span.counthere');
				var input = $(this).parent().find('input.dathidden');
				var current = parseInt($(span).html());
				if (current > 0)
				{
					$(span).html(current - 1);
					$(input).val(current - 1);
				}
			})

			function modifier()
			{
				$.post(ajaxpath+"?a=editsave", $("#formmodifier").serialize(), function(data)
				{
					try
					{
						obj = jQuery.parseJSON(data);
					}
					catch(err)
					{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Il y a eu une erreur, merci d\'essayer ultérieurement !</div> ');
						$('html,body').animate({scrollTop: 0},'slow');
						return false;
					}	
					
					if (obj.valid == true)
					{
						$('#alertbox').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong>Bravo !</strong> Votre fiche de frais est bien enregistrée.</a></div>');
						$('#editresult').html('');
						$('html,body').animate({scrollTop: 0},'slow');
					}else{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Ça n\'a pas marché. Merci d\'essayer ultérieurement.</div> ');
						$('html,body').animate({scrollTop: 0},'slow');
					}
				});
			}
			
			$('body').on('submit', '#formmodifier', function()
			{
					$('#formmodifier').validate({
					
					rules:{
						m_repas: {
							required: true,
							number: true
						},
						m_nuitees: {
							required: true,
							number: true
						},
						m_etapes: {
							required: true,
							number: true
						},
						m_km: {
							required: true,
							number: true
						},
						m_nbpieces: {
							required: true,
							number: true
						},
						m_total: {
							required: true,
							number: true
						}
					},
					
					errorClass: "help-inline"
				
				});
				if ($('#formmodifier').valid())
				{
					modifier();	
				}
				return false;					
			})
			
			$('#formajouter').submit(function()
			{
				if ($('#formajouter').valid())
				{
					ajouter();	
				}
				return false;
			})
			
			$('.clicktoedit').click(function(event)
			{
				var id = parseInt($(this).attr('data-what'));
				edit(id);
			});
			
			function edit(id)
			{
				$.post(ajaxpath+"?a=edit", {id: id}, function(data)
				{
					try
					{
						obj = jQuery.parseJSON(data);
					}
					catch(err)
					{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Il y a eu une erreur, merci d\'essayer ultérieurement !</div> ');
						scrollToAnchor('alertbox');
						$('#editresult').html('');
						return false;
					}	
					
					if (obj.valid == true)
					{
						$('#alertbox').html('');
						$('#editresult').html(obj.content);
						scrollToAnchor('editresult');
					}else{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> '+obj.error+'</div> ');
						$('#editresult').html('');
						$('html,body').animate({scrollTop: 0},'slow');
					}
				});
			}
			
			function ajouter()
			{
				$.post(ajaxpath+"?a=ajouter", $("#formajouter").serialize(), function(data)
				{
					$("html, body").animate({ scrollTop: 0 }, "slow");
					
					try
					{
						obj = jQuery.parseJSON(data);
					}
					catch(err)
					{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Il y a eu une erreur, merci d\'essayer ultérieurement !</div> ');
						return false;
					}	
					
					if (obj.valid == true)
					{
						var id = obj.id;
						$("#formajouter")[0].reset();
						$('#alertbox').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong>Bravo !</strong> Votre fiche de frais est bien enregistrée. <a href="frais.php?id='+id+'#modifier">Cliquez ici pour la modifier.</a></div> ');
					}else{
						$('#alertbox').html('<div class="alert"><a class="close" data-dismiss="alert">×</a><strong>Attention !</strong> Il y a eu une erreur, merci d\'essayer ultérieurement !</div> ');
					}
				});
			}
			
			var i = 1;
			
			$('#horsforfait').hide();
			$('#switch').on('switch-change', function (e, data) {
				var $el = $(data.el)
				  , value = data.value;
				$('#horsforfait').toggle();
				$('.toggle').toggle();
			});
			
			$('.toggle2').hide();
			$('#switch2').on('switch-change', function (e, data) {
				var $el = $(data.el)
				  , value = data.value;
				$('#vosfrais').toggle();
				$('.toggle2').toggle();
			});
			
			$('body').on("click", ".clicktoadd", function(event)
			{
				var c = parseInt($(this).attr('data-count'));
				if (!$('#hfdate_'+c).val() || !$('#hflibelle_'+c).val() || !$('#hfqte_'+c).val())
				{
					alert('Tous les champs doivent être remplis avant de continuer');
					return false;
				}
				
				$(this).remove(); 
				addfield();
			});
					
			function addfield()
			{
				i++;
				if (i > 10) { alert('Pas plus de 10 à la fois s\'il vous plaît'); return false}
				var newline = '<div class="control-group hf count'+i+'"><label class="control-label">Hors-forfait #<span class="count">'+i+'</span></label><div class="controls"><div class="input-prepend picker" data-date="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"> <span class="add-on"><i class="icon-calendar"></i></span><input type="text" autocomplete="off" class="input-medium" id="hfdate_'+i+'" name="hfdate_'+i+'" placeholder="Date" style="text-align: center;"></div> <input type="text" autocomplete="off" class="input-xlarge" id="hflibelle_'+i+'" name="hflibelle_'+i+'" placeholder="Libellé" style="text-align: center;"> <input type="text" autocomplete="off" class="input-mini" id="hfqte_'+i+'" name="hfqte_'+i+'" placeholder="Quantité" style="text-align: center;"> <button class="btn btn-info clicktoadd" data-count="'+i+'" type="button">+</button></div></div>';
				$('.hf').last().after(newline);
			}	
			
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
			
			$('body').on("click", ".picker", function(event)
			{
				$(this).datepicker(
				{
					onRender: function(date) 
					{
						return date.valueOf() > now.valueOf() ? 'disabled' : '';
					}
				}).on('changeDate', function(ev) {
					var d = new Date(ev.date);
					d = d.format('dd/mm/yyyy'); 
					$(this).find('.input-medium').val(d);
				}).on('show', function(ev) {
					var d = new Date(ev.date);
					d = d.format('dd/mm/yyyy'); 
					$(this).find('.input-medium').val(d);
				})				
			});
			
			// Javascript to enable link to tab
			var url = document.location.toString();
			if (url.match('#')) {
				$('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
				window.scrollTo(0, 0);
			} 
			
			// Change hash for page-reload
			$('.nav-tabs a').on('shown', function (e) {
				window.location.hash = e.target.hash;
				window.scrollTo(0, 0);
			})
			
			$("#infoover").popover(
			{
				offset: 10,
				trigger: 'manual',
				animate: false,
				html: true,
				placement: 'top',
				title: 'À propos des fiches de frais',
				delay: {show: 500, hide: 1000},
				content: '<p>Les frais forfaitaires doivent être justifiés par une facture acquittée faisant apparaître le montant de la TVA.</p>',
				template: '<div class="popover" onmouseover="clearTimeout(timeoutObj);$(this).mouseleave(function() {$(this).hide();});"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
			}).mouseenter(function() 
			{
				$(this).popover('show');
			}).mouseleave(function() 
			{
				var ref = $(this);
				timeoutObj = setTimeout(function()
				{
					ref.popover('hide');
				}, 50);
			});
			
			function scrollToAnchor(aid)
			{
				var aTag = $("#"+aid+"");
				$('html,body').animate({scrollTop: (aTag.offset().top - 50)},'slow');
			}
			
			$(".tablestrip tr.tbody").each(function(i)
			{
				if (i%4 < 2){
					$(this).css("background-color", "#F9F9F9");
				}else{
					$(this).css("background-color", "#FFFFFF");
				}
			});
			
			$(".tablestrip tbody tr.hidethat td div.well").each(function(i)
			{
				if (i%2 < 1){
					$(this).css("background-color", "#FFFFFF");
				}else{
					$(this).css("background-color", "#F9F9F9");
				}
			});
			
			$('tr.hidethat').hide();
			
			$('body').on('click', '.clicktosee', function()
			{
				if ($(this).closest('tr').next().is(':visible'))
				{$(this).html('Afficher cette fiche');}else{$(this).html('Masquer cette fiche');}
				$(this).closest('tr').next().toggle();
				return false;	
			})
			
			$('.switchvalide').on('switch-change', function (e, data) {
				var $el = $(data.el), value = data.value;
				var fid = parseInt($(this).attr('data-fid'));
				$.post(ajaxpath+"?a=valider", {fid: fid, state: value}, function(data)
				{
					
				});
			});
			
			$('body').on('click', '.closeedit', function()
			{
				$("#editresult").html('');	
				window.scrollTo(0, 0);
			})
						
		})
	</script>
</head>
<body>
    <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
                <div class="container" id="container">
                      <a class="brand" href="index.php">Galaxy Swiss Bourdin</a>
                        <ul class="nav pull-right">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Bonjour, <?php echo $username ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a><i class="icon-user"></i> Vous êtes un <?php echo $type; ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="index.php?a=logout"><i class="icon-off"></i> Se déconnecter</a></li>
                                </ul>
                            </li>
                        </ul>
                </div>
          </div>
    </div>
    
    <div style="clear: both; margin: 80px 0"></div>
    <div class="container">
    	<div id="alertbox"></div> 
        <div class="well" style="box-shadow: 0 0 5px #BABABA">
            <ul class="nav nav-tabs">
            	<?php if (strtolower($_SESSION['type']) == 'visiteur'): ?>
                  <li class="active"><a href="#ajouter" data-toggle="tab"><i class="icon-plus"></i> Ajouter une fiche de frais</a></li>
                  <li><a href="#modifier" data-toggle="tab"><i class="icon-edit"></i> Modifer une fiche de frais</a></li>
                  <?php endif; ?>
                  <?php if (strtolower($_SESSION['type']) == 'comptable'): ?>
                  <li class="active"><a href="#stats" data-toggle="tab"><i class="icon-bar-chart"></i> Statistiques</a></li>
                  <li><a href="#valider" data-toggle="tab"><i class="icon-ok"></i> Valider une fiche de frais</a></li>
                  <?php endif; ?>
            </ul>
            <div id="myTabContent" class="tab-content">
            <?php if (strtolower($_SESSION['type']) == 'visiteur'): ?>
                <div class="tab-pane active in" id="ajouter">
                	<legend>Période d'engagement</legend>
                    <form id="formajouter" class="form-horizontal" action="">
                          <div class="control-group">
                            <label class="control-label">Période d'engagement</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-small" id="mois" name="mois" placeholder="Mois (2 chiffres)" style="text-align: center;" value="<?php echo date('m', time()); ?>">
                                <input type="text" autocomplete="off" class="input-small" id="annee" name="annee" placeholder="Année (4 chiffres)" style="text-align: center;" value="<?php echo date('Y', time()); ?>">                           
                            </div>
                          </div> 
                          <legend>Frais au forfait <span id="infoover" class="icon-warning-sign"></span>
                          </legend>  
                          <div class="control-group">
                            <label class="control-label">Repas</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-mini" id="repas" name="repas" placeholder="Repas" style="text-align: center;">                           
                            	<div class="plusminus">
                                	<span class="badge badge-info minus"><i class="icon-minus"></i></span>
                                    <span class="counthere" style="margin: 0 5px;">1</span>
                                    <input type="hidden" name="crepas" class="dathidden" value="1" />
                                	<span class="badge badge-info plus"><i class="icon-plus"></i></span>
                                </div>
                            </div>
                          </div> 
                          <div class="control-group">
                            <label class="control-label">Nuitées</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-mini" id="nuitees" name="nuitees" placeholder="Nuitées" style="text-align: center;">                           
                            	<div class="plusminus">
                                	<span class="badge badge-info minus"><i class="icon-minus"></i></span>
                                    <span class="counthere" style="margin: 0 5px;">1</span>
                                    <input type="hidden" name="cnuitees" class="dathidden" value="1" />
                                	<span class="badge badge-info plus"><i class="icon-plus"></i></span>
                                </div>
                            </div>
                          </div>   
                          <div class="control-group">
                            <label class="control-label">Étapes</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-mini" id="etapes" name="etapes" placeholder="Étapes" style="text-align: center;">                           
                            </div>
                          </div>  
                          <div class="control-group">
                            <label class="control-label">Kilomètres</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-mini" id="km" name="km" placeholder="Km" style="text-align: center;">                           
                            </div>
                          </div>  
                          <legend>Frais hors-forfait <div style="margin-left: 10px; vertical-align: middle; display: inline; margin-top: -5px;"><div class="switch switch-mini" data-on-label="Oui" data-off-label="Non" id="switch"><input type="checkbox"></div></div></legend>                   
                    		<div class="toggle" style="margin-top: -10px; margin-bottom: 10px;"><i>Si vous avez des frais hors-forfait à saisir, cliquez sur le bouton ci-dessus pour faire apparaître le formulaire</i></div>
                            <div id="horsforfait">
                              <div class="control-group hf">
                                <label class="control-label">Hors-forfait #1</label>
                                <div class="controls">
                                	<div class="input-prepend picker" data-date="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"> <span class="add-on"><i class="icon-calendar"></i></span>
                                    	<input type="text" autocomplete="off" class="input-medium" id="hfdate_1" name="hfdate_1" placeholder="Date" style="text-align: center;">   
                                    </div>
                                    <input type="text" autocomplete="off" class="input-xlarge" id="hflibelle_1" name="hflibelle_1" placeholder="Libellé" style="text-align: center;">
                                    <input type="text" autocomplete="off" class="input-mini" id="hfqte_1" name="hfqte_1" placeholder="Quantité" style="text-align: center;">                           
                                	<button class="btn btn-info clicktoadd" type="button" data-count="1">+</button>
                                </div>
                              </div>                             	
                            </div>
                            <legend>Justificatifs</legend>
                          <div class="control-group">
                            <label class="control-label">Nombre de pièces</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="input-medium" id="nbpieces" name="nbpieces" placeholder="Nmbre" style="text-align: center;">                           
                            </div>
                          </div> 
                          <div class="control-group">
                            <label class="control-label">Montant total</label>
                            <div class="controls">
                            	<div class="input-append">
                                	<input type="text" autocomplete="off" class="input-medium" id="total" name="total" placeholder="Total" style="text-align: center;"> 
                                	<span class="add-on">€</span>
                                </div>                          
                            </div>
                          </div> 
                          
                          <div class="control-group">
                            <div class="controls">
                              <button type="submit" class="btn btn-success" >Enregistrer</button>
                            </div>
                          </div>                         
                          
                    </form>
                </div>
                <div class="tab-pane in" id="modifier">
                	<?php 
						$q = $db->select('frais_forfait', 'count(fid) as COUNT', "uid = '{$_SESSION['uid']}'");
						if ($db->num_rows($q))
						{
							$countfrais = intval($db->fetch_field($q, "COUNT"));	
						}
					?>
                	<legend>Vos fiches de frais <span class="badge badge-info" style="vertical-align: middle;"><?php echo $countfrais; ?></span><div style="margin-left: 10px; vertical-align: middle; display: inline;"><div class="switch switch-mini" data-on-label="Cacher" data-off-label="Afficher" id="switch2"><input type="checkbox" checked="checked"></div></div></legend>                
   					<div class="toggle2" style="margin-top: -10px; margin-bottom: 10px;"><i>Vous avez <?php echo $countfrais; ?> fiche(s) de frais, cliquez sur le bouton ci-dessus pour les afficher</i></div>
                    <div id="vosfrais">
						<table class="table table-striped">
                        	<tr>
                            	<th>#</th>
                                <th>Date</th>
                                <th>Montant total</th>
                                <th>Modifier</th>
                                <th>Validée</th>
                            </tr>
							<?php
								$i = 1;
								$q = $db->query(
								"
								SELECT f.total, f.fid, date, v.etat
								FROM frais_forfait AS f
								LEFT JOIN valides AS v ON f.fid = v.fid
								WHERE f.uid = '{$_SESSION['uid']}
								ORDER BY date DESC'
								");
								if ($db->num_rows($q))
								{
									while ($row = $db->fetch_array($q))
									{
										$total = $row['total'];
										$date = date('d/m/Y H:i', $row['date']);
										$link = "<button class=\"btn btn-link clicktoedit\" data-what=\"{$row['fid']}\">Modifier cette fiche</button>";	
										$fid = $i++;
										$icon = $row['etat'] == 1 ? 'icon-ok' : 'icon-remove';
										
										echo "
										<tr>
											<td>{$fid}</td>
											<td>{$date}</td>
											<td>{$total}€</td>
											<td>{$link}</td>
											<td><i class=\"{$icon}\"></i></td>
										</tr>
										";
									}	
								}else{
									echo '<tr><td colspan="5" style="text-align: center;">Vous n\'avez pas de fiches de frais</td></tr>';	
								}
								
							?>
                        </table>
                    </div>
                    <div id="editresult">
                    
                    </div>
                </div>
                <?php endif; ?>
                <?php if (strtolower($_SESSION['type']) == 'comptable'): ?>
                <div class="tab-pane active in" id="stats">
                	<legend>Que puis-je faire ?</legend>
                	<p>Bonjour <?php echo $username; ?>, vous êtes connecté(e) en tant que <?php echo $type; ?> ce qui vous permet de valider (ou non) les fiches de frais déposées par les utilisateurs (visiteurs).</p>
                	<legend>Combien de fiches de frais y a-t-il ?</legend>
                    <?php 
						$q = $db->query(
						"
							SELECT SUM(total) AS ttotal, SUM(CASE WHEN etat = 1 THEN total ELSE 0 END) AS ttotalpaye, COUNT(f.fid) AS totalfiches, COUNT(IF(etat=1, 0, null)) AS totalvalidees
							FROM frais_forfait AS f
							LEFT JOIN valides AS v ON f.fid = v.fid
						");
						
						if ($db->num_rows($q))
						{
							$row = $db->fetch_array($q);
							$total = intval($row['ttotal']);
							$totalp = intval($row['ttotalpaye']);
							$totalfiches = intval($row['totalfiches']);
							$totalvalidees = intval($row['totalvalidees']);
							$totalnonvalidees = $totalfiches - $totalvalidees;
						
					?>
                    <p>Il y a actuellement <b><?php echo $totalfiches ?></b> fiche(s) ajoutée(s) par nos utilisateurs pour un montant total de <b><?php echo $total ?>€</b>.</p><p> Sur ces <b><?php echo $totalfiches ?></b> fiche(s), <b><?php echo $totalvalidees ?></b> sont validée(s), réprésentant un montant de <b><?php echo $totalp ?>€</b>.</p>
                	<legend>Et les utilisateurs, combien sont-ils ?</legend>
                    <?php
						}else{	
					?>
                    <p>Vous ne devriez pas avoir trop de travail pour l'instant: il n'y a aucune fiches de frais actuellement.</p>
                    <?php
						}
						$q = $db->query(
						"
							SELECT COUNT(uid) AS totalusers, COUNT(IF(type=1, 0, null)) AS visiteurs
							FROM users
						");
						if ($db->num_rows($q))
						{
							$row = $db->fetch_array($q);
							$totalu = intval($row['totalusers']);
							$visiteurs = intval($row['visiteurs']);
							$comptables = $totalu - $visiteurs;
					?>
                    <p>Nous comptons un total de <?php echo $totalu; ?> utilisateur(s) dont <?php echo $visiteurs; ?> visiteur(s) et <?php echo $comptables; ?> comptable(s).</p>
                    <?php
						}else{
					?>
                    <p>Il n'y a aucun utilisateur. Et c'est très étrange que vous voyez ce message puisqu'il est destiné aux comptables enregistrés ...</p>
                    <?php	
						}
					?>
                </div>
                <div class="tab-pane" id="valider">
                	<legend>Valider les fiches de frais</legend>
                    <form id="tab">
						<table class="table tablestrip">
                        	<tr>
                            	<th>#</th>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Montant total</th>
                                <th>Afficher</th>
                                <th>Validée</th>
                            </tr>
							<?php
								$i = 1;
								//$q = $db->select('frais_forfait', 'total, fid, date', '', array('order' => 'date DESC'));
								$q = $db->query(
								"
								SELECT *, f.fid AS fid
								FROM frais_forfait AS f
								LEFT JOIN frais_hf AS hf ON hf.fid = f.fid
								LEFT JOIN valides AS v ON v.fid = f.fid
								INNER JOIN users AS u ON u.uid = f.uid
								ORDER BY etat ASC, date DESC
								"
								);
								$ifid = 1;
								if ($db->num_rows($q))
								{
									while ($row = $db->fetch_array($q))
									{
										$total = $row['total'];
										$date = date('d/m/Y H:i', $row['date']);
										$link = "<button class=\"btn btn-link clicktosee\" data-what=\"{$row['fid']}\">Afficher cette fiche</button>";	
										$fid = $ifid++;
										$checked = $row['etat'] == 1 ? "checked" : "";
										$check = '<div style="margin-left: 10px; vertical-align: middle; display: inline;"><div class="switch switch-mini switchvalide" data-on-label="Oui" data-off-label="Non" data-fid="'.$row['fid'].'"><input type="checkbox" '.$checked.'></div></div>';
										$user = $row['username'];
										$content = null;
										
										// On a du HF ici ?
										if (!empty($row['data']))
										{
											$hfs = unserialize($row['data']);
											$i = 0;
											foreach($hfs as $hf)
											{
												$i++;
												$frais_hf[$i] = $hf;
											}
											$ih = 1;
											if(count($frais_hf))
											{
												foreach($frais_hf as $hf)
												{
													$datefix = date('d/m/Y', $hf['date']);
													$content .= "  <div class=\"control-group\">    <label class=\"control-label\">Hors-forfait #{$ih}</label>    <div class=\"controls\">      <div class=\"input-prepend\"> <span class=\"add-on\"><i class=\"icon-calendar\"></i></span>        <input disabled type=\"text\" autocomplete=\"off\" class=\"input-medium\" id=\"m_hfdate_{$i}\" name=\"m_hfdate_{$i}\" placeholder=\"Date\" value=\"{$datefix}\" style=\"text-align: center;\"></div> <input disabled type=\"text\" autocomplete=\"off\" class=\"input-xlarge\" id=\"m_hflibelle_{$i}\" name=\"m_hflibelle_{$i}\" placeholder=\"Libellé\" value=\"{$hf['libelle']}\" style=\"text-align: center;\">      <input disabled type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_hfqte_{$i}\" name=\"m_hfqte_{$i}\" placeholder=\"Quantité\" value=\"{$hf['qte']}\" style=\"text-align: center;\">    </div>  </div>";
													$ih++;
												}	
											}
										}
									
										
										if (!$content) $content = '<div style="margin-top: -10px; margin-bottom: 10px;"><i>Il n\'y a pas de frais hors-forfaits liés à cette fiche</i></div>';
										$hf = "<legend>Frais hors-forfait</legend>{$content}";
										
										echo "
										<tr class=\"tbody\">
											<td>{$fid}</td>
											<td>{$date}</td>
											<td>{$user}</td>
											<td>{$total}€</td>
											<td>{$link}</td>
											<td>{$check}</td>
										</tr>
										";
										
										echo "
										<tr class=\"tbody hidethat\">
											<td colspan=\"6\" style=\"border-top: 0\" class=\"nocenter\">
												<div class=\"well\">";
												
										?>
                                        <legend>Détails de la fiche de frais</legend>
                                        <p>Cette fiche a été ajoutée par <b><?php echo $row['username']; ?></b> le <b><?php echo datefr($row['date']); ?></b> à <b><?php echo date('H:i', $row['date']); ?></b>
                                        <?php if ($row['etat'] == 1): ?>
                                         et elle a été validée.</p>
                                        <?php else:?>
                                         et elle  n'a pas encore été validée.</p>
                                        <?php endif; ?>
                                        <legend>Période d'engagement</legend>
                                        <form id="formajouter" class="form-horizontal" action="">
                                          <div class="control-group">
                                            <label class="control-label">Période d'engagement</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-small" id="mois" name="mois" placeholder="Mois (2 chiffres)" style="text-align: center;" value="<?php echo $row['mois'] ?>">
                                              <input disabled type="text" autocomplete="off" class="input-small" id="annee" name="annee" placeholder="Année (4 chiffres)" style="text-align: center;" value="<?php echo $row['annee'] ?>">
                                            </div>
                                          </div>
                                          <legend>Frais au forfait</legend>
                                          <div class="control-group">
                                            <label class="control-label">Repas</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-mini" id="repas" name="repas" placeholder="Repas" style="text-align: center;" value="<?php echo $row['repas'] ?>"> <div style="display: inline-block; line-height: 20px; margin-left: 10px;">x <?php echo intval($row['crepas']); ?></div>
                                            </div>
                                          </div>
                                          <div class="control-group">
                                            <label class="control-label">Nuitées</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-mini" id="nuitees" name="nuitees" placeholder="Nuitées" style="text-align: center;" value="<?php echo $row['nuitees'] ?>"> <div style="display: inline-block; line-height: 20px; margin-left: 10px;">x <?php echo intval($row['cnuitees']); ?></div>
                                            </div>
                                          </div>
                                          <div class="control-group">
                                            <label class="control-label">Étapes</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-mini" id="etapes" name="etapes" placeholder="Étapes" style="text-align: center;" value="<?php echo $row['etapes'] ?>">
                                            </div>
                                          </div>
                                          <div class="control-group">
                                            <label class="control-label">Kilomètres</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-mini" id="km" name="km" placeholder="Km" style="text-align: center;" value="<?php echo $row['km'] ?>">
                                            </div>
                                          </div>
                                          <?php echo $hf; ?>
                                          <legend>Justificatifs</legend>
                                          <div class="control-group">
                                            <label class="control-label">Nombre de pièces</label>
                                            <div class="controls">
                                              <input disabled type="text" autocomplete="off" class="input-medium" id="nbpieces" name="nbpieces" placeholder="Nmbre" style="text-align: center;" value="<?php echo $row['nbpieces'] ?>">
                                            </div>
                                          </div>
                                          <div class="control-group">
                                            <label class="control-label">Montant total</label>
                                            <div class="controls">
                                              <div class="input-append">
                                                <input disabled type="text" autocomplete="off" class="input-medium" id="total" name="total" placeholder="Total" style="text-align: center;" value="<?php echo $row['total'] ?>">
                                                <span class="add-on">€</span> </div>
                                            </div>
                                          </div>
                                        </form>

                                        
                                        <?php
													
										echo		"</div>
											</td>
										</tr>
										";
	
									}	
								}else{
									echo '<tr><td colspan="6" style="text-align: center;">Il n\'y a aucune fiche de frais pour le moment</td></tr>';	
								}
								
							?>
                        </table>                   
                    </form>
                </div>
                <?php endif; ?>
        	</div>
		</div>
    </div>
    
    <div style="clear: both; margin: 50px 0"></div>
    
<div id="loginfootbar">
	<span class="leftmsg">Heure du serveur: <?php echo date('H:i', time());?></span>
    <span class="rightmsg">Par Benjamin Coriou pour SwisspharmaCorp.</span>
</div>
    
</body>
</html>