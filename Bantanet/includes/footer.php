		<div class="footer">
			<div class="section">
				<?php if(!empty($_SESSION['Id']) && !isset($logout)): ?>
				<a href="logout.php">Logout</a>
				<?php else: ?>
				<a href="signup.php">Sign Up</a>
				<a href="login.php">Login</a>
				<?php endif; ?>
				<a href="about.php">About</a>
				<a href="contact.php">Contact</a>
			</div>
			<?php if(!empty($_SESSION['Id']) && !isset($logout)): ?>
			<div class="section">
				<a href="preferences.php">Courses</a>
				<a href="timetable.php">Timetable</a>
				<a href="edit.php">Edit</a>
				<a href="update.php">Account</a>
				<a href="password.php">Change Password</a>
				<a href="find.php">Friends</a>
			</div>
			<?php endif; ?>
			<div class="section">
				<a href="privacy-policy.php">Privacy Policy</a>
				<a href="terms-and-conditions.php">Terms and Conditions</a>
				<a href="unsubscribe.php">Unsubscribe</a>
			</div>
			<div class="section">
				<a href="https://twitter.com/bantanet" target="_blank"><span class="social-media icon-social-twitter-circular"></span> twitter</a>
				<a href="https://www.facebook.com/bantanetsocial" target="_blank"><span class="social-media icon-social-facebook-circular"></span> facebook</a>
			</div>
			<div class="section">
				<a>© bantanet 2014</a>
			</div>
		</div>
	</div><!-- right -->
</div><!-- wrap -->
<script type="text/javascript" src="js/compressed/c.js"></script>
<!-- 
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/process-ajax.js"></script>
 -->
</body>
</html>
<?php 
$mysqli->close();
?>