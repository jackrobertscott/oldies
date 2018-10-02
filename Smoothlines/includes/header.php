<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="description" content="The pickup line encyclopedia.">
    <meta name="keywords" content="pickup line lines smooth ice breaker">
    <meta name="author" content="Jack Scott">
    <title><?php if(empty($TITLE)){echo COMPANYNAME;}else{echo $TITLE;} ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="includes/libs/icomoon/style.css">
</head>
<body>
<div class="header">
  <div class="center clearfix">
    <h1 onclick="window.location='index.php'"><?php echo COMPANYNAME; ?></h1>
    <h1 class="icon-cog cmo"></h1>
    <ul class="corner shadow">
      <?php if(!empty($_SESSION['UserId']) && !isset($logout)): ?>
        <li onclick="window.location='logout.php'">Logout</li>
        <li onclick="window.location='update.php'">Account</li>
        <li onclick="window.location='password.php'">Change Password</li>
          <? if(!$user->get("Verified")): ?>
          <li onclick="window.location='verify.php'">Verify</li>
          <?php endif; ?>
        <?php else: ?>
        <li onclick="window.location='signup.php'">Sign Up</li>
        <li onclick="window.location='login.php'">Login</li>
        <?php endif; ?>
    </ul>
  </div>
</div>

<div class="center content">
<?php include('includes/sidemenu.php'); ?>
<?php if(!empty($errors)) include('includes/errors.php'); ?>