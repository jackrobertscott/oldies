<?php
/**
* Configuration
* -------------
* Root Directory
*/

define("WEBSITE_FOLDER_NAME", "Stream");
define("LINK", "http://".$_SERVER["HTTP_HOST"]."/".WEBSITE_FOLDER_NAME."/");
define("THEME_RES", $_SERVER['DOCUMENT_ROOT']."/".WEBSITE_FOLDER_NAME."/");
define("ASSETS_FOLDER_NAME", "TOOLKIT");
define("ASSETS", $_SERVER['DOCUMENT_ROOT']."/".ASSETS_FOLDER_NAME."/");
$_SESSION['LINK'] = LINK;
$_SESSION['THEME_RES'] = THEME_RES;
$_SESSION['ASSETS'] = ASSETS;

/**
* Configuration
* -------------
* Time Zone
*/

$_SESSION['TIMEZONE'] = 'Australia/Perth';
date_default_timezone_set($_SESSION['TIMEZONE']);

/**
* Configuration
* -------------
* DB Connection
*/

define("DB_FILE", "sldb.php");
define("DB_CONNECT", ASSETS.'../../'.DB_FILE);
$_SESSION['DB_CONNECT'] = DB_CONNECT;

/**
* Configuration
* -------------
* Errors
*/

define("ERROR_MESSAGE", "Errors found");

/**
* Configuration
* -------------
* Tables
*/

define("TABLE_USERS", "Users");
define("TABLE_IMAGES", "Images");
define("TABLE_DIS", "DisplayImages");
define("TABLE_POSTS", "Posts");
define("TABLE_FRIENDS", "Friends");
define("TABLE_FOLLOWS", "Follows");
$_SESSION['TABLE_USERS'] = TABLE_USERS;
$_SESSION['TABLE_IMAGES'] = TABLE_IMAGES;
$_SESSION['TABLE_DIS'] = TABLE_DIS;
$_SESSION['TABLE_POSTS'] = TABLE_POSTS;
$_SESSION['TABLE_FRIENDS'] = TABLE_FRIENDS;
$_SESSION['TABLE_FOLLOWS'] = TABLE_FOLLOWS;

/**
* Configuration
* -------------
* Meta Data Details
*/

define("META_DESC", "");
define("META_KEYS", "");
define("META_AUTH", "");

/**
* Configuration
* -------------
* General
*/

define("PASSWORD_MIN", "6");
define("FACEBOOK_LINK", "null");
define("TWITTER_LINK", "null");

/**
* Configuration
* -------------
* Company Details
*/

define("COMPANYNAME", "Stream");
define("COMPANYSLOGAN", "Event by Event Social Networking");
define("WEBURL", "bantanet.com");
define("SUPPORTEMAIL", "support@bantanet.com");
define("WEBURLwTAGS", "http://".WEBURL."/");

/**
* Configuration
* -------------
* Image Uploads
*/

define("UPLOADFILE", "uploads");

/**
* Configuration
* -------------
* Posts
*/

define("ROWS_PER_PAGE", "10");

/**
* Configuration
* -------------
* About Page
*/

$ABOUT_PARAS = array(
	"Stream is a service that provides its users to easy manage their daily activities.",
	"It is also a great platform for creating project timelines which you can easy share 
	with others."
);
?>