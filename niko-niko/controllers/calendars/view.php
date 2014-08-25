<?php

	$calendar = new donatj\SimpleCalendar();
	
	$calendar->setStartOfWeek('Monday');
	
	$team = request::get('id');
	if ($team == "") {
    $team = 1; // si pas de param alors equipe id=1 par defaut
  }
  
  $datamode = request::get('datamode');
	
	$datefrom = "";
	$dateto = "";
	
	$teams = users::getTeams();
	
	$teamName = $teams[$team];
	
	$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);

	
	if ($datamode != 'average') {
		
	foreach($data as $date=>$smiles) {
		$ht = "";
		foreach($smiles as $smile) {
			$ht .= MySmiley::getHtmlSmile($smile['smileycode'], false);
		
		}
		$calendar->addDailyHtml($ht ,  $date, $date  );
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
	
