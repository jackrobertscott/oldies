app.controller("friendCtrl", ["$scope", "$http", function($scope, $http){
	$scope.friends = [];
	$scope.search = {filter: ""};
	$scope.addFriend = function(newFriend)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/add-friend.ajax.php',
	        data    : $.param(newFriend),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("friend added.");
			}else{
				console.log(data);
				alert("An error has occured.");
			}
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
		$scope.getFriends();
	};
	$scope.deleteFriend = function(badFriend)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/delete-friend.ajax.php',
	        data    : $.param(badFriend),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("friend deleted.");
			}else{
				console.log(data);
				alert("An error has occured.");
			}
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
		$scope.getFriends();
	};
	$scope.getFriends = function()
	{
		$http({
	        method  : 'GET',
	        url     : 'theme/ajax/get-friends.ajax.php',
	        data    : $.param($scope.search),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			$scope.friends = data;
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
	};
	$scope.filterSearch = function()
	{
		$scope.getFriends();
	}
	$scope.getFriends();
}]);