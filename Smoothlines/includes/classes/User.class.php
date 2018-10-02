<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class User extends Base
{
	/**
	*This function checks if the session id variable is set
	*If it is set then the users data will be selected and stored in the data variable
	*If it is not set then the script will return to the logIn.php page
	*
	*@see all errors into $this->errors array; access via getErrors()
	*/
	function __construct()
	{
		parent::__construct();
		if(!empty($_SESSION['UserId']))
			$this->id = $_SESSION['UserId'];
		$this->table = TABLE_USERS;
	}

	/**
	*This function logs in a user. Must include the parameters email and password
	*
	*@param $email = String email
	*@param $password = String password
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function logIn($email, $password)
	{
		if(!$this->checkUser($email, $password))
			return false;
		setcookie("UserId", $this->get('VerCode'));
		return true;
	}

	/**
	*Destroy cookie Id variable and session array
	*
	*@return boolean = success/failure
	*/
	public function logOut()
	{
		setcookie("UserId" , "" , time()-3600);
		$_SESSION['UserId'] == NULL;
		session_destroy();
		return true;
	}

	/**
	*This function is called to construct a user and add them to db
	*This also sets the users data into this objects data variable
	*
	*@param $email = String email
	*@param $password = String password
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($email, $password, $args)
	{
		if(!is_array($args))
		{
			$this->errors[] = "Array not given in arguements.";
			return false;
		}
		if(!empty($_SESSION['UserId']))
		{
			$this->errors[] = "You are already signed in to an account.";
			return false;
		}else{
			if(!$this->checkEmail($email))
				return false;
			if(!$this->insertUser($email, $password, $args))
				return false;
			$em = new EmailMonkey();
			$verCode = $this->get('VerCode');
			if(!$em->verifyemail($verCode, $email))
			{
				$this->errors = $em->getErrors();
				return false;
			}
		}
		return true;
	}

	/**
	*Verify the account
	*
	*@param $verCode = String code submited by user
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function verify($testCode)
	{
		global $mysqli;
		$verCode = $this->get('VerCode');
		if($testCode == $verCode)
		{
			$args = array("Verified" => 1);
			if(!$this->dbUpdate($args))
				return false;
		}else{
			$this->errors[] = "The code does not match your verification code.";
			return false;
		}
		return true;
	}

	/**
	*this checks if the users submited email adress is available 
	*return true if not in use and false if in use already
	*
	*@param $email = String email
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	private function checkEmail($email)
	{
		return $this->inUse("Email", $email);
	}

	/**
	*Check if this user exists.
	*If the user does exist then set the id variables
	*
	*@param $email = String email, $password = String password
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	private function checkUser($email, $password = null)
	{
		global $mysqli;
		if($result = $mysqli->query("SELECT Id, Salt, Password FROM Users WHERE Email = '$email'"))
		{
			if($mysqli->affected_rows < 1)
			{
				$this->errors[] = "Email submited does not belong to an account.";
				return false;
			}
			$assoc = $result->fetch_assoc();
			$encPass = SHA1($password . $assoc['Salt']);
			if($encPass == $assoc['Password'])
			{
				$this->id = $assoc['Id'];
				$_SESSION['UserId'] = $assoc['Id'];
			}else{
				$this->errors[] = "Password is incorrect.";
				return false;
			}
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return true;
	}

	/**
	*This function inserts the Users information into the Users db table as a new user
	*
	*@param $email = String email
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	private function insertUser($email, $password = null, $args)
	{
		global $mysqli;
		if(!is_array($args))
		{
			$this->errors[] = "Array not given in arguements.";
			return false;
		}
		$insArray = array(
		"Email" => $email,
		"Salt" => SHA1(rand() . microtime()),
		"VerCode" => SHA1(microtime() . rand()),
		"Password" => SHA1($password . $insArray['Salt']),
		"Time" => date('jS M Y, g:i a'),
		"Active" => 1
		);
		//Insert to database
		if(!$this->id = $this->dbInsert($insArray))
			return false;
		$_SESSION['UserId'] = $this->id;
		//insert the extras
		if(!$this->dbUpdate($args))
			return false;
		return true;
	}

	/**
	*Check if users current password is correct, if so then change password to new password
	*
	*@param $newpassword = String password
	*@param $password = String password
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function changePass($password = null, $newpassword = null)
	{
		$email = $this->get('Email');
		$salt = $this->get('Salt');
		$encPass = SHA1($newpassword . $salt);
		$id = $_SESSION['UserId'];
		if(!$this->checkUser($email, $password))
			return false;
		$args = array("Password" => $encPass);
		if(!$this->dbUpdate($args))
			return false;
		return true;
	}

	/**
	*Check if users current password is correct, if so then change password to new password
	*
	*@param $newpassword = String password
	*@param $password = String password
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function resetPass($email, $np)
	{
		global $mysqli;
		if($result = $mysqli->query("SELECT Salt FROM Users WHERE Email = '$email'"))
		{
			$assoc = $result->fetch_assoc();
			$salt = $assoc['Salt'];
			$EncNp = SHA1($np . $salt);
			if(!$mysqli->query("UPDATE Users SET Password = '$EncNp' WHERE Email = '$email'"))
			{
				$this->errors[] = $this->prefixErr . $mysqli->error;
				return false;
			}if($mysqli->affected_rows < 1){
				$this->errors[] = "Email address not registered to an account.";
				return false;
			}else{
				$em = new EmailMonkey();
				if(!$em->passEmail($email, $np))
				{
					$this->errors = $em->getErrors();
					return false;
				}
			}
			$result->free();
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return true; 
	}

	/**
	*Set Active = 0
	*
	*@param $id = optional id of the row
	*@return boolean = success/failure
	*/
	public function remove($id = null)
	{
		return $this->deactivate($this->table, $id);
	}

}
?>