    </div><!-- middle -->
	<div id="right" class="fl"></div>
</div><!-- wrapper -->
<div class="w-100 b-t" id="footer">
	<div class="w-theme m-a oh ptb-14">
		<p class="ta-c">
			<a class="d-i ml-14 fs-12" class="d-i" href="<?php echo LINK; ?>index.php">Home</a>
			<?php if(!empty($_SESSION['UserId']) && !isset($logout)): ?>
			<a class="d-i ml-14 fs-12 fs-12" class="d-i" href="<?php echo LINK; ?>page/logout.php">Logout</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/update.php">Settings</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>friends.php">Friends</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/password.php">Change Password</a>
			<?php if(!$user->get("Verified")): ?>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/verify.php">Verify</a>
			<?php endif; ?>
			<?php else: ?>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/signup.php">SignUp</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/login.php">Login</a>
			<?php endif; ?>
		</p>
		<p class="ta-c mt-7">
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/about.php">About</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/contact.php">Contact</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/privacy-policy.php">Privacy Policy</a>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/terms-and-conditions.php">Terms and Conditions</a>
			<?php if(!$user->get("Unsubscribed")): ?>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/unsubscribe.php">Unsubscribe</a>
			<?php else: ?>
			<a class="d-i ml-14 fs-12" href="<?php echo LINK; ?>page/subscribe.php">Subscribe</a>
			<?php endif; ?>
			<?php if(TWITTER_LINK === null): ?>
			<a class="d-i ml-14 fs-12" href="<?php echo TWITTER_LINK; ?>" target="_blank"><span class="social-media icon-social-twitter-circular"></span> twitter</a>
			<?php endif; ?>
			<?php if(FACEBOOK_LINK === null): ?>
			<a class="d-i ml-14 fs-12" href="<?php echo FACEBOOK_LINK; ?>" target="_blank"><span class="social-media icon-social-facebook-circular"></span> facebook</a>
			<?php endif; ?>
		</p>
		<p class="ta-c fs-10 mt-7">© <?php echo COMPANYNAME." ".date('Y'); ?></p>
	</div>
</div>
<div class="event-wrap" ng-show="formDisplay">
	<div class="event m-a oh shadow cr-2">
		<form name="eventForm" novalidate>
			<label class="w-100-28 oh pf-14">
				<textarea class="fr pf-7 w-100-14 fs-13" class="w-100 mf-14" placeholder="Add an Event" name="event.Message" ng-model="event.Message" required></textarea>
				<select class="fr mt-14" ng-model="privacy" ng-options="value.privacy for value in privacyOptions" required></select>
				<span class="fs-12 fr mt-14 plr-7">Privacy: </span>
				<select class="fr mt-14" ng-model="format" ng-options="value.format for value in formatOptions" required></select>
				<span class="fs-12 fr mt-14 plr-7">Format: </span>
			</label>
			<span class="p-0 ta-l" ng-show="format.value == 0">
				<label class="w-100-28 oh plr-14 pb-14">
					<input class="fr pf-7 w-100-14 fs-13" type="text" placeholder="Location" name="event.Location" ng-model="event.Location"/>
				</label>
				<label class="w-100-28 oh pf-14 fs-13">
					Start
					<input style="width: 100px;" class="fr pf-7 ml-7" type="time" name="event.StartTime" ng-model="event.StartTime" placeholder="HH:mm:ss"/>
	   				<input style="width: 133px;" class="fr pf-7" type="date" name="event.StartDate" ng-model="event.StartDate" placeholder="yyyy-MM-dd" min="2013-01-01"/>
				</label>
				<label class="w-100-28 oh pf-14 fs-13">
					<input class="fl mr-7" type="checkbox" ng-model="event.addEnd">
					Add a End Time?
				</label>
				<label class="w-100-28 oh pf-14 fs-13" ng-show="event.addEnd">
					End
					<input style="width: 100px;" class="fr pf-7 ml-7" type="time" name="event.EndTime" ng-model="event.EndTime" placeholder="HH:mm:ss"/>
	   				<input style="width: 133px;" class="fr pf-7" type="date" name="event.EndDate" ng-model="event.EndDate" placeholder="yyyy-MM-dd" min="2013-01-01"/>
				</label>
			</span>
			<button class="fr pf-7 mlr-14 mb-14" ng-disabled="eventForm.$invalid" ng-click="addPost()">Post</button>
			<a class="fs-12 fr mt-14 plr-7" ng-click="toggleForm()">Cancel</a>
		</form>
	</div>
</div>
</body>
</html>
<?php 
$mysqli->close();
?>