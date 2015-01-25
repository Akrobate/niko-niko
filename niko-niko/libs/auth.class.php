<?php

/**
 *	Classe d'authententification des utilisateurs
 *
 *
 *
 */
 
	define('KEY',"MaClefPourEncoder");


class Auth {


	public static function checkAuthUser($user, $akey) {
		
		$obtainedKey = self::getAuthKey($user);
		if ($obtainedKey == $akey) {
			return true;
		}
		return false;
	}




	public static function getAuthKey($user) {
	
		$akey = md5($user . "#" . md5(KEY));
		return $akey;

	}


	

	public static function tryToAuth($user, $akey) {

		if (!self::userSessionIsSet()) {
			if (!self::checkAuthUser($user, $akey)) {
				$user = 'anonyme';
			}
		}
		self::sessionOpen($user);
	}


	/**
	 *	GEstion des sessions
	 *
	 */

	public static function sessionOpen($user) {
		$_SESSION['user'] = $user;
	}
	
	
	public static function sessionClose() {
		$_SESSION['user'] = null;
	}

	public static function userSessionIsSet() {
		if (isset($_SESSION['user']) && ($_SESSION['user'] != null)) {
			return true;
		} else {
			return false;
		}
	}

	public static function getUser() {
		return $_SESSION['user'];
	}

}
