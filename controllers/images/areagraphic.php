<?php

	/**
	 *	Script responsable du calcul des datas
	 *	en fonction des paramètres TEAM ID; PERIODE
	 *
	 *	@brief	Ce script renvoi une image contenant le graphique
	 *
	 *	@author Artiom FEDOROV
	 *	
	 *	@example	localhost/niko-niko/niko-niko/index.php?controller=images&action=areagraphic&id=1&periode=now
	 *
	 */

	
	$team = request::get("id");
	$command = request::get("periode");

	$preventTPL = 0;

	if ($team == "") {
    	$team = DEFAULT_TEAM; // si pas de param alors equipe id=1 par defaut
	}

	// Semaine courante
	if ($command == "now") {
		$lastMonday = date("Y-m-d", strtotime("last Monday") - (24*60*60) );

	} elseif (is_numeric($command)) {
		$lastMonday = date("Y-m-d", strtotime("last Monday") - ((24*60*60*7) * $command) - (24*60*60));
	} else {
		die();	
	}

	// Configs
	$datefrom = $lastMonday;
	$dateto = "";
	$teams = users::getTeams();	
	$teamName = $teams[$team];
	
	// On récupére l'ensemble sur une période données
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
			// Préparation des datas
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

	// Création de l'objet Graph
	$graph = new AreaGraph();
	
	// Configuration de la taille
	$graph->valMaxY = $maxSize;
	$graph->init();

	// On change de label en fonction de ce que l'on affiche	
	if ($command == "now") {
		$graph->setTitle( $teamName . " Semaine courante");
	} else {
		$graph->setTitle( $teamName . " " . $lastMonday);
	}
	
	// Draw data
	$graph->drawData($data);
	$image = $graph->getImage();

