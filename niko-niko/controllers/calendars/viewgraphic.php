<?php

	
	$team = request::get('id');
	$datamode = request::get('datamode');
	
	$datefrom = "";
	$dateto = "";
	
	$teams = users::getTeams();
	
	$teamName = $teams[$team];
	
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
	
	// a reprendre, pas clean
	$data = $result;
