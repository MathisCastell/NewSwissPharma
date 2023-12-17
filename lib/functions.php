<?php
	require_once("mysqli.lib.php");
	
	$cred = array(	'host' 		=> 'localhost',
					'user' 		=> 'root',
					'password' 	=> '',
					'database'	=> 'swisspharma');
	$db = new mysqli_lib;
	$db->connect($cred);
	
	error_reporting(E_ALL ^ E_NOTICE);
	@session_start();

	function islogged()
{
    if ((isset($_SESSION['type']) && isset($_SESSION['username']) && isset($_SESSION['login']) == true))    
    {
        return true;    
    }
    return false;
}

	
	function logout()
	{
		unset($_SESSION['login']);
	}
	
	function datefr($stamp)
	{
		$mois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		$jours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
		$n = date('n', $stamp);
		$jn = date('N', $stamp);
		$d = date('j', $stamp);
		$y = date('Y', $stamp);
		$mois = $mois[$n -1];	
		$jour = $jours[$jn -1];
		
		return strtolower($jour).' '.$d.' '.strtolower($mois).' '.$y;
	}

?>