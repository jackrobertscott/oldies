<?php

date_default_timezone_set('Australia/Perth');

/**
* Configuration
* -------------
* DB Connection
*/

define("DB_HOST", "localhost");
define("DB_NAME", "package");
define("DB_USER", "root");
define("DB_PASS", "root");

/**
* Configuration
* -------------
* Tables
*/

define("TABLE_USERS", "Users");
define("TABLE_POSTS", "Posts");
define("TABLE_IMAGES", "Images");
define("TABLE_FRIENDS", "Friends");
define("TABLE_MESSAGES", "Messages");
define("TABLE_COMMENTS", "Comments");

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
define("PRESETS_DIRECTORY", "../page/");
define("FACEBOOK_LINK", "null");
define("TWITTER_LINK", "null");

/**
* Configuration
* -------------
* Company Details
*/

define("COMPANYNAME", "Company Name");
define("COMPANYSLOGAN", "Company Slogan");
define("WEBURL", "email.com");
define("SUPPORTEMAIL", "support@email.com");

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
?>