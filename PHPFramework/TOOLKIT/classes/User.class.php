<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class User extends Base
{
	const COLUMN_ID = 'Id';
	const COLUMN_EMAIL = 'Email';
	const COLUMN_SALT = 'Salt';
	const COLUMN_PASSWORD = 'Password';
	const COLUMN_VERCODE = 'VerCode';
	const COLUMN_VERIFIED = 'Verified';
	const COLUMN_ACTIVE = 'Active';
	const COLUMN_UNSUBSCRIBED = 'Unsubscribed';

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
	public function login($email, $password)
	{
		if(!$this->checkUser($email, $password))
			return false;
		setcookie("UserId", $this->get(COLUMN_VERCODE));
		if(!$this->get(COLUMN_ACTIVE))
		{
			$activate = array(COLUMN_ACTIVE => 1);
			$this->dbUpdate($activate);
		}
		return true;
	}

	/**
	*Destroy cookie Id variable and session array
	*
	*@return boolean = success/failure
	*/
	public function logout()
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
			$em = new EmailManager();
			$verCode = $this->get(COLUMN_VERCODE);
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
		if(!$verCode = $this->get(COLUMN_VERCODE))
			return false;
		if($testCode == $verCode)
		{
			$args = array(COLUMN_VERIFIED => 1);
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
		return $this->inUse(COLUMN_EMAIL, $email);
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
		if($result = $mysqli->query("SELECT ".COLUMN_ID.", ".COLUMN_SALT.", ".COLUMN_PASSWORD." FROM $this->table WHERE ".COLUMN_EMAIL." = '$email'"))
		{
			if($mysqli->affected_rows < 1)
			{
				$this->errors[] = "Email submited does not belong to an account.";
				return false;
			}
			$assoc = $result->fetch_assoc();
			$encPass = SHA1($password . $assoc[COLUMN_SALT]);
			if($encPass == $assoc[COLUMN_PASSWORD])
			{
				$this->id = $assoc[COLUMN_ID];
				$_SESSION['UserId'] = $assoc[COLUMN_ID];
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
		$salt = SHA1(rand() . microtime());
		$insArray = array(
		COLUMN_EMAIL => $email,
		COLUMN_SALT => $salt,
		COLUMN_VERCODE => SHA1(microtime() . rand()),
		COLUMN_PASSWORD => SHA1($password . $salt),
		COLUMN_ACTIVE => 1
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
		$email = $this->get(COLUMN_EMAIL);
		$salt = $this->get(COLUMN_SALT);
		$encPass = SHA1($newpassword . $salt);
		$id = $_SESSION['UserId'];
		if(!$this->checkUser($email, $password))
			return false;
		$args = array(COLUMN_PASSWORD => $encPass);
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
		if($result = $mysqli->query("SELECT ".COLUMN_SALT." FROM $this->table WHERE ".COLUMN_EMAIL." = '$email'"))
		{
			$assoc = $result->fetch_assoc();
			$salt = $assoc[COLUMN_SALT];
			$EncNp = SHA1($np . $salt);
			if(!$mysqli->query("UPDATE $this->table SET ".COLUMN_PASSWORD." = '$EncNp' WHERE ".COLUMN_EMAIL." = '$email'"))
			{
				$this->errors[] = $this->prefixErr . $mysqli->error;
				return false;
			}if($mysqli->affected_rows < 1){
				$this->errors[] = "Email address not registered to an account.";
				return false;
			}else{
				$em = new EmailManager();
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
	*Set User 'Unsubscribed' = 0
	*
	*@return boolean = success/failure
	*/
	public function subscribe()
	{
		return $this->updateSubscription(0);
	}

	/**
	*Set User 'Unsubscribed' = 1
	*
	*@return boolean = success/failure
	*/
	public function unsubscribe()
	{
		return $this->updateSubscription(1);
	}

	/**
	*Update the subscription
	*
	*@param $i = the (int) value to set the subscription (0 or 1)
	*@return boolean = success/failure
	*/
	public function updateSubscription($i)
	{
		if(!$this->id)
		{
			$this->errors[] = "User must log in to unsubscribe.";
			return false;
		}
		if($this->get(COLUMN_UNSUBSCRIBED) == $i)
		{
			$this->errors[] = "This account is already unsubscribed.";
			return false;
		}
		$args = array(COLUMN_UNSUBSCRIBED => $i);
		if(!$this->dbUpdate($args))
			return false;
		return true;
	}

}
?>