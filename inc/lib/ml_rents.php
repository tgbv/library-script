<?php 

class RENTS
{
	static function get_latest_rents($a, $b)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		if($b == 1)
		{
			$ret = $mysql -> query("SELECT * FROM rents ORDER BY $a ASC LIMIT 10;");
			$ret = mysqli_fetch_all($ret);
		}
		else if($b == -1)
		{
			$ret = $mysql -> query("SELECT * FROM rents ORDER BY $a DESC LIMIT 10;");
			$ret = mysqli_fetch_all($ret);
		}
		else
		{
			$ret = array();
		}
		
		$mysql -> disconnect();
		
		return $ret;
	}
}