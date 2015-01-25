<?php

	/**
	 *	Script responsable du calcul des datas
	 *	en fonction des paramètres TEAM ID; PERIODE
	 *
	 *	@author Artiom FEDOROV
	 *	
	 */


	// Récupération des paramètres	
	$team = request::get('id');
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
	
	// réglage de la valeur team par défaut
	if ($team == "") {
    	$team = DEFAULT_TEAM; // si pas de param alors equipe id=1 par defaut
	}
  
	$datamode = request::get('datamode');
	$teams = users::getTeams();
	$teamName = $teams[$team];
	$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);

	// Définition du mode de calcul : ici mode moyenne
	if ($datamode != 'average') {
		if (count($data)) {
			foreach($data as $date=>$smiles) {
				$ht = "";
				foreach($smiles as $smile) {
					$ht .= MySmiley::getHtmlSmile($smile['smileycode'], false);
				}
				$calendar->addDailyHtml($ht ,  $date, $date  );
			}
		}
		
	// On affiche tout, plus seulement la moyenne
	} else {
	
		foreach($data as $date=>$smiles) {
			$ht = "";
			$smilescore = 0;
			foreach($smiles as $smile) {
				$smilescore += $smile['smileycode'];
			}
		
			$smileaverage = $smilescore / count($smiles);		
			$ht = MySmiley::getHtmlSmile(MySmiley::getRoundedScore($smileaverage), false);
			$calendar->addDailyHtml($ht ." <span style=\"color: black\"> ". (int)$smileaverage ." </span> ",  $date, $date  );
		}
	}
	
