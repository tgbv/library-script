<?php 

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
				return "MySQL error occurred. Please try again later or inform the developer.";
			}
		}
		
		if($error !== null)
		{
			return $error;
		}
		
		$mysql -> disconnect();
	}
	
	static function reg_rent($a, $b, $c, $d)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$error = null;
		$time = date("d/m/Y h:i:s A", time());
		$c = str_replace(" ",  "", $c);
		$bid = explode(",", $c);
		
		foreach($bid as $x => $y)
		{
			if($mysql -> check("SELECT id FROM rents WHERE rents.customer = '$a' AND rents.phone_number = '$b' AND rents.book_id REGEXP '$y';"))
			{
				$error .= "Such customer with the same phone number already registered book ID: $y.<br>";
			}
			
			if(!$mysql -> check("SELECT id FROM books WHERE books.id = '$y';"))
			{
				$error .= "Book ID: $y does not exist.<br>";
			}
		}
		
		if($error !== null)
		{
			return $error;
		}
		else
		{
			if($ret = $mysql -> query("INSERT INTO rents(customer, phone_number, book_id, notes, time) VALUES('$a', '$b', '$c', '$d', '$time');"))
			{
				return "Rent created with success!";
			}
			else
			{
				return "MySQL error occurred. Please try again later or inform the developer.";
			}
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
	
	static function edit_rent($a, $b, $c, $d, $e, $f)
	{
		$mysql = new MYSQL(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$mysql -> connect();
		
		$error = null;
		
		if($mysql -> check("SELECT id FROM rents WHERE rents.id = '$a';"))
		{
			$d = str_replace(" ", "", $d);
			$bid = explode(",", $d);
			
			foreach($bid as $x => $y)
			{
				if($mysql -> check("SELECT id FROM rents WHERE rents.id != '$a' AND rents.customer = '$b' AND rents.phone_number = '$c' AND rents.book_id REGEXP '$y';"))
				{
					$error .= "Such customer with the same phone number already registered book id: $y.<br>";
					
					unset($bid[$x]);
				}
			}
			
			$d = implode(",", $bid);
		}
		else
		{
			$error = 'Rent record ID does not exist!';
		}
		
		if($error !== null)
		{
			return $error;
		}
		else
		{
			if($mysql -> query("UPDATE rents SET customer = '$b', phone_number = '$c', book_id = '$d', notes = '$e' WHERE rents.id = '$a';"))
			{
				return "Rent record updated with success!";
			}
			else
			{
				return "MySQL error occurred. Please try again later or contact the developer.";
			}
		}
		
		$mysql -> disconnect();
	}
}