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

		if (MySmiley::detect($sujet)) {
			if(users::checkUserCanAdd($user, $date)) {	
				$item = array();
				$item['smiles']['smileycode'] = MySmiley::detect($sujet);
				$item['smiles']['created'] = $date;
				$item['smiles']['inteams'] = users::getTeamIds($user);
				$response['item']=$item;
				OrmSmiley::addSmile($item);
				users::addVoteDay($user, $date);
			} else {
				$response['status'] = "user cant add";
			}
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
