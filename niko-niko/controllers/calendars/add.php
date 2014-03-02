<?php

	$mailbox = new MyMail();
	$msgs = $mailbox->removeOne();
	
	//exit();
	//print_r($msgs = $mailbox->getNew());

	$ht = "";
	$msgs = $mailbox->getNew();
	foreach($msgs as $m) {
		
		//$m['message']
		//	echo($m['subject']);
		//MySmiley::str2smiley($m['subject'], true);
		if(MySmiley::str2smiley($m['subject'], false)) {

			$ht .= MySmiley::str2smiley($m['subject'], false) ;
			
		}
		$m['date'] = $m['date'];
		print_r($m);
		
	
	}


	//$date = "2014-03-01";
	$date = "2014-03-02";
	$user = "adel.lynda@gmail.com";
	//$user = "fedorov.artiom@gmail.com";


	$smiley['smileycode'] = 20;
	$smiley['created'] = $date;
	$smiley['inteams'] = users::getTeamIds($user);
	$item['smiles'] = $smiley;

	if(users::checkUserCanAdd($user, $date)) {	
		
		OrmSmiley::addSmile($item);
		users::addVoteDay($user, $date);
		
	}

?>
