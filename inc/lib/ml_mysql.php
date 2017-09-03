<?php 

class MYSQL
{
	public $host;
	public $username;
	public $password;
	public $database;
	public $connection;
	
	function __construct($a, $b, $c, $d)
	{
		$this -> host = $a;
		$this -> username = $b;
		$this -> password = $c;
		$this -> database = $d;
	}

	function connect()
	{		
		if($connection = mysqli_connect($this -> host, $this -> username, $this -> password, $this -> database))
		{
			$this -> connection = $connection;
			return $connection;
		}
		else
		{
			return mysqli_connect_error();
		}
	}
	
	function query($a)
	{
		if($ret = mysqli_prepare($this -> connection, $a))
		{
			if(!$ret = mysqli_query($this -> connection, $a))
			{
				//return mysqli_error($this -> connection);
				return false;
			}
			else
			{
				return $ret;
			}
		}
		else
		{
			return false;
		}
	}
	
	function escape_string($a)
	{
		return mysqli_real_escape_string($this -> connection, $a);
	}
	
	function select_db($a)
	{
		if($ret = mysqli_select_db($connection, $a))
		{
			$this -> database = $a;
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function check($a)
	{
		$ret = $this -> query($a);
		
		if(mysqli_num_rows($ret) > 0)
		{
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	function disconnect()
	{
		mysqli_close($this -> connection);
	}
}