<?php

class request {

	public static function get($str) {
		if (isset($_GET[$str])) {
			return $_GET[$str];
		} elseif (isset($_POST[$str])) {
			return $_POST[$str];
		} else {
			return null;
		}
	}

}

?>
