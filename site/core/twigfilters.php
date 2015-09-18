<?php

class BaseTwigFilters {
	
	/* Convert the string to URL format */
	public function toURL($str) {
		
		$str = strtolower($str);
		$str = str_replace('/','-',$str);
		$str = str_replace(' ','-',$str);
		$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
		
		return $str;
	}
}
?>