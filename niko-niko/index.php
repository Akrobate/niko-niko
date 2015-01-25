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
	
	// Controller par defaut (a migrer dans un fichier de conf)s
	$controller = request::getDefault("controller", DEFAULT_CONTROLLER);
	// Action par defaut (a migrer dans un fichier de conf)
	$action = request::getDefault("action", DEFAULT_ACTION);
	$akey = request::get("auth");
	$user = request::get("user");	

	// On effectue une tentative de connection	
	Auth::tryToAuth($user, $akey);
	$permissions = acl::loadToSessionUsersACL(Auth::getUser());


	// Verification des droits utilisateurs courant
	if (users::userCanAccess()) {

		/**
		 *	Brique principale de routage
		 *	On verifie si les fichiers existent
		 *	On bypass la veri pour ajax-*
		 *
		 *	Lyaout mode conteneur
		 */

		// Si le l'action du contreuleur inclut le mot clef "ajax" alors le template n'est pas requis
		$action_tab = explode('-', $action);
		
		// Si nous sommes en mode AJAX
		if ($action_tab[0] == 'ajax') {
			if(file_exists(PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php")) {
				require_once (PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php");
			}
			
		// Si Controlleur est images	
		} elseif ((file_exists(PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php") && ($controller == "images")
				&& ((PATH_TEMPLATES . $controller . PATH_SEP . $action . ".php")))) {
					require_once (PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php");
					require_once (PATH_TEMPLATES . $controller . PATH_SEP . $action . ".php");
					
		// Cas Generique pour tous
		} elseif ((file_exists(PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php")) 
			&& ((PATH_TEMPLATES . $controller . PATH_SEP . $action . ".php"))) {
				require_once (PATH_SCRIPTS . $controller . PATH_SEP . $action . ".php");
				ob_start();
					require_once (PATH_TEMPLATES . $controller . PATH_SEP . $action . ".php");
					$template_content = ob_get_contents();
				ob_end_clean();
				require_once( PATH_TEMPLATES . "layouts" . PATH_SEP . "mainBootstrap3.php");	

		// Si Template ou script manquant
		} else {
			echo("ERROR::Template or script missing");
		}

	// Si pas authorisé	
	} else {
		require_once( PATH_TEMPLATES . "layouts" . PATH_SEP . "connect.php");	
	}

