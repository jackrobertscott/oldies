<?php
session_start();
require('theme/config.php');
require(ASSETS.'open.php');

if(empty($_SESSION['UserId']))
{
    header("Location: ".LINK."page/login.php");
    exit();
}

include("theme/header.php");
?>

<span class="pf-14"><?php echo $user->get('Name'); ?>'s Stream<a class="d-i fr" ng-click="toggleForm()">+ Event</a></span>

<span ng-repeat="post in posts">
	<div ng-show="post.Type == 'post'" class="post pf-14 oh ml-7" ng-class="{'post-user': post.UserId == <?php echo $user->get('Id'); ?>}">
		<span ng-show="post.Active == 1">
			<span class="fs-11 fr">{{post.Name}}</span>
			<p class="fs-13 post-message">{{post.Message}}</p>
			<p class="fs-11" ng-show="post.Location.length">{{post.Location}}</p>
			<div class="bottom mt-14">
				<p class="d-i fs-11">{{formatDate(post.Start, 'h:m a')}}</p>
				<p ng-show="validTime(post.End)" class="d-i fs-11"> - {{formatDate(post.End, 'd MMMM h:m a')}}</p>
				<a class="d-i c-green fs-11" ng-show="post.Follows == 1 && post.UserId != <?php echo $user->get('Id'); ?>" ng-click="followPost(post)"> - following</a>
				<a class="d-i c-blue fs-11" ng-show="post.Follows != 1 && post.UserId != <?php echo $user->get('Id'); ?>" ng-click="followPost(post)"> - follow</a>
				<a class="fr d-i c-red fs-11" ng-show="post.UserId == <?php echo $_SESSION['UserId']; ?>" ng-click="delete(post)">delete</a>
			</div>
		</span>
		<span ng-hide="post.Active == 1">
			<a class="d-i c-blue fs-12" ng-click="undoDelete(post)">undo</a>
		</span>
	</div>
	<div ng-hide="post.Spacer == 'disable'" class="post-spacer ml-7"></div>
	<div ng-show="post.Type == 'day'" class="day plr-14 ptb-7 b-b oh ml-7 fs-12" ng-class="{'bg-today': post.Today}">
		<span>
			<p class="d-i fl fs-11">{{formatDate(post.Day, 'dddd, Do MMM')}}</p>
			<p ng-show="post.Today" class="d-i fl fs-11">. - Today</p>
			<a class="d-i fr fs-11" ng-click="toggleForm(formatDate(post.Day, 'YYYY-MM-DD'))">+ Event</a>
		</span>
	</div>
</span>

<script type="text/javascript">
	$('.today').click(function() {
		$('html, body').animate({
	        scrollTop: $(".bg-today").offset().top - 50
	    }, 1000);
	});
</script>

<?php
include("theme/footer.php");
?>