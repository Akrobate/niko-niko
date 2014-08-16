<?php

	// Run feeding
	//feedExemples(6, "2014-08-01", "2014-08-23");
	

	function feedExemples($nbr_users = 6, $dateFrom, $dateTo) {
//	 	users::getTeams()		
		while (strtotime($dateFrom) < strtotime($dateTo)) {
			for($i = 0; $i < $nbr_users; $i++) {
				$item = array();
				$item['smiles']['smileycode'] = rand(1,3) * 10;
				$item['smiles']['created'] = $dateFrom;
				$item['smiles']['inteams'] = array(rand(1,2));
				OrmSmiley::addSmile($item);		
			}
			$dateFrom = date("Y-m-d", strtotime($dateFrom) + (24*3600));
		}
	}

	
	
?>
