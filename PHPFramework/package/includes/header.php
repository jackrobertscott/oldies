<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <!--
    This is used for view on small media devices such as mobile
    <meta name=viewport content="width=device-width, initial-scale=1">
    ***********************************************************
    This is the web page information
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    -->
    <title><?php if(empty($TITLE)){echo COMPANYNAME;}else{echo $TITLE;} ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="includes/libs/icomoon/style.css">
</head>
<body>
<?php if(!empty($errors)) include('includes/errors.php'); ?>