<?php

/**
 *	Classe s'occupant de restreindre les niveau d'acces en fonction des utilisateurs
 *
 *
 *
 */



class acl {

	private static $config = null;
	private static $configAccess = null;

	public static function getConfigAccess() {
		if (self::$configAccess === null) {
			self::$configAccess = parse_ini_file ("config/access.config.ini",TRUE);
		} 
		return self::$configAccess;
	}


	
	
	
	
	public static function getRolePermissions($role) {
		$config = self::getConfigAccess();
		$res = array();		
		if ( isset ( $config['roles'][$role] ) ) {
			$strroles = $config['roles'][$role];
			$roles = explode("|", $strroles);
			foreach($roles as $r) {
				$res[] = trim($r);
			}
		} 	
		return $res;
	}
	
	
	
	public static function getUserRole($user) {
		$config = self::getConfigAccess();
		if ( isset ( $config['usersroles'][$user] ) ) {
			return $config['usersroles'][$user];
		} else {
			return $config['usersroles']['anonyme'];
		}
	}

	
	/**
	 *	Methode permettant de récuperer tous les utilisateurs
	 *	d'un role
	 *
	 */
	
	
	public static function getUsersFromRole($role) {
	
		$res = array();
		$config = self::getConfigAccess();
		foreach($config['usersroles'] as $u => $r) {
			if ($r == $role) {
				$res[] = $u;
			}
		}
		return $res;
	}


	/**
	 *	Methodes travaillant avec les sessions
	 *
	 *
	 */


	public static function loadToSessionUsersACL($user) {
		$role = self::getUserRole($user);
  	 	$permission = self::getRolePermissions($role);
		self::setSessionPermissions($permission);
		return $permission;
	}


	public static function setSessionPermissions($perm) {
		$_SESSION['ACL']['permission'] = $perm;
	}


	public static function getSessionPermissions() {
		return $_SESSION['ACL']['permission'];
	}
	
	public static function sessionClose() {
		$_SESSION['ACL'] = null;
	}



	public static function userCanSee($what) {
		$permissionList = self::getSessionPermissions();
		if (in_array($what, $permissionList)) {
			return true;
		}
		return false;
	}	
	
}
