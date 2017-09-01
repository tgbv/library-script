<?php 

// lib.php
//
// Started: 23/08/2017

require_once("config.php");

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


class SEARCH
{
	static function get_results($a, $b, $c)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$check = explode(":", $a);
		
		if($check[0] === "bid")
		{
			if($c === 1)
			{
				if($ret = $mysql -> check("SELECT * FROM books WHERE books.id = '$check[1]' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else
				{
					return false;
				}
			}
			else if($c === -1)
			{
				if($ret = $mysql -> check("SELECT * FROM books WHERE books.id = '$check[1]' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else
				{
					return false;
				}
			}
		}
		else
		{			
			if($c == 1)
			{
				if($ret = $mysql -> check("SELECT * FROM books WHERE books.number = '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM books WHERE books.author REGEXP '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM books WHERE books.title REGEXP '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else
				{
					return false;
				}
			}
			else if($c == -1)
			{
				if($ret = $mysql -> check("SELECT * FROM books WHERE books.number = '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM books WHERE books.author REGEXP '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM books WHERE books.title REGEXP '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else
				{
					return false;
				}
			}
		}
		
		$mysql -> disconnect();
	}
}

class REGISTER
{
	static function get_latest_regs($a, $b)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		if($b == 1)
		{
			$ret = $mysql -> query("SELECT * FROM books ORDER BY $a ASC LIMIT 10");
			$ret = mysqli_fetch_all($ret);
		}
		else if($b == -1)
		{
			$ret = $mysql -> query("SELECT * FROM books ORDER BY $a DESC LIMIT 10");
			$ret = mysqli_fetch_all($ret);
		}
		
		return $ret;
		
		$mysql -> disconnect();
	}
	
	static function reg_book($a, $b, $c, $d)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$error = null;
		
		if($ret = $mysql -> check("SELECT id FROM books WHERE books.title = '$b' AND books.author = '$a';"))
		{
			$error .= "<br>Such book title written by this author already exists.";
		}
		else if($ret = $mysql -> check("SELECT id FROM books WHERE books.number = '$c';"))
		{
			$error .= "<br>Book number is already taken.";
		}
		else
		{
			if($mysql -> query("INSERT INTO books(author, title, number, description) VALUES('$a', '$b', '$c', '$d');"))
			{
				return "Book registered with success!";
			}
			else
			{
				return "MySQL error occurred. Please try again later.";
			}
		}
		
		if($error !== null)
		{
			return $error;
		}
		
		$mysql -> disconnect();
	}
	
	static function edit_book($a, $b, $c, $d, $e)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$error = null;
		
		if($mysql -> check("SELECT id FROM books WHERE books.id = '$a';"))
		{
			if($mysql -> query("UPDATE books SET author = '$b', title = '$c', number = '$d', description = '$e' WHERE books.id = '$a';"))
			{
				return "Book updated with success!";
			}
			else
			{
				return "MySQL error occurred. Please try again later.";
			}
		}
		else
		{
			$error = 'Book ID does not exist!';
		}
		
		if($error !== null)
		{
			return $error;
		}
		
		$mysql -> disconnect();
	}
}

class USER
{
	static function login($a, $b)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$ret = $mysql -> check("SELECT password FROM users WHERE users.username = '$a';");
		
		if($ret)
		{
			$pass = hash("sha256", $a . CRYPT_STRING . $b);
			
			if(mysqli_fetch_array($ret)["password"] === $pass)
			{
				$mysql -> query("UPDATE users SET users.ip = '$_SERVER[REMOTE_ADDR]' WHERE users.username = '$a';");
	
				setcookie("key", $pass, time()+3600, "/");
				
				header("Location: /library.php", true, 301);
			}
			else
			{
				return "Invalid password! Please retry.";
			}
		}
		else
		{
			return "Invalid username! Please retry."; 
		}
		
		$mysql -> disconnect();
	}
	
	static function check_login()
	{
		if(!isset($_COOKIE["key"]))
		{
			$_COOKIE["key"] = null;
		}
		
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		if($mysql -> check("SELECT id FROM users WHERE users.password = '$_COOKIE[key]';"))
		{
			return true;
		}
		else
		{
			return false;
		}
		
		$mysql -> disconnect();
	}
}

class MISC
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
}