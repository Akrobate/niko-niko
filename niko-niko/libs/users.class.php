<?php

class users extends sql {

	private static $config = null;
	private static $configAccess = null;
	
	public static function checkUserCanAdd($user, $date) {
	
		$user = sql::escapeString($user);
		$usercode = self::encodeUserName($user);
		$query = "SELECT * FROM days WHERE created='".$date."' AND votedlist LIKE '%".$usercode."%' ";
		
		//echo($query);
		
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = parent::allFetchArray();
		if ($nbr > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
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
	
	
	private static function getConfig() {
	
		if (self::$config === null) {
			$teamconf = parse_ini_file ("config/team.config.ini",TRUE);
			self::$config = $teamconf;
		} 
		
		return self::$config;
		
	}
	
	
	public static function getTeams() {
		$teamconf = self::getConfig();
		$result = array();
		//print_r($teamconf);
		foreach($teamconf as $key => $value) {
			$result[ $value['teamid'] ] = $value['teamname'];
		}
		return $result;
	}
	
	
	public static function encodeUserName($user) {
		$md5 = md5($user);
		return $md5;
	}
	
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
	
	
	public static function userIsInConf($user) {
		$outuser = self::allUsers();
		return in_array($user, $outuser);
	}
	
	
	public static function getConfigAccess() {
		if (self::$configAccess === null) {
			self::$configAccess = parse_ini_file ("config/access.config.ini",TRUE);
		} 
		return self::$configAccess;
	}
	
	
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


?>
