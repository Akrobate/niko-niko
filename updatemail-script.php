<?php

	/**
	 *	Script permettant de verifier les mails et les ajouter a la base
	 *
	 *	@brief effectue un slimple appel au script ajax chargé de la tache
	 *	@author	Artiom FEDOROV
	 *
	 */

	require_once("./api.php");
	
	$controller="calendars";
	$action="ajax-check";

	require_once (PATH_SCRIPTS . $controller . "/" . $action . ".php");
