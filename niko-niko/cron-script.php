<?php

	error_reporting(15);
	require_once("./api.php");

	$users = users::allUsers();
	$tosend = array();
	$date = date("Y-m-d", time());
	
	$managers = acl::getUsersFromRole('manager');
	
	
	foreach($users as $user) {
		if (users::checkUserCanAdd($user, $date)) {
			$tosend[] = $user;
			$d['to'] = $user;
			$d['subject'] = "Vous n'avez pas encore participe - Niko-Niko";

			
			if (in_array($user, $managers)) {
				$d['manager'] = true;				
				$d['connection_url'] = url::internal(DEFAULT_CONTROLLER, DEFAULT_ACTION, null, "&".http_build_query(array('user'=>$user, 'auth' => Auth::getAuthKey($user))));
			}
			
			$mailbox = new MyMail();
			$msgs = $mailbox->SendTemplatedMail("relance", $d);
		}
	}

	print_r ($tosend);
