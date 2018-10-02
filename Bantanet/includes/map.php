<div class="footer">
	<div class="middle">
		<div class="section">
			<h1>Main</h1>
			<?php if(!empty($_SESSION['Id']) && !isset($logout)): ?>
			<p><a href="timetable.php">MyTable</a></p>
			<p><a href="logout.php">Logout</a></p>
			<?php else: ?>
			<p><a href="signup.php">SignUp</a></p>
			<p><a href="login.php">Login</a></p>
			<?php endif; ?>
			<p><a href="privacy-policy.php">Privacy Policy</a></p>
			<p><a href="terms-and-conditions.php">Terms and Conditions</a></p>
		</div>
		<div class="section">
			<h1>Account</h1>
			<p><a href="edit.php">Edit MyTable</a></p>
			<p><a href="update.php">Account</a></p>
		</div>
		<div class="section">
			<h1>People</h1>
			<p><a href="friends.php">My Friends</a></p>
			<p><a href="find.php">Find Friends</a></p>
			<p><a href="requests.php">Friend Requests</a></p>
		</div>
	</div>
</div>