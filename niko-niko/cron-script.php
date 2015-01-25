<?php

	/**
	 *	Script cron de NikoNiko
	 *
	 *	@brief Relance les utilisateurs
	 *
	 *	@author	Artiom FEDOROV
	 *
	 */
	 
	error_reporting(15);
	require_once("./api.php");

	// On récupere tous les utilisateurs
	$users = users::allUsers();
	$tosend = array();
	$date = date("Y-m-d", time());
	
	// On récupere la liste des manager pour former le lien de connection
	$managers = acl::getUsersFromRole('manager');
	
	// Boucle principale sur les utilisateurs
	foreach($users as $user) {
	
		// Verificaation si l'utilisateur a déja voté
		if (users::checkUserCanAdd($user, $date)) {
			$d = array();
			$tosend[] = $user;
			$d['to'] = $user;
			$d['subject'] = "Vous n'avez pas encore participe - Niko-Niko";

			// Si l'utilisateur est un des managers alors lien de manager en plus
			if (in_array($user, $managers)) {
				$d['manager'] = true;
				$d['connection_params'] = http_build_query(array('user'=>$user, 'auth' => Auth::getAuthKey($user)));
				$d['connection_url'] = url::internal(DEFAULT_CONTROLLER, DEFAULT_ACTION, null, "&".$d['connection_params'] );
			}
			
			// Envoi de l'email
			$mailbox = new MyMail();
			$msgs = $mailbox->SendTemplatedMail("relance", $d);
			echo($user . "\n");
		}
	}

