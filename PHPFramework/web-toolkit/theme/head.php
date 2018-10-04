<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo META_DESC; ?>">
    <meta name="keywords" content="<?php echo META_KEYS; ?>">
    <meta name="author" content="<?php echo META_AUTH; ?>">
    <title><?php if(empty($TITLE)){echo COMPANYNAME;}else{echo $TITLE;} ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="theme/css/style.css">
    <link rel="stylesheet" type="text/css" href="includes/libs/icomoon/style.css">
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.23/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="theme/js/main.js"></script>
</head>
<body ng-app="">
<div class="header">
	<h1 onCLick="window.location='index.php'"><?php echo COMPANYNAME; ?></h1>
  	<h2><?php if(!empty($TITLE)){echo $TITLE;} ?></h2>
</div>
<?php if(!empty($errors)) include('includes/errors.php'); ?>