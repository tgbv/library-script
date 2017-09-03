<?php 

class SEARCH
{
	static function get_results($a, $b, $c)
	{
		if(strlen($a) < 3)
		{
			return "Please input at least 3 characters in the search query.";
			exit();
		}
		
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
		
	static function get_rents($a, $b, $c)
	{
		if(strlen($a) < 3)
		{
			return "Please input at least 3 characters in the search query.";
			exit();
		}
		
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();

		$rid = preg_replace('/rid:/', '', $a);
		
		if(preg_match('/rid:/', $a) != false)
		{
			if($c == 1)
			{
				if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.id = '$rid' ORDER BY $b ASC;"))
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
				if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.id = '$a' ORDER BY $b DESC;"))
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
				if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.customer = '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.phone_number REGEXP '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.book_id REGEXP '$a' ORDER BY $b ASC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.time REGEXP '$a' ORDER BY $b ASC;"))
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
				if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.customer = '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.phone_number REGEXP '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.book_id REGEXP '$a' ORDER BY $b DESC;"))
				{
					return mysqli_fetch_all($ret);
				}
				else if($ret = $mysql -> check("SELECT * FROM rents WHERE rents.time REGEXP '$a' ORDER BY $b DESC;"))
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