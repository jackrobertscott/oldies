<?php

date_default_timezone_set('Australia/Perth');

/**
* Configuration
* -------------
* DB Connection
*/

define("DB_HOST", "localhost");
define("DB_NAME", "smoothlines");
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
* Company Details
*/

define("COMPANYNAME", "Smooth Lines");
define("COMPANYSLOGAN", "The pickup line encyclopedia.");
define("WEBURL", "smoothlines.org");
define("SUPPORTEMAIL", "support@smoothlines.org");

/**
* Configuration
* -------------
* Image Uploads
*/

define("UPLOADFILE", "uploads");
?>