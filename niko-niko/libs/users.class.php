<?php

class users {

		
	public static function getTeamIds($user) {
	
		$result = array();
		$teamconf = parse_ini_file ("config/team.config.ini",TRUE);
		$teamconf;

		foreach($teamconf as $key=>$value) {
			if (in_array($user, $value['member'])) {
				$result[] = $value['teamid'];
			}
		
		}
		return $result;
	}
	
	
}


?>
