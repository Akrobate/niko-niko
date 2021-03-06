<?php

/**
 * @brief		Classe ORM de smileys
 * @details		Permet de manipuler les smiley en bases
 *
 * @author		Artiom FEDOROV
 *
 */
 
class OrmSmiley extends sql {

		
	/**
	 *	Methode permettant de renvoyer les smileys d'une periode
	 *
	 * @brief		Methode permettant de renvoyer les smileys d'une periode
	 * @param	teamiid	int	L'identifiant de l'équipe
	 * @param	datefrom	dateSQL		Date de début (format sql)
	 * @param	dateto	dateSQL		Date de fin (format sql)	 
	 *
	 * @return	array 	Renvoie le tableau contanant les smiley pour l'équipe donnée 
	 *					sur la periode donnée
	 */

	public static function getAllSmileysFromPeriode($teamid, $datefrom="", $dateto="") {

		$query = "SELECT * FROM smiles WHERE inteams LIKE '%".$teamid."%' ";
		if ($datefrom != "") {
			$query .= " AND created >= \"$datefrom\" ";
		}
		if ($dateto != "") {
			$query .= " AND created < \"$dateto\" ";
		}
		$query .= " ORDER BY created ASC ";
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = array();
		while ($r = parent::FetchArray()) {
			$data[ $r['created'] ][] = $r;
		}
		return $data;
	}


	/**
	 *	Methode permettant d'ajouter un smiley en base
	 *
	 * @brief		Ajoute un smiley en base
	 * @param	item	Array	Tableau contenant les parametres pour smiley
 	 *
	 * @return	Renvoi void si ok et false sinon
	 * @todo	Optimiser TRUE/FALSE
	 *
	 */

	public static function addSmile($item) {
		
		if(!isset($item['smiles'])) {
			return false;
		}
		$item['smiles']['inteams'] = implode(',', $item['smiles']['inteams']);
		$str_cols = "";
		$str_values = "";
		
		foreach ($item['smiles'] as $key => $val) {
			$str_cols .= $key . ',';
			$str_values .= '"'.$val . '",';
		}

		$str_cols = substr($str_cols, 0, -1); 
		$str_values = substr($str_values, 0, -1); 
		$query = "INSERT INTO smiles (".$str_cols.") VALUES (".$str_values.");";				
		parent::query($query);
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
		$usercode = users::encodeUserName($user);
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
		$usercode = users::encodeUserName($user);
		$query = "SELECT * FROM days WHERE created='".$date."'";
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		$data = parent::allFetchArray();
		
		// On determine si l'on fait un insert ou un update
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
}
