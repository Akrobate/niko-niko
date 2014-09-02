<?php

	//localhost/niko-niko/niko-niko/index.php?controller=images&action=areagraphic&id=1&periode=now
	$team = request::get("id");
	$command = request::get("periode");

	$preventTPL = 0;

	// Semaine courante
	if ($command == "now") {
		$lastMonday = date("Y-m-d", strtotime("last Monday") - (24*60*60));
	
	} elseif (is_numeric($command)) {
		$lastMonday = date("Y-m-d", strtotime("last Monday") - ((24*60*60*7) * $command) - (24*60*60));
	} else {
		die();	
	}

	$datefrom = $lastMonday;
	$dateto = "";
	$teams = users::getTeams();	
	$teamName = $teams[$team];
	
	$dataBrut = @OrmSmiley::getAllSmileysFromPeriode($team, $datefrom, $dateto);
	
	$data = array();
	// Todo: Filtrer  par la date end
	
	$i = 0;
	$maxSize = 5;
	if (count($dataBrut)){
		foreach($dataBrut as $date =>$smiles) {
			if ($i >= 5) {
				$i=0;
				break;
			}
			$i++;
			$smiledata = array(0=>0,1=>0,2=>0);
			foreach($smiles as $smile) {
				if ($smile['smileycode'] == 10) {
					$smiledata[0]++;
				} elseif ($smile['smileycode'] == 20) {
					$smiledata[1]++;	
				} elseif ($smile['smileycode'] == 30) {
					$smiledata[2]++;
				}
			}
			if (count($smiles) > $maxSize) {
				$maxSize = count($smiles);
			}			
			$data[] = $smiledata;
		}
	}

	$graph = new AreaGraph();
	$graph->valMaxY = $maxSize;
	$graph->init();
	
	if ($command == "now") {
		$graph->setTitle( $teamName . " Semaine courante");
	} else {
		$graph->setTitle( $teamName . " " . $lastMonday);
	}

	$graph->drawData($data);
	$image = $graph->getImage();

