<?

	define ("PATH_CURRENT", "./" );
	define ("PATH_CONFIGS", PATH_CURRENT. "config/");
	define ("PATH_LIBS", PATH_CURRENT . "libs/" );
	define ("LIBS_PATH", PATH_CURRENT . "libs/" );
	define ("PATH_SCRIPTS", PATH_CURRENT . "controllers/" );
	define ("PATH_TEMPLATES", PATH_CURRENT."templates/");



	include(PATH_LIBS . "lib.php");
	include(PATH_LIBS . "calendar.class.php");
	include("libs/smiley.class.php");
	include("libs/users.class.php");
	
	
