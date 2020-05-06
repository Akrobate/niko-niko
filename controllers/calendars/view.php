<?php

	/**
	 *	Script responsable du calcul des datas
	 *	en fonction des paramètres TEAM ID; PERIODE
	 *
	 *	@author Artiom FEDOROV
	 *	
	 */


	// Récupération des paramètres	
	$team = request::getDefault('id', DEFAULT_TEAM);
	$periode = request::get('periode');
	
	// Positionnemment des valeurs par défaut
	$datefrom = "";
	$dateto = "";

	// Si la période est la période courante
	if ($periode == "now" || $periode == "" || $periode == 0) {
		$month_str = date("F", time());
		$fisrtofmonth = date("Y-m-d", strtotime("1 $month_str"));
		$datefrom = $fisrtofmonth;
		$periode="now";
		$previousperiode = 1;
		$nextperiode = "now";
			
	// Si une période a été communiqués
	} elseif (is_numeric($periode)) {
		
		$month_str = date("F", strtotime("-$periode month"));
		$datefrom = date("Y-m-d", strtotime("1 $month_str"));
		
		if ($periode == "1") {
			$previousperiode = $periode + 1;
			$nextperiode = "now";
		} else {
			$previousperiode = $periode + 1;
			$nextperiode = $periode - 1 ;
		
		}
		
	}

	// Déclaration du calendrier
	$calendar = new donatj\SimpleCalendar("$month_str");
	$calendar->setStartOfWeek('Monday');
	$periode_indicator = $month_str;
	
  
  	// On recupere les parametres
	$datamode = request::get('datamode');
	
	// On recupere toutes les equipes
	$teams = users::getTeams();
	
	// On récupère les noms d'équipes
	$teamName = $teams[$team];
	
	// On récupere l'ensemble des smiley sur une périodes
	$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);

	/**
	 *  Définition du mode de calcul : ici mode moyenne
	 *	Mode datamode = average
	 *
	 */
	 
	if ($datamode != 'average') {
		if (count($data)) {
			foreach($data as $date=>$smiles) {
				$ht = "";
				// On parcour l'ensemle des smiley de ce jour
				foreach($smiles as $smile) {
					$ht .= MySmiley::getHtmlSmile($smile['smileycode'], false);
				}
				// Ajout au calendrier
				$calendar->addDailyHtml($ht ,  $date, $date  );
			}
		}
		
	
	/**
	 *  Définition du mode de calcul
	 *	On affiche tout, plus seulement la moyenne
	 *	Mode datamode = all
	 *
	 */	
	} else {

		foreach($data as $date=>$smiles) {
			$ht = "";
			$smilescore = 0;
			foreach($smiles as $smile) {
				$smilescore += $smile['smileycode'];
			}
			
			// Calcul de la moyenne	
			$smileaverage = $smilescore / count($smiles);		
			
			// rendu du smiley 
			$ht = MySmiley::getHtmlSmile(MySmiley::getRoundedScore($smileaverage), false);
			
			// Ajout au calendrier
			$calendar->addDailyHtml($ht ." <span style=\"color: black\"> ". (int)$smileaverage ." </span> ",  $date, $date  );
		}
	}
	
