<?php

	error_reporting(15);
	require_once("./api.php");
	
	//var_dump(users::userCanAccess());

	$users = users::allUsers();
	$tosend = array();
	$date = date("Y-m-d", time());
	foreach($users as $user) {
		if (users::checkUserCanAdd($user, $date)) {
			$tosend[] = $user;
			$d['to'] = $user;
			$d['subject'] = "Vous n'avez pas encore participe - Niko-Niko";
			$mailbox = new MyMail();
			$msgs = $mailbox->SendTemplatedMail("relance", $d);
		}
	}

	print_r ($tosend);

	exit();
