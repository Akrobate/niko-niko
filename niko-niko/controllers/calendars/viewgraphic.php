<?php
	
	/**
	 *	Script récuperant les datas pour les graphiques
	 *	en fonction des paramètres team, datamode, ...
	 *
	 *	@author Artiom FEDOROV
	 *	
	 */
	
	// Récupération des parametres
	$team = request::get('id');
	$datamode = request::get('datamode');
	
	// Calcul de la durée il y a un mois
	$monthago = date("Y-m-d", time() - (30 * 24 * 60 * 60));

	$datefrom = $monthago;
	$dateto = "";
	
	// Verification des droits sur le script
	if ( ($team == "") || (!acl::userCanSee('SELECT_TEAM')) ) {
    	$team = DEFAULT_TEAM; // si pas de param alors equipe id=1 par defaut
	}
	
	// On récupere toutes les equipes
	$teams = users::getTeams();	
	$teamName = $teams[$team];
	
	// traitement et mise en page
	if ($datamode == 'average') {
		$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);
		$result = array();
	
		foreach($data as $date=>$smiles) {
			$ht = "";
			$smilescore = 0;
			foreach($smiles as $smile) {
				$smilescore += $smile['smileycode'];
			}
			$smileaverage = $smilescore / count($smiles);	
			$date = date("j-M-y", strtotime($date));
			$result[$date] = $smileaverage;
		}
	
		$data = $result;
	}
