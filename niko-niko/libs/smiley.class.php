<?php

/**
 * codes:
 *  30 = :-)
 *  20 = :-|
 *	10 = :-(
 */



class MySmiley {

	private static $imgpath = "./images/";


	public static function str2smiley($str, $echo = false) {
	
		return self::getHtmlSmile(self::detect($str), $echo); 

	}
	




	// La méthode qui se fait pas chier!	
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




	private static function htmlImgWrapp($filename) {
	
		$result = '<img width="72" border="0" height="72" src="'.$filename.'" alt="" />';
		
		return $result;
	}




}






