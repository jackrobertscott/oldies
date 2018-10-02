<?php
session_start();
$TITLE = "Friends";
require('theme/config.php');
require(ASSETS.'open.php');

//code

include("theme/header.php");
?>

<span ng-controller="friendCtrl">
	<form class="w-100-28 pf-14 bg-lightgrey">
		<input class="friend-search b-f fs-14 pf-7" type="text" placeholder="search" ng-model="search.Name" ng-change="filterSearch()"/>
	</form>
	<div class="friend fl pf-14 w-50-28 b-t" ng-repeat="friend in friends | filter: search.Name">
		<div class="friend-dp fl" style="background-image={{friend.dp}}"></div>
		<p>{{friend.Name}}</p>
		<div class="fr" ng-show="friend.Level == 0">
			<p class="fs-12">Not Friends</p>
			<button class="pf-7 fs-12" ng-click="addFriend(friend)">Add</button>
		</div>
		<div class="fr" ng-show="friend.Level == 1">
			<p class="fs-12">Sent Request</p>
			<button class="delete pf-7 fs-12" ng-click="deleteFriend(friend)">Delete</button>
		</div>
		<div class="fr" ng-show="friend.Level == 2">
			<p class="fs-12">Request</p>
			<button class="is-friend pf-7 fs-12" ng-click="addFriend(friend)">Confirm</button>
			<button class="delete pf-7 fs-12" ng-click="deleteFriend(friend)">Ignore</button>
		</div>
		<div class="fr" ng-show="friend.Level == 3">
			<p class="fs-12">Friends</p>
			<button class="delete pf-7 fs-12" ng-click="deleteFriend(friend)">Delete</button>
		</div>
	</div>
</span>

<?php
include("theme/footer.php");
?>
