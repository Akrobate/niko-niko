<?php

/**
 * @brief		Classe url permettant de réaliser des traitement sur les urls
 * @details		Conversions d'url, redirections, centralisation des appels aux modules          
 * @author		Artiom FEDOROV
 */


class url {

	static $rewrited = false;
	
	/**
	 * @brief		Methode permettant de definir le mode de redirection
	 * @details		La classe url peut fonctionner de deux manières: Soit en parametrisant les 
 	 *				url ex: ?action=a&controller=b
	 *				Soit en mode a/b/id/other_params/
	 * @param	bool		true = mode avec les / (redirection necessaire), false = mode parametre d'url
	 * @return    self
	 */
	
	static function setRewrite($bool) {
		self::$rewrited = $bool;
		return self;
	}

	/**
	 * @brief		Réalise une redirection header 301
	 * @details		Peut etre appelé depuis le controlleur          
	 * @param	controller		Nom du controlleur verss lequel on redirige
	 * @param	action			Nom de l'action visée
	 * @param	id				id (optionnel)
	 * @return    null
	 */

	static function redirect($controller, $action, $id = null, $other_params = null){
		$querystr = self::internal($controller, $action, $id, $other_params);
		header("Location: index.php" . $querystr);
	}

	/**
	 * @brief		Genere une url
	 * @details		Genere l'url String en fonction des parametres d'entreé
	 * @param	controller		Nom du controlleur verss lequel on redirige
	 * @param	action			Nom de l'action visée
	 * @param	id				id (optionnel)
	 * @param	other_params	parametres optionnels, concatenation a la fin de l'url
	 * @return    null
	 */

	static function internal($controller, $action, $id = null, $other_params = null) {
		if (self::$rewrited) {
			$query = "$controller/$action";
			if ($id) {
				$query .= "/$id";
			}
			if ($other_params) {
				$query .= "?" . $other_params;
			}	
		} else {
			$query = "?controller=$controller&action=$action";
			if ($id) {
				$query .= "&id=$id";
			}
			if ($other_params) {
				$query .= $other_params;
			}
		}
		return $query;
	}

}


/**
 * @brief		Classe str permettant de réaliser des traitement sur les chaines de carracteres
 * @details		Conversions d'url, redirections, centralisation des appels aux modules          
 * @author		Artiom FEDOROV
 */

class str {

	/**
	 * @brief		utf8e encode en UTF8 une chaine de carractere
	 * @details		Surcharge de la methode utf8_encode
	 * @param	str			Chaine a encoder en urf8
	 * @return    String	renvoi la chaine de carracteres encodée en utf8
	 */
	 
	static function utf8e ($str) {	
		return utf8_encode($str);
	}

}
