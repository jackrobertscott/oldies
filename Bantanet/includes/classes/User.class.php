<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class User extends Base
{
	/**
	*DATABASE INFORMATION
	*
	*TABLE NAME
	* - Users
	*
	*FIELD NAMES
	* - Id (primary and auto incrementing)
	* - Email (unique)
	* - Password
	* - VerCode
	* - Salt
	* - Time 
	* - TimeStamp (TIMESTAMP) auto
	* - Unsubscribed
	* - Verified
	*
	*NOTE
	*Any extra fields made can be updated after a user has been inserted.
	*This is done using the function: dbUpdate(self::TABLENAME, $args, $rowId);
	*
	*Also session_start(); must be called on all pages using this class
	*/

	//VARIABLES

	const TABLENAME = "Users";

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
		if(!empty($_SESSION['Id']))
		{
			$this->setData(self::TABLENAME, $_SESSION['Id']);
		}
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
		if(!$this->setData(self::TABLENAME, $_SESSION['Id']))
			return false;
		setcookie("Id", $this->get('VerCode'));
		return true;
	}

	/**
	*Destroy cookie Id variable and session array
	*
	*@return boolean = success/failure
	*/
	public function logOut()
	{
		setcookie("Id" , "" , time()-3600);
		$_SESSION['Id'] == NULL;
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
	public function createUser($email, $password)
	{
		if(!empty($_SESSION['Id']))
		{
			$this->errors[] = "You are already signed in to an account.";
			return false;
		}else{
			if(!$this->checkEmail($email))
				return false;
			if(!$this->dbInsertUser($email, $password, $args))
				return false;
			if(!$this->setData(self::TABLENAME, $_SESSION['Id']))
				return false;
			$em = new EmailMonkey();
			if(!$em->verifyemail($this->get('VerCode'), $this->get('Email')))
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
	public function verify($verCode = null)
	{
		global $mysqli;
		$id = $_SESSION['Id'];
		if(empty($id))
		{
			$this->errors[] = "You must be signed in to verify your account.";
			return false; 
		}else{
			$query = "SELECT VerCode FROM Users WHERE Id = '$id'";
			if($result = $mysqli->query($query))
			{
				$assoc = $result->fetch_assoc();
				if($verCode == $assoc['VerCode'])
				{
					$args = array("Verified" => 1);
					if(!$this->dbUpdate(self::TABLENAME, $args, $id))
						return false;
				}else{
					$this->errors[] = "The code does not match your verification code.";
					return false;
				}
			}else{
				$this->errors[] = $this->prefixErr . $mysqli->error;
				return false;
			}
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
		return $this->inUse(self::TABLENAME, "Email", $email);
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
		if($result = $mysqli->query("SELECT Salt FROM Users WHERE Email = '$email'"))
		{
			if($mysqli->affected_rows < 1)
			{
				$this->errors[] = "Email submited does not belong to an account.";
				return false;
			}
			$assoc = $result->fetch_assoc();
			$salt = $assoc['Salt'];
			$result->free();
			$encPass = SHA1($password . $salt);
			if($result = $mysqli->query("SELECT Id FROM Users WHERE Email = '$email' AND Password = '$encPass'"))
			{
				if($mysqli->affected_rows > 0)
				{
					$assoc = $result->fetch_assoc();
					$_SESSION['Id'] = $assoc['Id'];
					$result->free();
				}else{
					$this->errors[] = "Password is incorrect.";
					return false;
				}
			}else{
				$this->errors[] = $this->prefixErr . $mysqli->error;
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
	private function dbInsertUser($email, $password = null, $args)
	{
		//Initialise variables
		global $mysqli;
		$insArray = array();
		$insArray['Email'] = $email;
		$insArray['Salt'] = SHA1(rand() . microtime());
		$insArray['VerCode'] = SHA1(microtime() . rand());
		$insArray['Password'] = SHA1($password . $insArray['Salt']);
		$insArray['Time'] = date('l jS \of F Y h:i:s A');
		//Insert to database
		if(!$this->dbInsert(self::TABLENAME, $insArray))
			return false;
		if($result = $mysqli->query("SELECT Id FROM Users WHERE Email = '$email'"))
		{
			$assoc = $result->fetch_assoc();
			$_SESSION['Id'] = $assoc['Id'];
			$result->free();
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		if(!$this->dbUpdate(self::TABLENAME, $args, $_SESSION['Id']))
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
		$id = $_SESSION['Id'];
		if(!$this->checkUser($email, $password))
			return false;
		$args = array("Password" => $encPass);
		if(!$this->dbUpdate(self::TABLENAME, $args, $id))
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
			}
			$result->free();
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return true; 
	}

}
?>