<?php

/**
 *	Classe qui s'occupe de la recupération des paramatetres
 *	@author	Artiom FEDOROV
 */
 
class request {

	/**
	 *	Methode qui recupere le get post 
	 *	@param str	Nom du parametre
	 *	@return 	Null si aucu sinon param GET d'abord puis post
	 *
	 */

	public static function get($str) {
		if (isset($_GET[$str])) {
			return $_GET[$str];
		} elseif (isset($_POST[$str])) {
			return $_POST[$str];
		} else {
			return null;
		}
	}

	/**
	 *	Methode qui recupere le get post 
	 *	@param str	Nom du parametre
	 *	@return 	Null si aucu sinon param GET d'abord puis post
	 *
	 */

	public static function getDefault($str, $default = null) {
		$v = self::get($str);
		if ($v === null) {
			return $default;
		} else {
			return $v;
		}
	}

}

