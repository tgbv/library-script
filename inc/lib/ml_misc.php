<?php 

class MISC extends SETTINGS
{
	static function uri_check($a)
	{
		$ret = explode("?", $a);
		
		if(empty($ret))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	static function no_cache()
	{
		header("Cache-Control: no-cache, no-store", true);
	}
	
	static function redirect($a)
	{
		$s = str_replace("\\", "/", realpath(getcwd()));
		$s = str_replace($_SERVER["DOCUMENT_ROOT"], "", $s);
		
		$makeurl = "//" . $_SERVER["HTTP_HOST"] . "$s" . "$a";
		
		header("Location: $makeurl", true, 301);
	}
}