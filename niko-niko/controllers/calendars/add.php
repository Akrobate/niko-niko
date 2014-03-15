<?php

	$mailbox = new MyMail();

	$ht = "";
	$msgs = $mailbox->getAllAndRemove(true);
	
	foreach($msgs as $m) {
		
		if(MySmiley::str2smiley($m['subject'], false)) {

			$ht .= MySmiley::str2smiley($m['subject'], false) ;
			//$m['message']
		}
		//$m['date'] = $m['date'];
		$m['time'] = strtotime($m['date']);
		$m['ftime'] = date("Y-m-y", $m['time']);                           // 03.10.01
		print_r($m);
		
		
		$sujet = $m['subject'];
		$user = $m['from'];
		$date = $m['ftime'];
		
		
		if (MySmiley::detect($sujet)) {
			if(users::checkUserCanAdd($user, $date)) {	
				$smiley = array();
				$smiley['smileycode'] = MySmiley::detect($sujet);
				$smiley['created'] = $date;
				$smiley['inteams'] = users::getTeamIds($user);
				$item['smiles'] = $smiley;
			
				OrmSmiley::addSmile($item);
				users::addVoteDay($user, $date);
			}
		}
		
			
		
	
	}

?>
