<?php 

class ADMIN
{
	public $mysql;
	
	function __construct()
	{
		$this -> mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$this -> mysql -> connect();
	}
	
	function login($a, $b)
	{
		if($this -> check_email($a))
		{
			if($this -> check_password($a, $b))
			{
				$pass = hash("sha256", $a. CRYPT_STRING . $b);
				
				setcookie("key", $pass, time()+3600, "/");
				
				MISC :: redirect("/library.php");
			}
			else
			{
				return "The provided password is incorrect.";
			}
		}
		else
		{
			return "E-mail address does not exist.";
		}
	}
	
	function check_login()
	{
		if(!isset($_COOKIE["key"]))
		{
			return false;
			exit();
		}
		
		if($this -> mysql -> check("SELECT id FROM ml_users WHERE ml_users.password = '$_COOKIE[key]';"))
		{
			return true;
		}
		else
		{
			return false;
		}
		
		$mysql -> disconnect();
	}
	
	protected function check_email($a)
	{
		if($this -> mysql -> check("SELECT id FROM ml_users WHERE ml_users.email = '$a';"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	protected function check_password($a, $b)
	{
		$pass = hash("sha256", $a . CRYPT_STRING . $b);
		
		if($this -> mysql -> check("SELECT id FROM ml_users WHERE ml_users.password = '$pass';"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

class SETTINGS extends ADMIN
{
	public $mysql;
	
	function __construct()
	{
		parent :: __construct();
		
		$this -> mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$this -> mysql -> connect();
	}
	
	function update_password($a, $b, $c)
	{
		if(parent :: check_email($a))
		{
			if(parent :: check_password($a, $b))
			{
				$pass = hash("sha256", $a . CRYPT_STRING . $c);
				
				if($this -> mysql -> query("UPDATE ml_users SET ml_users.password = '$pass' WHERE ml_users.email = '$a';"))
				{
					setcookie("key", $pass, time()+3600, "/");
					
					return "Password updated with success!";
				}
				else
				{
					return "MySQL error occurred. Please try again later or contact the developer.";
				}
			}
			else
			{
				return "The provided password is incorrect.";
			}
		}
		else
		{
			return "The provided e-mail address does not exist.";
		}
	}
	
	function update_email($a, $b, $c)
	{
		if(parent :: check_email($a))
		{
			if(parent :: check_password($a, $c))
			{
				if($this -> mysql -> query("UPDATE ml_users SET ml_users.email = '$c' WHERE ml_users.email = '$a';"))
				{	
					$pass = hash("sha256", $c . CRYPT_STRING . $b);
					
					setcookie("key", $pass, time()+3600, "/");
					
					return "E-mail updated with success!";
				}
				else
				{
					return "MySQL error occurred. Please try again later or contact the developer.";
				}
			}
			else
			{
				return "The provided password is incorrect.";
			}
		}
		else
		{
			return "The provided e-mail address does not exist.";
		}
	}
}