<?php

	@session_start();
	error_reporting(E_ALL ^ E_NOTICE);
	
	require_once('./lib/functions.php');
	
	if (!islogged() && ($_GET['a'] != 'login' && $_GET['a'] != 'register'))
	{
		die("Tu n'as rien à faire ici petit fouineur !");	
	}
	
	if ($_GET['a'] == 'valider')
	{
		$fid = intval($_POST['fid']);
		$state = $_POST['state'] == "true" ? 1 : 0;
		
		$q = $db->select('valides', 'etat', "fid = '$fid'");
		if ($db->num_rows($q))
		{
			$db->update('valides', array('etat' => $state), "fid = '$fid'");	
		}else{
			$db->insert('valides', array('fid' => $fid, 'etat' => $state));	
		}
		
		echo "FID: $fid State: $state";
		
		$db->close();
	}
	
	if ($_GET['a'] == 'editsave')
	{
		// On s'occupe d'abord des hors-forfaits
		$hf = array();
		foreach($_POST as $k => $v)
		{
			$p = "#(hfdate|hflibelle|hfqte)_([0-9]){1,2}#";
			if (preg_match($p, $k, $a))
			{
				if ($v)
				{
					$hf[$a[2]][$a[1]] = $v;	
				}
				
			}
		}
		
		// Maintenant le reste
		foreach($_POST as $k => $v)
		{
			$$k = intval($v);	
		}
		
		$a = array(
				"repas" 	=> $m_repas,
				"crepas"	=> $mcrepas,
				"nuitees"	=> $m_nuitees,
				"cnuitees" 	=> $mcnuitees,
				"etapes" 	=> $m_etapes,
				"km" 		=> $m_km,
				"nbpieces" 	=> $m_nbpieces,
				"total"		=> $m_total,
				"uid"		=> intval($_SESSION['uid'])
				);	
				
		if (count($hf))
		{
			foreach($hf as $frais)
			{
				$ab[] = array(
						"date" 		=> strtotime(str_replace("/", "-", $frais['hfdate'])),
						"libelle" 	=> $db->filter($frais['hflibelle'], 1),
						"qte" 		=> intval($frais['hfqte'])
						);
			}	
			$ab = serialize($ab);
			$db->update('frais_hf', array('data' => $ab), "uid = '{$_SESSION['uid']}' AND fid = '{$m_fid}'");
		}		
		
		$db->update('frais_forfait', $a, "uid = '{$_SESSION['uid']}' AND fid = '{$m_fid}'");
		
		$q = $db->select('valides', 'fid', "fid = '{$m_fid}'");
		if ($db->num_rows($q))
		{
			$db->update('valides', array('etat' => 0), "fid = '{$m_fid}'");	
		}
		
		if ($db->error())
		{
			echo json_encode(array('valid' => false));	
		}else{
			echo json_encode(array('valid' => true));	
		}
		
		$db->close();
	}
	
	if ($_GET['a'] == 'edit')
	{
		$id = intval($_POST['id']);
		$q = $db->query("
		SELECT DISTINCT * 
		FROM frais_forfait
		LEFT JOIN frais_hf 
		ON frais_hf.fid = frais_forfait.fid
		WHERE frais_forfait.fid = '{$id}' AND frais_forfait.uid = '{$_SESSION['uid']}'
		");
		$frais_forfait = array();
		$frais_hf = array();
		if ($db->num_rows($q))
		{
			$row = $db->fetch_array($q);
			
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
			}
			
			$i = 0;
			foreach($row as $k => $v)
			{
				if ($k != 'data' && !empty($v) && $k != 'hfid') 
				{
					$frais_forfait[$k] = $v;
				}
			}
			
			$r[1] = "<h3>Modifier une fiche de frais <i class=\"icon-remove closeedit\"></i></h3> <form id=\"formmodifier\" class=\"form-horizontal\" action=\"\">  <legend>Frais au forfait</legend>  <div class=\"control-group\">    <label class=\"control-label\">Repas</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_repas\" name=\"m_repas\" placeholder=\"Repas\" value=\"{$frais_forfait['repas']}\" style=\"text-align: center;\"> <div class=\"plusminus\"> <span class=\"badge badge-info minus\"><i class=\"icon-minus\"></i></span> <span class=\"counthere\" style=\"margin: 0 5px;\">".intval($frais_forfait['crepas'])."</span>  <input type=\"hidden\" name=\"mcrepas\" class=\"dathidden\" value=\"".intval($frais_forfait['crepas'])."\" />  <span class=\"badge badge-info plus\"><i class=\"icon-plus\"></i></span> </div>   </div>  </div>  <div class=\"control-group\">    <label class=\"control-label\">Nuitées</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_nuitees\" name=\"m_nuitees\" placeholder=\"Nuitées\" value=\"{$frais_forfait['nuitees']}\" style=\"text-align: center;\"> <div class=\"plusminus\"> <span class=\"badge badge-info minus\"><i class=\"icon-minus\"></i></span> <span class=\"counthere\" style=\"margin: 0 5px;\">".intval($frais_forfait['cnuitees'])."</span>  <input type=\"hidden\" name=\"mcnuitees\" class=\"dathidden\" value=\"".intval($frais_forfait['cnuitees'])."\" />  <span class=\"badge badge-info plus\"><i class=\"icon-plus\"></i></span> </div>   </div>  </div>  <div class=\"control-group\">    <label class=\"control-label\">Étapes</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_etapes\" name=\"m_etapes\" placeholder=\"Étape\" value=\"{$frais_forfait['etapes']}\" style=\"text-align: center;\">    </div>  </div>  <div class=\"control-group\">    <label class=\"control-label\">Kilomètres</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_km\" name=\"m_km\" placeholder=\"Km\" value=\"{$frais_forfait['km']}\" style=\"text-align: center;\">    </div>  </div>";
			$r[3] = "  <legend>Justificatifs</legend>  <div class=\"control-group\">    <label class=\"control-label\">Nombre de pièces</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-medium\" id=\"m_nbpieces\" name=\"m_nbpieces\" placeholder=\"Nmbre\" value=\"{$frais_forfait['nbpieces']}\" style=\"text-align: center;\">    </div>  </div>  <div class=\"control-group\">    <label class=\"control-label\">Montant total</label>    <div class=\"controls\">      <input type=\"text\" autocomplete=\"off\" class=\"input-medium\" id=\"m_total\" name=\"m_total\" placeholder=\"Total\" value=\"{$frais_forfait['total']}\" style=\"text-align: center;\">    </div>  </div>  <div class=\"control-group\">    <div class=\"controls\">  <input type=\"hidden\" id=\"m_fid\" name=\"m_fid\" value=\"{$id}\" />     <button type=\"submit\" class=\"btn btn-success\" >Enregistrer les modifications</button>    </div>  </div></form>";
			
			$i = 1;
			$content = "";
			if(count($frais_hf))
			{
				foreach($frais_hf as $hf)
				{
					$date = date('d-m-Y');
					$datefix = date('d/m/Y', $hf['date']);
					$content .= "  <div class=\"control-group\">    <label class=\"control-label\">Hors-forfait #{$i}</label>    <div class=\"controls\">      <div class=\"input-prepend picker\" data-date=\"{$date}\" data-date-format=\"dd-mm-yyyy\"> <span class=\"add-on\"><i class=\"icon-calendar\"></i></span>        <input type=\"text\" autocomplete=\"off\" class=\"input-medium\" id=\"m_hfdate_{$i}\" name=\"m_hfdate_{$i}\" placeholder=\"Date\" value=\"{$datefix}\" style=\"text-align: center;\">      </div>      <input type=\"text\" autocomplete=\"off\" class=\"input-xlarge\" id=\"m_hflibelle_{$i}\" name=\"m_hflibelle_{$i}\" placeholder=\"Libellé\" value=\"{$hf['libelle']}\" style=\"text-align: center;\">      <input type=\"text\" autocomplete=\"off\" class=\"input-mini\" id=\"m_hfqte_{$i}\" name=\"m_hfqte_{$i}\" placeholder=\"Quantité\" value=\"{$hf['qte']}\" style=\"text-align: center;\">    </div>  </div>";
					$i++;
				}	
			}
			
			if (!$content) $content = '<div style="margin-top: -10px; margin-bottom: 10px;"><i>Il n\'y a pas de frais hors-forfaits liés à cette fiche</i></div>';
			$r[2] = "<legend>Frais hors-forfait</legend>{$content}";
			
			$content = $r[1].$r[2].$r[3];
			echo json_encode(array('valid' => true, 'content' => $content));
			
		}else{
			echo json_encode(array('valid' => false, 'error' => 'La note de frais n\'a pas été trouvée'));	
		}
		
		$db->close();
	}
					
	if ($_GET['a'] == 'ajouter')
	{
		// On s'occupe d'abord des hors-forfaits
		$hf = array();
		foreach($_POST as $k => $v)
		{
			$p = "#(hfdate|hflibelle|hfqte)_([0-9]){1,2}#";
			if (preg_match($p, $k, $a))
			{
				if ($v)
				{
					$hf[$a[2]][$a[1]] = $v;	
				}
				
			}
		}
		
		// Maintenant le reste
		foreach($_POST as $k => $v)
		{
			$$k = intval($v);	
		}
		
		$a = array(
				"mois" 		=> $mois,
				"annee" 	=> $annee,
				"repas" 	=> $repas,
				"crepas"	=> $crepas,
				"nuitees"	=> $nuitees,
				"cnuitees"	=> $cnuitees,
				"etapes" 	=> $etapes,
				"km" 		=> $km,
				"nbpieces" 	=> $nbpieces,
				"total"		=> $total,
				"uid"		=> intval($_SESSION['uid']),
				"date"		=> time()
				);
				
		$db->insert('frais_forfait', $a);
		$insertid = $db->insert_id();
		$a = array();
		
		if (count($hf))
		{
			foreach($hf as $frais)
			{
				$a[] = array(
						"date" 		=> strtotime(str_replace("/", "-", $frais['hfdate'])),
						"libelle" 	=> $db->filter($frais['hflibelle'], 1),
						"qte" 		=> intval($frais['hfqte'])
						);
			}	
			$a = serialize($a);
			$insert = array(
							'uid' 	=> intval($_SESSION['uid']),
							'fid' 	=> $insertid,
							'data' 	=> $a
						);
			$db->insert('frais_hf', $insert);
		}
		
		if ($insertid)
		{
			echo json_encode(array('valid' => true, 'id' => $insertid));	
		}else{
			echo json_encode(array('valid' => false));	
		}
		
		$db->close();
	}

	if ($_GET['a'] == "login")
	{
		@session_start();
		
		foreach($_POST as $key => $value)
		{
			$$key = $value;	
		}
		
		// Mot de passe MD5
		$password = md5($password);
		// Nous n'autorisons que les chiffres, les lettres et les espaces
		$username = preg_replace("#[^A-Za-z0-9À-ÿ]#", "", $username);
		
		// Requête
		$query = $db->select('users', '*', "username = '$username' AND password = '$password'");
		
		if ($db->num_rows($query))
		{
			echo json_encode(array('success' => true));
			$row = $db->fetch_array($query);
			$type = $row['type'];
			$_SESSION['type'] = $type == "1" ? 'visiteur' : 'comptable';
			$_SESSION['username'] = $username;
			$_SESSION['uid'] = intval($row['uid']);
			$_SESSION['login'] = true;
		}else{
			echo json_encode(array('error' => 'bad'));
		}
		
		$db->close();
	}
	
	if ($_GET['a'] == "register")
	{
		$return = array('valid', 'error');
		
		foreach($_POST as $key => $value)
		{
			$$key = $value;	
		}
		
		if (!$nom || !$prenom || !$adresse || !$cp || !$ville || !$email || !$type || !$pseudo || !$pass || !$comfpass)
		{
			$return['valid'] = false;
			$return['error'] = "Tous les champs sont obligatoires";
			echo json_encode($return);
		}else{
			$nom = preg_replace("#[^A-Za-z0-9 ]#", "", $nom);
			$prenom = preg_replace("#[^A-Za-z0-9 ]#", "", $prenom);
			$adresse = preg_replace("#[^A-Za-z0-9 ]#", "", $adresse);
			$cp = preg_replace("#[^0-9]#", "", $cp);
			$ville = preg_replace("#[^A-Za-z0-9 -]#", "", $vile);
			$type = (strtolower($type) == "visiteur") ? 1 : 2;	
			$pseudo = preg_replace("#[^A-Za-z0-9À-ÿ]#", "", $pseudo);
			$emailpattern = "#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$#";
			if (!preg_match($emailpattern, $email) || $pass != $comfpass)
			{
				$return['valid'] = false;
				$return['error'] = "Vos données ne sont pas valides";
				echo json_encode($return);					
			}else{
				$db->query("SET NAMES UTF8");
				$q = $db->select('users', 'username', "username = '$pseudo'");
				if ($db->num_rows($q))
				{
					$return['valid'] = false;
					$return['error'] = "Ce pseudo ($pseudo) est déjà utilisé. Merci d'en choisir un autre";
					echo json_encode($return);					
				}else{
					$pass = md5($pass);
					$array = array(
									"username" 	=> $pseudo,
									"password" 	=> $pass,
									"type" 		=> $type,
									"nom" 		=> $nom,
									"prenom" 	=> $prenom,
									"adresse" 	=> $adresse,
									"cp" 		=> $cp,
									"email" 	=> $email 
								);
					$db->insert('users', $array);
					if ($db->insert_id())
					{
						$return['valid'] = true;
						echo json_encode($return);								
					}else{
						$return['valid'] = false;
						$return['error'] = "Votre compte n'a pas été créé. Merci d'essayer ultérieurement";
						echo json_encode($return);								
					}
				}
			}
		}
	}

?>