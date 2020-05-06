<?php

/**
 *	Classe s'occupant de verifier l'authentification des users
 *
 *	@detail cette classe gere en partie les sessions
 *	@author Artiom FEDOROV
 *
 */

class Auth {

	
	/**
	 *	Methode qui permet de verifier si le user est authentifié
	 *	@brief	Compare la clefs au mail
	 *	@detail	Regenere une clef avec le mail puis compare au akeu
	 *	@param	user	nom de l'utilisateur (mail)
	 *	@param	akey	Clef a verifier
	 *	@return	bool	True user autentifié / false sinon
	 *
	 */

	public static function checkAuthUser($user, $akey) {
		$obtainedKey = self::getAuthKey($user);
		if ($obtainedKey == $akey) {
			return true;
		}
		return false;
	}


	/**
	 *	Methode qui permet ge generer une clef
	 *	@brief	Genere une clef depuis l'email
	 *	@param	user	nom de l'utilisateur (mail)
	 *	@return	string	Renvoi la string auth key generée
	 *
	 */

	public static function getAuthKey($user) {
		$akey = md5($user . "#" . md5(KEY));
		return $akey;
	}


	/**
	 *	Methode qui tente de connecter l'utilisateur
	 *	@brief	Tente de connecter le user et met en session ce dernier
	 *	@detail	ouvre une session utilisateur
	 *	@param	user	nom de l'utilisateur (mail)
	 *	@param	akey	Clef a verifier
	 *	@return void
	 *
	 */

	public static function tryToAuth($user, $akey) {

		if ($user != "") {
			if (!self::userSessionIsSet()) {
				if (!self::checkAuthUser($user, $akey)) {
					$user = 'anonyme';
				}
				self::sessionOpen($user);
			} else {
				if (self::getUser() == 'anonyme' && $user != "") {
					if (!self::checkAuthUser($user, $akey)) {
						$user = 'anonyme';
					}
				}
				self::sessionOpen($user);
			}
		} else {
			if (!self::userSessionIsSet()) {
				$user = 'anonyme';
				self::sessionOpen($user);
			}
		}
	}


	/**
	 *	Methodes travaillant avec les sessions
	 *
	 * ---------------------------------------
	 *	
	 *	Methode qui charge le user en session
	 *
	 *	@param	prend en parametre le nom de lutilisateur (mail)
	 *	@brief	Charge l'utilisateur dans la session
	 *  @return	void
	 *
	 */

	public static function sessionOpen($user) {
		$_SESSION['user'] = $user;
	}
	
	
	/**
	 *	Methode qui detruit la sessions
	 *	@brief	Annule les element de session de ce composant
	 *	@return		void
	 *
	 */
	 
	public static function sessionClose() {
		$_SESSION['user'] = null;
	}


	/**
	 *	Methode qui test si la sessions existe
	 *	@brief	Verifie si une session est ouverte
	 *	@return		Bool 	True si oui / false sinon
	 *
	 */
	 
	public static function userSessionIsSet() {
		if (isset($_SESSION['user']) && ($_SESSION['user'] != null)) {
			return true;
		} else {
			return false;
		}
	}
	

	/**
	 *	Methode getteur de l'utilisateur
	 *	@detail	Le user est chargé depuis la session
	 *	@return		string 	Renvoi le nom de l'utilisateur (mail)
	 *
	 */
	 
	public static function getUser() {
		return $_SESSION['user'];
	}

}
