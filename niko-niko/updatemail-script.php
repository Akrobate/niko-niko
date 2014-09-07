<?php

	error_reporting(15);
	require_once("./api.php");
	
	$controller="calendars";
	$action="ajax-check";
	require_once (PATH_SCRIPTS . $controller . "/" . $action . ".php");

	exit();
