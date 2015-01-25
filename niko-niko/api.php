<?

	/**
	 * @brief		ensemble des inclusions nécessaires a l'application
	 * @author		Artiom FEDOROV
	 *
	 */

	// Definitions des Constantes
	define ("PATH_SEP", '/');
	define ("PATH_CURRENT", "." . PATH_SEP);
	define ("PATH_CONFIGS", PATH_CURRENT. "config" . PATH_SEP);
	define ("PATH_LIBS", PATH_CURRENT . "libs" . PATH_SEP);
	define ("LIBS_PATH", PATH_CURRENT . "libs" . PATH_SEP);
	define ("PATH_SCRIPTS", PATH_CURRENT . "controllers" . PATH_SEP);
	define ("PATH_TEMPLATES", PATH_CURRENT . "templates" . PATH_SEP);
	define ("PATH_FONTS", PATH_CURRENT . "public" . PATH_SEP ."fonts" . PATH_SEP);
	
	include(PATH_CONFIGS . "db.php");
	include(PATH_CONFIGS . "mail.php");
	include(PATH_CONFIGS . "app.defines.php");
	
	include(PATH_LIBS . "mymail.class.php");
	include(PATH_LIBS . "sql.class.php");
	include(PATH_LIBS . "calendar.class.php");
	include(PATH_LIBS . "request.class.php");
	include(PATH_LIBS . "orm.smiley.class.php");
	include(PATH_LIBS . "smiley.class.php");
	include(PATH_LIBS . "helper.class.php");
	include(PATH_LIBS . "phpmailer". PATH_SEP . "class.phpmailer.php");
	include(PATH_LIBS . "users.class.php");
	include(PATH_LIBS . "auth.class.php");	
	include(PATH_LIBS . "acl.class.php");	
	include(PATH_LIBS . "areagraph.class.php");
	
