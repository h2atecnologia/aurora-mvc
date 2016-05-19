<?php

namespace Helper;

class StringUtility
{
	public static function slugify($text)
	{ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	
		// trim
		$text = trim($text, '-');
	
		$lc = setlocale(LC_CTYPE, 'pt_BR');
		//echo $text;
		// transliterate
		try
		{
			$text = iconv('UTF-8', 'US-ASCII//TRANSLIT', $text);
			$text = iconv('UTF-8', 'US-ASCII//IGNORE', $text);
		} catch (Exception $e) {
			//
		}
	
		setlocale(LC_CTYPE, $lc);
	
		// lowercase
		$text = strtolower($text);
	
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
	
		if (empty($text))
		{
			return 'n-a-' . rand(1000, 9999);
		}
	
		return $text;
	}

	public static function extract_extension($text)
	{
		$_pieces = explode(".", $text);

		if(count($_pieces) > 1)
			array_pop($_pieces);
	
		return implode(".", $_pieces);			
	}

	public static function strtolower_ex($text){ 
		return mb_convert_case($text, MB_CASE_LOWER, "UTF-8"); 
	}

	public static function strtoupper_ex($text){ 
		return mb_convert_case($text, MB_CASE_UPPER, "UTF-8"); 
	}
}

?>