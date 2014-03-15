<?php

require_once(LIBS_PATH."sql.class.php");

class OrmSmiley extends sql {


	public static function getAllSmileysFromPeriode($teamid, $datefrom, $dateto) {

		$query = "SELECT * FROM smiles WHERE inteams LIKE '%".$teamid."%' ";
		
		//echo($query);
		
		$result = parent::query($query);
		$nbr = parent::nbrRows();
		
		while ($r = parent::FetchArray()) {
			$data[ $r['created'] ][] = $r;
		
		}
		
		return $data;
	
	}




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
	
	
	
	
	
	
	
	
	
	
}
