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
---------

*	protected 	$errors;
*	protected 	$prefixErr;
*	protected 	$id;

METHODS
-------

* 	public 			function 	dbUpdate($args, $id = null, $table = null)
* 	public 			function 	inUse($table, $key, $value)
* 	public 			function 	dbInsert($table, $array)
* 	public 			function 	get($table, $key, $id = null)
*	public 			function 	deactivate($table, $id = null)
* 	public	static 	function 	sanPOST()
* 	public 	static 	function 	sanGET()
* 	public 	static 	function 	escape($str)
* 	public 			function 	getErrors() //Use --> array_merge($errArray1, $errArray2);

///////////////////////////////////////////////////////////////////////////////////////////

User extends Base

VARIABLES
---------

NULL

METHODS
-------

* 	public 			function 	logIn($email, $password)
*	public 			function 	logOut()
* 	public 			function 	create($email, $password)
* 	public 			function 	verify($verCode = null)
* 	public 			function 	changePass($password = null, $newpassword = null)
* 	public 			function 	resetPass($email, $np)

PRIVATE
-------

* 	private 		function 	checkEmail($email)
* 	private 		function 	checkUser($email, $password = null)
* 	private 		function 	create($email, $password = null, $args)

///////////////////////////////////////////////////////////////////////////////////////////

EmailMonkey extends Base

VARIABLES
---------

*	private 	$brandname;
*	private 	$websiteurl;
*	private 	$supemail;
*	private 	$urlwtags;
*	private 	$email_des = array();
*	public 		$errors = array();

METHODS
-------

* 	public 			function 	verifyemail($verCode, $direction)
* 	public 			function 	passEmail($direction, $np)
* 	public 			function 	sendToSupport($email, $subj, $mess)
* 	public 			function 	sendMessage($direction, $subj, $mess)
* 	public 			function 	testEmail($direction, $unsub)

///////////////////////////////////////////////////////////////////////////////////////////

Image extends Base

VARIABLES
---------

*	private 	$location;
*	private 	$ImgArray;
*	private 	$extention;

METHODS
-------

* 	public 			function 	upload($name, $dirFolder, $nw, $nh)
* 	public 			function 	getLocation()

PRIVATE
-------

* 	private 		function 	checkImg()
* 	private 		function 	copyAndAdd($nw, $nh)
* 	private 		function 	dimensions($upw, $uph, $nw, $nh)







