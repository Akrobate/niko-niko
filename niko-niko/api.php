<?

	define ("PATH_CURRENT", "./" );
	define ("PATH_CONFIGS", PATH_CURRENT. "config/");
	define ("PATH_LIBS", PATH_CURRENT . "libs/" );
	define ("LIBS_PATH", PATH_CURRENT . "libs/" );
	define ("PATH_SCRIPTS", PATH_CURRENT . "controllers/" );
	define ("PATH_TEMPLATES", PATH_CURRENT."templates/");
	define ("PATH_FONTS", PATH_CURRENT."public/fonts/");
	
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
	include(PATH_LIBS . "phpmailer/class.phpmailer.php");
	include(PATH_LIBS . "users.class.php");
	include(PATH_LIBS . "auth.class.php");	
	include(PATH_LIBS . "acl.class.php");	
	include(PATH_LIBS . "areagraph.class.php");
	
