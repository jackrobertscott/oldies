<!-- CODE -->

<div class="footer">
  <p>
    <?php if(!empty($_SESSION['UserId']) && !isset($logout)): ?>
	<a href="<?php echo LINK; ?>page/logout.php">Logout</a>
	<a href="<?php echo LINK; ?>page/update.php">Account</a>
	<a href="<?php echo LINK; ?>page/password.php">Change Password</a>
		<?php if(!$user->get("Verified")): ?>
		<a href="<?php echo LINK; ?>page/verify.php">Verify</a>
		<?php endif; ?>
	<?php else: ?>
	<a href="<?php echo LINK; ?>page/signup.php">Sign Up</a>
	<a href="<?php echo LINK; ?>page/login.php">Login</a>
	<?php endif; ?>
  </p>
  <p>
    <a href="<?php echo LINK; ?>page/about.php">About</a>
	<a href="<?php echo LINK; ?>page/contact.php">Contact</a>
	<a href="<?php echo LINK; ?>page/privacy-policy.php">Privacy Policy</a>
	<a href="<?php echo LINK; ?>page/terms-and-conditions.php">Terms and Conditions</a>
	<?php if(!$user->get("Unsubscribed")): ?>
		<a href="<?php echo LINK; ?>page/unsubscribe.php">Unsubscribe</a>
	<?php else: ?>
		<a href="<?php echo LINK; ?>page/subscribe.php">Subscribe</a>
	<?php endif; ?>
	<?php if(TWITTER_LINK === null): ?>
		<a href="<?php echo TWITTER_LINK; ?>" target="_blank"><span class="social-media icon-social-twitter-circular"></span> twitter</a>
	<?php endif; ?>
	<?php if(FACEBOOK_LINK === null): ?>
		<a href="<?php echo FACEBOOK_LINK; ?>" target="_blank"><span class="social-media icon-social-facebook-circular"></span> facebook</a>
	<?php endif; ?>
  </p>
  <p style="font-size: 10px;">© <?php echo COMPANYNAME." ".date('Y'); ?></p>
</div>
</body>
</html>
<?php 
$mysqli->close();
?>