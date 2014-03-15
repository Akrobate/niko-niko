<?php

	$calendar = new donatj\SimpleCalendar();
	
	$team = request::get('id');
	$datefrom = "";
	$dateto = "";
	
	$teams = users::getTeams();
	
	$teamName = $teams[$team];
	
	$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);
	
	foreach($data as $date=>$smiles) {
		$ht = "";
		foreach($smiles as $smile) {
			$ht .= MySmiley::getHtmlSmile($smile['smileycode'], false);
		
		}
		$calendar->addDailyHtml($ht ,  $date, $date  );
	}
	
	
