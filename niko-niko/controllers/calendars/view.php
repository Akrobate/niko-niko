<?php

	
	$team = request::get('id');
	$periode = request::get('periode');

	
	$datefrom = "";
	$dateto = "";
	// Mois courant
	if ($periode == "now" || $periode == "" || $periode == 0) {
		$month_str = date("F", time());
		$fisrtofmonth = date("Y-m-d", strtotime("1 $month_str"));
		$datefrom = $fisrtofmonth;

		$periode="now";
		$previousperiode = 1;
		$nextperiode = "now";
			
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

	$calendar = new donatj\SimpleCalendar("$month_str");
	$calendar->setStartOfWeek('Monday');
	$periode_indicator = $month_str;
	
	 
	
	
	if ($team == "") {
    	$team = 1; // si pas de param alors equipe id=1 par defaut
	}
  
  $datamode = request::get('datamode');
	
	
	
	$teams = users::getTeams();
	
	$teamName = $teams[$team];
	
	$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);

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
	
