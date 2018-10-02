Author: Jack Scott
Date: 8/14
Version: 1.0

/*****************************/
   ESSENTIAL CLASSES PACKAGE
     	  © copyright
        Jack Scott 2014
/*****************************/

///////////////////////////////////////////////////////////////////////////////////////////

Base

VARIABLES
*********

-	protected 	$data;
-	protected 	$errors;
-	protected 	$prefixErr;

METHODS
*******

-	protected		function 	setData($table, $id = null)
- 	public 			function 	dbUpdate($table, $args, $id = null)
- 	public 			function 	inUse($table, $key, $value)
- 	public 			function 	dbInsert($table, $array)
- 	public	static 	function 	sanPOST()
- 	public 	static 	function 	sanGET()
- 	public 	static 	function 	escape($str)
- 	public 			function 	get($var)
- 	public 			function 	getErrors() //Use --> array_merge($errArray1, $errArray2);

///////////////////////////////////////////////////////////////////////////////////////////

User extends Base

VARIABLES
*********

NULL

METHODS
*******

- 	public 			function 	logIn($email, $password)
-	public 			function 	logOut()
- 	public 			function 	createUser($email, $password)
- 	public 			function 	verify($verCode = null)
- 	private 		function 	checkEmail($email)
- 	private 		function 	checkUser($email, $password = null)
- 	private 		function 	dbInsertUser($email, $password = null, $args)
- 	public 			function 	changePass($password = null, $newpassword = null)
- 	public 			function 	resetPass($email, $np)

///////////////////////////////////////////////////////////////////////////////////////////

EmailMonkey extends Base

VARIABLES
*********

-	private 	$brandname;
-	private 	$websiteurl;
-	private 	$supemail;
-	private 	$urlwtags;
-	private 	$email_des = array();
-	public 		$errors = array();

METHODS
*******

- 	public 			function 	verifyemail($verCode, $direction)
- 	public 			function 	passEmail($direction, $np)
- 	public 			function 	sendToSupport($email, $subj, $mess)
- 	public 			function 	sendMessage($direction, $subj, $mess)
- 	public 			function 	testEmail($direction, $unsub)

///////////////////////////////////////////////////////////////////////////////////////////

Image extends Base

VARIABLES
*********

-	private 	$location;
-	private 	$ImgArray;
-	private 	$extention;

METHODS
*******

- 	public 			function 	upImg($name, $dirFolder, $nw, $nh)
- 	private 		function 	checkImg()
- 	private 		function 	copyAndAdd($nw, $nh)
- 	private 		function 	dimensions($upw, $uph, $nw, $nh)
- 	public 			function 	getLocation()







