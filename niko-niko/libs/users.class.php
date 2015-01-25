<?php

/**
 *	Classe Users
 *
 *	@brief  chargée de gerer les utilisateurs et les votes
 *	@author Artiom FEDOROV
 *
 */

class users extends sql {

	private static $config = null;
	private static $configAccess = null;
	
	
	/**
	 *	Methode qui verifie si un utilisateur a le droit de participation
	 *	
	 *	@return bool	True si utilisateur peut ajouter, false sinon
	 *
	 */
	
	
	public static function checkUserCanAdd($user, $date) {
	
		$user = sql::escapeString($user);
		$usercode = self::encodeUserName($user);
		$query = "SELECT * FROM days WHERE created='".$date."' AND votedlist LIKE '%".$usercode."%' ";
		
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = parent::allFetchArray();
		if ($nbr > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
	/**
	 *	Methode ajoute le vote d'un utilisateur
	 *	
	 *	@brief	Ajoute un vote d'utilisateur
	 *	@param	user	string	Nom de l'utilisateur (ici mail)
	 *	@param	date	string	Date format sql du jour
	 *	@return void
	 *
	 */
	
	public static function addVoteDay($user, $date) {
	
		$user = sql::escapeString($user);
		$usercode = self::encodeUserName($user);
		$query = "SELECT * FROM days WHERE created='".$date."'";
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = parent::allFetchArray();
		if ($nbr > 0) {
			$query = "UPDATE days SET votedlist = CONCAT(votedlist, ',','".$usercode."') WHERE created = '".$date."'";
			$result = parent::query($query);
		} else {
			$query = "INSERT INTO days (created, votedlist) VALUES ('".$date."','".$usercode."')";
			$result = parent::query($query);
		}
		
	}
	
	
	/**
	 *	Methode ajoute le vote d'un utilisateur
	 *	Methode assurant l'anonymat en melangeant a chaque ajout la votelist
	 *
	 *	@brief	Ajoute un vote d'utilisateur
	 *	@param	user	string	Nom de l'utilisateur (ici mail)
	 *	@param	date	string	Date format sql du jour
	 *	@return void
	 *
	 */
	public static function addVoteDayAndShuffle($user, $date) {
	
		$user = sql::escapeString($user);
		$usercode = self::encodeUserName($user);
		$query = "SELECT * FROM days WHERE created='".$date."'";
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = parent::allFetchArray();
		
		if ($nbr > 0) {
			// Mélange		
			$allcodes = $data[0]['votedlist'];
			$allcodesArr = explode(",",$allcodes);
			$allcodesArr[] = $usercode;
			shuffle($allcodesArr);
			$allcodes = implode("," , $allcodesArr);
			$query = "UPDATE days SET votedlist = '".$allcodes."' WHERE created = '".$date."'";
			$result = parent::query($query);
		
		} else {

			$query = "INSERT INTO days (created, votedlist) VALUES ('".$date."','".$usercode."')";
			$result = parent::query($query);
		}
		
	}
	
	
	/**
	 *	Renvoi la liste des id des equipes aux quelles appartient
	 *	un utilisateur passé en parametre
	 *	@param	user	string	Nom de l'utilisateur (mail)
	 *	@return Array	IDs des equipes auxquelles appartient le user
	 *
	 */
	
	public static function getTeamIds($user) {
	
		$teamconf = self::getConfig();
		$result = array();
		
		foreach($teamconf as $key=>$value) {
			if (in_array($user, $value['member'])) {
				$result[] = $value['teamid'];
			}
		}
		return $result;
	}
	
	
	/**
	 *	Methode qui charge le fichier de conf de l'équipe
	 *	@brief	Charge le fichier de config dans le singleton
	 *
	 */
	
	private static function getConfig() {
		if (self::$config === null) {
			$teamconf = parse_ini_file (PATH_CONFIGS ."team.config.ini", true);
			self::$config = $teamconf;
		} 
		return self::$config;
	}
	
	
	/**
	 *	Methode renvoi toutes les equipes
	 *	@brief	Renvoi un tableau contenant tous les parametres des equipes
	 *	@return Array Contenant les parametres des equipes
	 *
	 */
	 
	public static function getTeams() {
		$teamconf = self::getConfig();
		$result = array();
		foreach($teamconf as $key => $value) {
			$result[ $value['teamid'] ] = $value['teamname'];
		}
		return $result;
	}
	
	
	/**
	 *	Methode permettant d'anonymiser le username
	 *	@brief	Anonymise le mail pour stockage en base
	 *	@return String	Du nom codé (ici md5)
	 *
	 */
	 
	public static function encodeUserName($user) {
		$md5 = md5($user);
		return $md5;
	}
	
	
	/**
	 *	Methode permettant de renvoyer l'ensemble des utilisateurs
	 *	@brief	Renvoi tous les users
	 *	@return Array des utilisateurs
	 *
	 */
	 
	public static function allUsers() {
		$teamconf = self::getConfig();
		$outuser = array();
		foreach($teamconf as $teams) {
		
			foreach($teams['member'] as $user) {
				$outuser[] = $user;
			}
		}
		$outuser = array_flip($outuser);
		$outuser = array_flip($outuser);
		return $outuser;
	}
	
	
	/**
	 *	Methode permettant de verifier si un user est dans la confs
	 *	@brief	verifier si un user est dans la confs
	 *	@return Bool True si dans la conf / false sinon
	 *
	 */
	
	public static function userIsInConf($user) {
		$outuser = self::allUsers();
		return in_array($user, $outuser);
	}
	
	
	/**
	 *	Methode qui charge le fichier de conf Access de l'appli
	 *	@brief	Charge le fichier de config Access dans le singleton
	 *
	 */
	 
	public static function getConfigAccess() {
		if (self::$configAccess === null) {
			self::$configAccess = parse_ini_file ("config/access.config.ini",TRUE);
		} 
		return self::$configAccess;
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
