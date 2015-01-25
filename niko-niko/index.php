<?php

	/**
	 *	Routeur de l'application NikoNiko
	 *
	 *	@brief	ce script sert de routeur a l'ensemble de l'application
	 *	@author	Artiom FEDOROV
	 *
	 */


	session_start();
	error_reporting(15);
	require_once("./api.php");
	
	// Recuperation des parametres principaux de routage
	$controller = request::get("controller");
	$action = request::get("action");
	$akey = request::get("auth");
	$user = request::get("user");	
	
	// Controller par defaut (a migrer dans un fichier de conf)
	if ($controller == "") {
		$controller = "calendars";
	}
	
	// Action par defaut (a migrer dans un fichier de conf)
	if($action == "") {
		$action = "view";
	}
	
	
	$me = Auth::tryToAuth($user, $akey);
	$permissions = acl::loadToSessionUsersACL($me);
	
	// Verification des droits utilisateurs courant
	if (users::userCanAccess()) {

		// Si le l'action du contreuleur inclut le mot clef "ajax" alors le template n'est pas requis
		$action_tab = explode('-', $action);
		if ($action_tab[0] == 'ajax') {
			if(file_exists(PATH_SCRIPTS . $controller . "/" . $action . ".php")) {
				require_once (PATH_SCRIPTS . $controller . "/" . $action . ".php");
			}
			
			
		} elseif ((file_exists(PATH_SCRIPTS . $controller . "/" . $action . ".php") && ($controller == "images")
				&& ((PATH_TEMPLATES . $controller . "/" . $action . ".php")))) {
					require_once (PATH_SCRIPTS . $controller . "/" . $action . ".php");
					require_once (PATH_TEMPLATES . $controller . "/" . $action . ".php");
		} elseif ((file_exists(PATH_SCRIPTS . $controller . "/" . $action . ".php")) 
			&& ((PATH_TEMPLATES . $controller . "/" . $action . ".php"))) {
				require_once (PATH_SCRIPTS . $controller . "/" . $action . ".php");
				ob_start();
					require_once (PATH_TEMPLATES . $controller . "/" . $action . ".php");
					$template_content = ob_get_contents();
				ob_end_clean();
				require_once( PATH_TEMPLATES . "layouts/mainBootstrap3.php");	
		} else {
			echo("ERROR::Template or script missing");
		}
		
	// Si pas authorisé	
	} else {
		require_once( PATH_TEMPLATES . "layouts/connect.php");	
	}
	

	
		
