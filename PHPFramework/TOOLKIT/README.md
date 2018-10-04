Web Toolkit - Page
==================

Install
-------

Copy the contents of the 'website' folder into the domain name's folder.

Copy the 'assets' folder to the same directory as the domain name folder.

Personalise
-----------

To personalise the website; edit the text withing the '<your website>/theme' directory.

To configure the websites main settings; edit the config.php file.

CSS of JS code (including AJAX) may also be added/edited via the corresponding files 
and folders located within this directory. 

Add Pages
---------

To add more web pages to the website; add them to the main website directory along side
the 'index.php' file. (use the 'index.php' file as a template added pages)

Class Functions
---------------

Base

**all classes that extend from base should use the base functions when
querieing the database, unless a seperate function has been specified**  

* success 	(bool) 	dbUpdate($args, $id = null, $table = null)
* success	(bool) 	inUse($key, $value, $table = null)
* Id 		(int) 	dbInsert($args, $table = null)
* Value		(*)		get($key, $id = null, $table = null)
* success	(bool) 	deactivate($id = null, $table = null)
* success	(bool) 	Base::sanPOST()
* success	(bool) 	Base::sanGET()
* success	(bool) 	Base::escape($str)
* Id		(int) 	getId()
* Errors	(array) getErrors()

User extends Base

* function __construct()
* success	(bool) 	logIn($email, $password)
* success	(bool) 	logOut()
* success	(bool) 	create($email, $password, $args)
* success	(bool) 	verify($testCode)
* success	(bool) 	checkEmail($email)
* success	(bool) 	checkUser($email, $password = null)
* success	(bool) 	insertUser($email, $password = null, $args)
* success	(bool) 	changePass($password = null, $newpassword = null)
* success	(bool) 	resetPass($email, $np)
* success	(bool) 	remove($id = null)

EmailManager extends Base

* __construct()
* success	(bool) 	verifyemail($verCode, $direction)
* success	(bool) 	passEmail($direction, $np)
* success	(bool) 	sendToSupport($email, $subj, $mess)
* success	(bool) 	sendMessage($direction, $subj, $mess)
* success	(bool) 	testEmail($direction, $unsub)

Image extends Base

* __construct($id = null)
* success	(bool) 	upload($name, $dirFolder, $nw, $nh)
* success	(bool) 	create($extras = null, $userId = null, $location = null)
* success 	(bool) 	addDisplayImage($extras = null, $userId = null, $location = null)
* Location	(str) 	getLocation()
* Id 		(int) 	getImageId()
* success	(bool) 	remove($id = null)

Post extends Base

* __construct($table, $id = null)
* success	(bool) 	create($message = "", $extras = null, $userId = null)
* success	(bool) 	like($postId = null, $userId = null)
* success	(bool) 	dislike($postId = null, $userId = null)
* success	(bool) 	remove($id = null)

Link extends Base

* __construct($table, $oneId, $twoId = null)
* success	(bool) create($oneId, $twoId)
* success 	(bool) linkExists($oneId, $twoId)





