<?php

	//print_r($_POST);

	//$data = request::allPost();

	$response['msg'] = 'ERROR';

	$mailbox = new MyMail();
	$msgs = $mailbox->getAllAndRemove(true);
	
	foreach($msgs as $m) {
		//print_r($m);
		$sujet = $m['subject'];
		$user = $m['from'];
		$date = date("Y-m-d", strtotime($m['date']));

		$response['from'] =  $user;
		$response['date'] = $date;
		$response['maildate'] = $m['date'];	
		$response['subject'] = $sujet;
		$response['detection']= MySmiley::detect($sujet);


		if (users::userIsInConf($user)) {


			if (MySmiley::detect($sujet)) {
				if(users::checkUserCanAdd($user, $date)) {	
					$item = array();
					$item['smiles']['smileycode'] = MySmiley::detect($sujet);
					$item['smiles']['created'] = $date;
					$item['smiles']['inteams'] = users::getTeamIds($user);
					$response['item']=$item;
					OrmSmiley::addSmile($item);
					users::addVoteDay($user, $date);
				
					// On previent l'utilisateur	
					$d['to'] = $user;
					$d['smiley'] = ":-)";
					$d['subject'] = "Merci pour la participation! Niko-Niko";
					$tplname = "voteprisencompte";
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);
				
				} else {
					$response['status'] = "user cant add";
				
					$d['to'] = $user;
					$d['subject'] = "Niko-Niko";
					$tplname = "nomorevote";
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);
				
				
				}
			} else { // SI smiley non detecté
		
			// On previent l'utilisateur
					$response['status'] = "No Smiley in mail";
				
					$d['to'] = $user;
					$d['subject'] = "Pas de smiley reconnu - Niko-Niko";
					$tplname = "nosmiley";
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);
		
			}
			
		} else { // Si l'utilisateur ne peut participer au NikoNiko
				
					$response['status'] = "User Not in Niko niko";
					$d['to'] = $user;
					$d['subject'] = "Vous ne faites partie d'aucune equipe - Niko-Niko";
					$tplname = "noniko";
					$mailbox = new MyMail();
					$msgs = $mailbox->SendTemplatedMail($tplname, $d);
		
		
		}
		
	}

	if ( count($msgs) > 0) {
		$response['msg'] = 'OK';
		$response['new'] = count($msgs);
	} else {
		$response['msg'] = 'IDLE';
	}
	
	echo(json_encode($response));

?>
