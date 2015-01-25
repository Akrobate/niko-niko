<?php

	/**
	 *	Script ajoutant un vote
 	 *
	 *	@author Artiom FEDOROV
	 *	
	 */

	// On releve la boite mail
	$mailbox = new MyMail();
	$msgs = $mailbox->getAllAndRemove(true);
	
	// On parcours message par message
	foreach($msgs as $m) {
	
		$sujet = $m['subject'];
		$user = $m['from'];
		$date = date("Y-m-y", strtotime($m['date']));

		// On detecte le smiley dans le sujet		
		if (MySmiley::detect($sujet)) {

			// Si l'utilisateur peut ajouter
			if(users::checkUserCanAdd($user, $date)) {	

				// On construit l'objet a ajouter
				$item = array();
				$item['smiles']['smileycode'] = MySmiley::detect($sujet);
				$item['smiles']['created'] = $date;
				$item['smiles']['inteams'] = users::getTeamIds($user);
				
				// On ajoute via la couce ORM
				OrmSmiley::addSmile($item);
				
				// On ajoute le vote
				users::addVoteDay($user, $date);
			}
		}
		
	}

