</div>
<div class="footer center">
  <p>
    <?php if(!empty($_SESSION['UserId']) && !isset($logout)): ?>
	<a href="logout.php">Logout</a>
	<a href="update.php">Account</a>
	<a href="password.php">Change Password</a>
		<?php if(!$user->get("Verified")): ?>
		<a href="verify.php">Verify</a>
		<?php endif; ?>
	<?php else: ?>
	<a href="signup.php">Sign Up</a>
	<a href="login.php">Login</a>
	<?php endif; ?>
  </p>
  <p>
    <a href="about.php">About</a>
	<a href="contact.php">Contact</a>
	<a href="privacy-policy.php">Privacy Policy</a>
	<a href="terms-and-conditions.php">Terms and Conditions</a>
	<?php if(!$user->get("Unsubscribed")): ?>
	<a href="unsubscribe.php">Unsubscribe</a>
	<?php endif; ?>
	<!--
	<a href="https://twitter.com/" target="_blank"><span class="social-media icon-social-twitter-circular"></span> twitter</a>
	<a href="https://www.facebook.com/" target="_blank"><span class="social-media icon-social-facebook-circular"></span> facebook</a>
	-->
  </p>
  <p style="font-size: 10px;">© <?php echo COMPANYNAME." ".date('Y'); ?></p>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.cmo').click(function()
	{
		$('.header ul').show();
	});
	$(document).mouseup(function (e)
	{
	    var container = $('.header ul');
	    if (!container.is(e.target) // if the target of the click isn't the container...
	        && container.has(e.target).length === 0) // ... nor a descendant of the container
	        container.hide();
	});
});
</script>
</body>
</html>
<?php 
$mysqli->close();
?>