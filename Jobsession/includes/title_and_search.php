		<div class="head_bar">
			<div class="head_bar_center">
				<div class="title_bar_hold">
					<a href="index.php"><h1>Jobsession<span> - BETA</span></h1></a>
					<a href="https://www.facebook.com/pages/Jobsession/217102721823067" target="_blank"><h2 class="icon-facebook2"></h2></a>
					<a href="http://instagram.com/j0bsession" target="_blank"><h2 class="icon-instagram"></h2></a>
					<a href="https://twitter.com/j0bsession" target="_blank"><h2 class="icon-twitter2"></h2></a>
					<!-- <a href="#" target="_blank"><h2 class="icon-tumblr2"></h2></a> -->
					<!-- <a href="#" target="_blank"><h2 class="icon-instagram"></h2></a> -->
				</div><!-- title_bar_hold -->
				<?php if( $_SESSION['log'] ) : ?>
				<div class="title_bar_user">
					<h1><?php if($_SESSION['es'] == 2) : ?><a href="employer_profile.php"><?php echo $_SESSION['user'] ; ?></a><?php else : ?><?php echo $_SESSION['user'] ; ?><?php endif ; ?><span style="font-size: 14px;"> - <a href="log_out.php">Log Out</a></span></h1>
				</div><!-- title_bar_hold -->
				<?php elseif($page_title != "Log In") : ?>
				<div class="title_bar_user">
					<h1><span style="font-size: 14px;"><a href="log_in.php">Log In</a></span></h1>
				</div><!-- title_bar_hold -->
				<?php endif ; ?>
			</div><!-- head_bar_center -->
		</div><!-- head_bar -->