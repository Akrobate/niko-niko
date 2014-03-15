<?php

class sql {

	private static $connect_handler = null;
	private static $query_result;
	
	
	public static function connect() {
		$connect_handler = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_NAME, $connect_handler);
		self::$connect_handler = $connect_handler;
	}

	public static function query($query) {
		if (self::$connect_handler == null) {
			self::connect();
		}
		
		// real_escape_string a prevoir ici
		self::$query_result = mysql_query($query, self::$connect_handler);
		
	}

	
	public static function allFetchArray() {
		$data = array();
		while ($return = @mysql_fetch_array(self::$query_result)) {
			$data[] = $return;
		}
		return $data;
	}
	
	public static function fetchArray() {
		return mysql_fetch_array(self::$query_result);
	}

	public static function nbrRows() {
		return mysql_num_rows(self::$query_result);
	}

	public static function lastId() {
		return mysql_insert_id() ;
	}


	public static function escapeString($string) {
		if (self::$connect_handler == null) {
			self::connect();
		}
		return mysql_real_escape_string($string);
	}

	public static function escapeArray($arr) {
		
		
		foreach($arr as $key => $val) {
			if (is_string($val)) {
				$arr[$key] = mysql_real_escape_string($val);
			}
		}
			
		return $arr;
			
	}

}



