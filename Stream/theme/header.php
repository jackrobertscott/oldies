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
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/form.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/friend.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>theme/css/icomoon/style.css">
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.23/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo LINK; ?>theme/js/moment.js"></script>
    <script>var app = angular.module("app", []);</script>
    <script src="<?php echo LINK; ?>theme/js/main.js"></script>
    <script src="<?php echo LINK; ?>theme/js/postCtrl.js"></script>
    <script src="<?php echo LINK; ?>theme/js/friendCtrl.js"></script>
</head>
<body ng-app="app" <?php if(empty($TITLE)): ?>ng-controller="postCtrl"<?php endif; ?>>
<div id="header" class="w-100 bg-header">
    <div class="oh m-a w-theme"> 
        <a class="fl fs-16 c-white pf-14" href="<? echo LINK; ?>index.php"><?php echo COMPANYNAME; ?></a>
        <ul class="fr pr-14 c-white" style="padding-top: 12px;">
        <?php if(!empty($_SESSION['UserId']) && !isset($logout)): ?>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>index.php'">Home</span>
            </li>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>friends.php'">Friends</span>
            </li>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>page/update.php'">Settings</span>
            </li>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>page/logout.php'">Logout</span>
            </li>
        <?php else: ?>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>page/login.php'">Login</span>
            </li>
            <li class="cur-p fl"> 
                <span class="pt-7 pl-14 fs-11" onclick="window.location='<?php echo LINK ?>page/signup.php'">SignUp</span>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</div>
<div class="oh bg-black" id="privacy">
    <div class="oh m-a w-theme">
        <a class="ptb-7 plr-14 fs-10 c-white d-i fl" href="<?php echo LINK; ?>page/update.php">
        Privacy status: 
        <?php 
        switch ($user->get('Privacy')) 
        {
            case 0:
                echo 'Only Me';
                break;
            case 1:
                echo 'Friends';
                break;
            case 2:
                echo 'Anyone';
                break;
            default:
                echo 'Only Me';
                break;
        }
        ?>
        </a>
        <span class="today d-i cur-p fr fs-10 c-white ptb-7 plr-14">Today</span>
    </div>
</div>
<div id="wrapper" class="oh m-a w-theme">
    <div id="left" class="fl"></div>
    <div id="middle" class="fl b-r oa">
        <?php if(!empty($TITLE)): ?>
            <h2 id="title" class="pf-14 fs-15 b-b"><?php echo $TITLE; ?></h2>
        <?php endif; ?>
        <?php if(!empty($errors)): ?>
            <?php include('errors.php'); ?>
        <?php endif; ?>


