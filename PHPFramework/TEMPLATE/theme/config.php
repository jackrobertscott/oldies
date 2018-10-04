<?php
/**
* Configuration
* -------------
* Root Directory
*/

define("WEBSITE_FOLDER_NAME", "TEMPLATE");
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
* Tables
*/

define("TABLE_USERS", "Users");
define("TABLE_IMAGES", "Images");
define("TABLE_POSTS", "Posts");
define("TABLE_FRIENDS", "Friends");
define("TABLE_VOTES", "Votes");
$_SESSION['TABLE_USERS'] = TABLE_USERS;
$_SESSION['TABLE_IMAGES'] = TABLE_IMAGES;
$_SESSION['TABLE_POSTS'] = TABLE_POSTS;
$_SESSION['TABLE_FRIENDS'] = TABLE_FRIENDS;
$_SESSION['TABLE_VOTES'] = TABLE_VOTES;

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

define("COMPANYNAME", "cname");
define("COMPANYSLOGAN", "slogan");
define("WEBURL", "bantanet.com");
define("SUPPORTEMAIL", "support@url.com");
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
	"This is the first paragraph in the about page.",
	"This is the second paragraph.",
	"Paragraphs are surrounded by <p></p> tags"
);
?>