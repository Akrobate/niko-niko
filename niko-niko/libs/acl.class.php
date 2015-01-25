<?php


class acl {

	private static $config = null;
	private static $configAccess = null;

	public static function getConfigAccess() {
		if (self::$configAccess === null) {
			self::$configAccess = parse_ini_file ("config/access.config.ini",TRUE);
		} 
		return self::$configAccess;
	}


	
	public static function userCanSee($whattosee, $who = 'anonyme') {
		$config = self::getConfigAccess();
		$role =  self::getUserRole($who);
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


	
}
