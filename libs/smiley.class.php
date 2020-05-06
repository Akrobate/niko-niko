<?php

/**
 *	Classe servant a travailler avec les emoticones (smileys)
 *
 *	@brief Premet l'affichage la reconnaissance des emoticones	
 *	@decription Definit les scores
 *	@author	Artiom FEDOROV
 *
 * codes:
 *  30 = :-)
 *  20 = :-|
 *	10 = :-(
 */



class MySmiley {

	// Chemin vers les images emoticones
	private static $imgpath = "./images/";


	/**
	 *	Methode de conversion string en smileys
	 *
	 *	@param str	prend en parametre une chaine avec smiley dedan a detecter
	 *	@param echo	parametre definissant affichage immediat ou renvoi chaine de carracteres
	 *	@return string	Renvoi la chaine de carracteres html du smiley detecté
	 */
	 
	public static function str2smiley($str, $echo = false) {
		return self::getHtmlSmile(self::detect($str), $echo); 
	}
	

	/**
	 *	Methode simpliste de detection de smiley
	 *
	 *	@brief	renvoi le score du smiley detecté dans une chaine de carracteres
	 *	@description	Si dans une chaine de carracteres :-) ou :-| ou :-( est detecté
	 *					alors on renvoi le score correspondant
	 *
	 *	@return	int 	Renvoi le code du smiley trouvé dans la chaine
	 *	@param String Prend en parametre la chaine de carracteres
	 */
	
	public static function detect($str) {
	
		if (strpos($str, ':-)') !== false) {
			return 30;
		}
		
		if (strpos($str, ':-|') !== false) {
			return 20;
		}
		
		if (strpos($str, ':-(') !== false) {
			return 10;
		}
		return false;	
	}


	/**
	 *	Methode permettant d'obtenir l'html a partir du smiley code
	 *	@param	int		Prend en parametre le code du smiley : 10/20/30
	 *	@brief	Conveti un smiley code en html
	 *	@return String	Renvoi la l'image en html correspondant au score
	 *
	 */
	 
	public static function getHtmlSmile($smileCode, $echo) {
		$prefix = "smiley-";
		$ext = ".png";
		switch ($smileCode) {
			case 10:
				$out = self::htmlImgWrapp(self::$imgpath . $prefix . "10" . $ext);
				break;
			case 20:
				$out = self::htmlImgWrapp(self::$imgpath . $prefix . "20" . $ext);
				break;
			case 30:
				$out = self::htmlImgWrapp(self::$imgpath . $prefix . "30" . $ext);
				break;
			default:
				$out =  "";
		}
		if ($echo) {
			echo($out);
		}
		return $out;
	}


	/**
	 *	Methode permettant d'obtenir la moyenne
	 *
	 */

	public static function getRoundedScore($score, $values = array(0, 10, 20, 30)) {
	
		$lessDelta = 100;
		$pointer = 0;
		foreach($values as $key=>$val) {
			if ((($score - $val) * ($score - $val)) < $lessDelta) {
				$lessDelta = (($score - $val) * ($score - $val));
				$pointer = $key;
			}
		}	
		return $values[$pointer];
	}


	/**
	 *	Methode de wrapping pour afficher l'image
	 *	@brief	html helper 
	 *
	 */

	private static function htmlImgWrapp($filename) {
		$result = '<img width="72" border="0" height="72" src="'.$filename.'" alt="" />';
		return $result;
	}
}

