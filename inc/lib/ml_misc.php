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
}