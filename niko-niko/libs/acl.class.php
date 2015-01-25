<?php

/**
 *	Classe s'occupant de restreindre les niveau d'acces en fonction des utilisateurs
 *	@brief c'est ici que se gerent les roles des utilisateurs
 *	@author Artiom FEDOROV
 *
 */

class acl {

	// Datas Array du fichier de config
	private static $config = null;
	
	// Datas Array du fichier de config Access
	private static $configAccess = null;


	/**
	 *	Methode qui permet de recuperer la config de Access
	 *	@brief	Récupere la config et la stoque
	 *	@detail	La config devient accessible en dans le singleton
	 *			Si la config est déja chargé alors le fichier n'est
	 *			pas ouvert une autre fois
	 */

	public static function getConfigAccess() {
		if (self::$configAccess === null) {
			self::$configAccess = parse_ini_file (PATH_CONFIGS . PATH_SEP . "access.config.ini", true);
		} 
		return self::$configAccess;
	}


	/**
	 *	Methode renvoi l'ensemble des autorisation pour un role
	 *
	 *	@brief	Récupere les autorisation pour un role
	 *	@param role	Prend en parametre le nom du role
	 *	@return		Array	Liste des autorisations
	 *
	 */

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
	
	
	/**
	 *	Methode renvoi le role pour un user
	 *
	 *	@brief	Récupere le role d'un user
	 *	@param user	Nom de lutilisateur (mail)
	 *	@return	role	renvoi le role de l'utilisateur 
	 *
	 */
	
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
	 *	@param	role	prend en parametre un role
	 *	@return renvoi tous les utilisateurs du role
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
	 * ---------------------------------------
	 *	
	 *	Methode qui charge les permissions depuis la session pour un user donné
	 *
	 *	@param	prend en parametre le nom de lutilisateur (mail)
	 *	@brief	Charge et renvoi les permissions utilisateur
	 *  @return	Array permissions 
	 */


	public static function loadToSessionUsersACL($user) {
		$role = self::getUserRole($user);
  	 	$permission = self::getRolePermissions($role);
		self::setSessionPermissions($permission);
		return $permission;
	}


	/**
	 *	Methode setteur de permission
	 *	@brief	Set des permissions en session
	 *	@param	perm	Array tableau des permissions
	 *	@return		Void
	 *
	 */
	 
	public static function setSessionPermissions($perm) {
		$_SESSION['ACL']['permission'] = $perm;
	}


	/**
	 *	Methode getteur de permission
	 *	@brief	Recupere toutes les permissions depuis la session
	 *	@return		Array	Tableau des permissions
	 *
	 */
	 
	public static function getSessionPermissions() {
		return $_SESSION['ACL']['permission'];
	}
	
	
	/**
	 *	Methode qui detruit la sessions
	 *	@brief	Annule les element de session de ce composant
	 *	@return		void
	 *
	 */
	
	public static function sessionClose() {
		$_SESSION['ACL'] = null;
	}

	/**
	 *	Methode qui verifie si le user est authorisé a voir un element
	 *	@brief	Verifie si l'utilisateur a le droit de voir un element
	 *	@param	what	string	Nom de l'element a acceder
	 *	@detail	Verifie en session
	 *	@return		Bool	True si peut acceder / False sinon
	 *
	 */

	public static function userCanSee($what) {
		$permissionList = self::getSessionPermissions();
		if (in_array($what, $permissionList)) {
			return true;
		}
		return false;
	}
	
	
	/**
	 *	Methode qui verifie si le user est authorisé (IP restrict)
	 *	@brief	Verifie si l'utilisateur a le droit d'acceder, restriction par ip
	 *	@return		Bool	True si peut acceder / False sinon
	 *
	 */
	
	public static function userCanAccess() {
		$usr = $_SERVER['REMOTE_ADDR'];
	    $tmp =  self::getConfigAccess();
	    $accesslist = $tmp['access']['allowAdr'];
	    
	    if (in_array('all', $accesslist)) {
		    return true;
	    } elseif (in_array($usr,$accesslist)) {
		    return true;
		}
		return false;
	}
	
}
