<?php
	/**
	 *	Script Ajax permettant de relever les boites et faire participer les gens
	 *
	 *	@brief 	Releve la boite et ajoute les votes a la bases
	 *	@author	Artiom FEDOROV
	 *
	 */
	
	$response['msg'] = 'ERROR';

	// On releve l'ensemble des emails
	$mailbox = new MyMail();
	// Au passe les mails relevés sont supprimés de la boite
	$msgs = $mailbox->getAllAndRemove(true);
	
	// Traitement message par message
	foreach($msgs as $m) {
	
		$sujet = $m['subject'];
		$user = $m['from'];
		$date = date("Y-m-d", strtotime($m['date']));

		// Création du mail de reponse
		$response['from'] =  $user;
		$response['date'] = $date;
		$response['maildate'] = $m['date'];	
		$response['subject'] = $sujet;
		
		// On cherche le smiley dans le sujet
		$response['detection']= MySmiley::detect($sujet);

		// On verifie que l'utilisateuer a le droit de participer
		if (users::userIsInConf($user)) {
		
			// On tente de detecter le smiley dans le sujet du mails
			if (MySmiley::detect($sujet)) {
				
				// On verifie que l'utilisateur n'a pas déja voté
				if(users::checkUserCanAdd($user, $date)) {	

					// Fabrication de l'item vote compatible ORM
					$item = array();
					$item['smiles']['smileycode'] = MySmiley::detect($sujet);
					$item['smiles']['created'] = $date;
					$item['smiles']['inteams'] = users::getTeamIds($user);
					$response['item']=$item;
					
					// On ajoute l'element
					OrmSmiley::addSmile($item);
					
					//OrmSmiley::addVoteDay($user, $date);
					
					// On ajout le vote en melangeant l'ordre des utilisateurs pour plus d'anonymat
					OrmSmiley::addVoteDayAndShuffle($user, $date);

					// Fabrication de l'email - On previent l'utilisateur	
					$d['to'] = $user;
					$d['smiley'] = ":-)";
					$d['subject'] = "Merci pour la participation! Niko-Niko";
					$tplname = "voteprisencompte";

					// Envoi du mail vote pris en compte
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);
				
				} else {
				
					// So l'utilisateur ne peut pas participer alors mail l'en informant
					$response['status'] = "user cant add";
				
					// Construction du mail
					$d['to'] = $user;
					$d['subject'] = "Niko-Niko";
					$tplname = "nomorevote";
					
					// Envoi
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);			
				}
			} else { 
			
				// SI smiley non detecté
				// On previent l'utilisateur
				$response['status'] = "No Smiley in mail";
			
				// Création du message Niko Niko Non reconu
				$d['to'] = $user;
				$d['subject'] = "Pas de smiley reconnu - Niko-Niko";
				$tplname = "nosmiley";
				
				// Envoi du message
				$mailbox = new MyMail();
				$msgs = $mailbox->SendTemplatedMail($tplname, $d);	
			}
		} else { 
		
			// Si l'utilisateur ne peut participer au NikoNiko
			
			// Création du message				
			$response['status'] = "User Not in Niko niko";
			$d['to'] = $user;
			$d['subject'] = "Vous ne faites partie d'aucune equipe - Niko-Niko";
			$tplname = "noniko";
			
			// Envoi du message
			$mailbox = new MyMail();
			$msgs = $mailbox->SendTemplatedMail($tplname, $d);	
		}
	}

	// Si aucun message alors idle message au front
	if ( count($msgs) > 0) {
		$response['msg'] = 'OK';
		$response['new'] = count($msgs);
	} else {
		$response['msg'] = 'IDLE';
	}
	
	// Réponse json car script ajax
	echo(json_encode($response));

