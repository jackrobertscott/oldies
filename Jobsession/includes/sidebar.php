		<div class="main_left">
			<div class="left_select">
				<ul>
					<a href="index.php">
						<li><p><span class="icon-house"></span>Home</p></li>
					</a>
					<?php if(!$_SESSION['log']) : ?>
					<a href="emp_user_reg.php?emp_yes=true">
						<li><p><span class="icon-plus2"></span>Post a Job for FREE</p></li>
					</a>
					<?php elseif($_SESSION['ver'] != 2) : ?>
					<a href="verify_account.php?emp_yes=true">
						<li><p><span class="icon-plus2"></span>Post a Job for FREE</p></li>
					</a>
					<?php elseif($_SESSION['es'] != 2) : ?>
					<a href="employer_reg.php?emp_yes=true">
						<li><p><span class="icon-plus2"></span>Post a Job for FREE</p></li>
					</a>
					<?php else : ?>
					<a href="job_submit_ini.php">
						<li><p><span class="icon-plus2"></span>Post a Job for FREE</p></li>
					</a>
					<?php endif; ?>
					<?php if($_SESSION['log']) : ?>
					<a href="fav_jobs.php">
						<li><p><span class="icon-heart"></span>Favourited Jobs</p></li>
					</a>
					<?php if( $_SESSION['ver'] != 2 ) : ?>
					<a href="verify_account.php">
						<li><p><span class="icon-checkmark"></span>Verify Account</p></li>
					</a>
					<?php endif ; ?>
					<a href="update_acc.php">
						<li><p><span class="icon-user"></span>Update my Account Info</p></li>
					</a>
					<a href="upd_password.php">
						<li><p><span class="icon-key"></span>Change Password</p></li>
					</a>
					<?php if($_SESSION['es'] != 2) : ?>
					<a href="employer_reg.php">
						<li><p><span class="icon-briefcase"></span>Register as an Employer</p></li>
					</a>
					<a href="applied_jobs.php">
						<li><p><span class="icon-layout"></span>Jobs Applied For</p></li>
					</a>
					<a href="quick_cv.php">
						<li><p><span class="icon-vcard"></span>Quick CV</p></li>
					</a>
					<?php else : ?>
					<a href="employer_profile.php">
						<li><p><span class="icon-briefcase"></span>Employer Profile</p></li>
					</a>
					<?php endif; ?>
					<?php else : ?>
					<a href="log_in.php">
						<li><p><span class="icon-login"></span>Log in</p></li>
					</a>
					<a href="user_reg.php">
						<li><p><span class="icon-user-add"></span>Sign Up</p></li>
					</a>
					<a href="emp_user_reg.php?emp_yes=true">
						<li><p><span class="icon-briefcase"></span>Employers</p></li>
					</a>
					<?php endif; ?>
				</ul>
			</div><!-- left_select -->
		</div><!-- main_left -->