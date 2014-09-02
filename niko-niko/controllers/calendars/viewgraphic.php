<?php
	
	$team = request::get('id');
	$datamode = request::get('datamode');
	
	$monthago = date("Y-m-d", time() - (30 * 24 * 60 * 60));

	$datefrom = $monthago;
	$dateto = "";
	
	$teams = users::getTeams();	
	$teamName = $teams[$team];
	
	if ($datamode == 'average') {
		$data = OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);
		//print_r ($data);

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
