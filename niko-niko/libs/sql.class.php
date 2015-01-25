<?php

/**
 * @brief		Classe sql permettant de gerer toutes les interaction de base de données
 * @details		surcharge de toutes les méthodes d'acces a la base de données
 *				
 * @author		Artiom FEDOROV
 */

class sql {

	private static $connect_handler = null;
	private static $query_result;

	
	/**
	 * @brief		Méthode de connection a la base de données
	 * @details		Se connecte a la base de données selon les constantes
	 *				DB_HOST, DB_USER, DB_PASSWORD
	 *				Le handler est renvoyé et stocké au niveau du singleton
	 * @return	handler		Renvoi le handler de la connection
	 */
	 
	public static function connect() {
		$connect_handler = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_NAME, $connect_handler);
		self::$connect_handler = $connect_handler;
	}


	/**
	 * @brief		Méthode d'execution de requetes
	 * @details		Execute la requette 
	 *				DB_HOST, DB_USER, DB_PASSWORD
	 *				Le pointeur de la requete est renvoyé et stocké au niveau du singleton
	 * @return	query_result	Renvoie le resultat de la requette a fetcher
	 */
	 
	public static function query($query) {
		if (self::$connect_handler == null) {
			self::connect();
		}
		
		// real_escape_string a prevoir ici
		self::$query_result = mysql_query($query, self::$connect_handler);	
	}

	
	/**
	 * @brief		Methode qui renvoie tous les resultats de la requete
	 * @details		Fetch l'ensemble de la requete avec la méthode fetch_array
	 * @return	Array	Renvoi tous les résultats de la requete
	 *
	 */
	 
	public static function allFetchArray() {
		$data = array();
		while ($return = @mysql_fetch_array(self::$query_result)) {
			$data[] = $return;
		}
		return $data;
	}
	
	
	/**
	 * @brief		Methode qui renvoie un resultat de la requete
	 * @details		Fetch de la requete avec la méthode fetch_array
	 * @return	Array	Renvoi le resultat courant de la requete
	 */
	 
	public static function fetchArray() {
		return mysql_fetch_array(self::$query_result);
	}
	
	
	/**
	 * @brief		Methode qui renvoie le nombre de résultats
	 * @details		nombre de resultats de la requete
	 * @return	int	Renvoi nombre de resultats
	 */
	 
	public static function nbrRows() {
		return mysql_num_rows(self::$query_result);
	}
	
	
	/**
	 * @brief		Methode qui renvoie l'id du dernier element inseré
	 * @details		identifiant du dernier enregistrement crée
	 * @return	int	Renvoi l'id
	 */
	 
	public static function lastId() {
		return mysql_insert_id() ;
	}


	/**
	 * @brief		Methode qui echape les chaines de carractere
	 * @details		Pour eviter l'injection sql toutes les données d'UI doivent etre echapés
	 * @param	string	Chaine de carractere a echapper
	 * @return	string	Chaine echappée
	 *
	 */
	 
	public static function escapeString($string) {
		if (self::$connect_handler == null) {
			self::connect();
		}
		return mysql_real_escape_string($string);
	}
	
	
	/**
	 * @brief		Methode qui echape les chaines de carractere d'un Array
	 * @details		Pour eviter l'injection sql toutes les données d'UI doivent etre echapés
	 * @param	Array	Tableau contenant des chaines de carractere a echapper
	 * @return	Array	Tableau contenant les chaines echappées
	 *
	 */
	 
	public static function escapeArray($arr) {	
		foreach($arr as $key => $val) {
			if (is_string($val)) {
				$arr[$key] = mysql_real_escape_string($val);
			}
		}
		return $arr;	
	}
}

