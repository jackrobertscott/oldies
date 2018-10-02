<?php if( !$_SESSION['log'] ) : ?>
	<div class="width_span">
		<div class="log_or_reg">
			<h1><span class="icon-login" style="font-size: 16px;font-weight: bold;"></span>     Log in </h1>
			<form action="log_in.php" method="POST">
				<div class="input_holder">
					<input type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>" placeholder="Email or Username" id="sub_username"></p>
				</div><!-- input_holder -->
				<div class="input_holder">
					<input type="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>" placeholder="Password" id="sub_password"></p>
				</div><!-- input_holder -->
				<label style="float:left;color:#ffffff;padding:10px 16px 0;font-size:14px;">
					<input type="checkbox" name="keepin" style="display:inline;" value="true"> Keep me logged in.
				</label>
				<input type="submit" class="submit_button" value="Submit">
			</form>
			<h1 style="margin-left:35px;"> OR </h1>
			<a href="user_reg.php">
				<div class="register">
					<h1>Sign Up</h1>
				</div><!-- register -->
			</a>
		</div><!-- log_or_reg -->
	</div><!-- width_span -->
<?php else : ?>
<div class="width_span">
	<h1 style="font-family: 'Lobster', cursive;">Jobsession running in Beta</h1>
</div><!-- width_span -->
<?php endif ; ?>

<?php if(!empty($errors)) : ?>
	<div class="error_bar"><p>ERROR</p></div>
<?php endif; ?>