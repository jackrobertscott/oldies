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
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/standard.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/icomoon/style.css">
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.23/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo LINK; ?>theme/js/main.js"></script>
</head>
<body ng-app="">
<div class="header">
    <div class="content oh m-a ptb-7">
    	<h1 onCLick="window.location='<?php echo LINK; ?>index.php'"><?php echo COMPANYNAME; ?></h1>
    </div>
</div>
<?php if(!empty($TITLE)): ?>
<div class="content oh m-a">
    <p class="title"><?php echo $TITLE; ?></p>
</div>
<?php endif; ?>
<?php if(!empty($errors)): ?>
    <div class="content oh m-a">
        <?php include(ASSETS.'errors.php'); ?>
    </div>
<?php endif; ?>

<!-- CODE -->