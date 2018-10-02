app.controller("postCtrl", ["$scope", "$http", function($scope, $http){
	$scope.posts = [];
	$scope.privacyOptions = [
		{value: 0, privacy: 'Normal'},
		{value: 1, privacy: 'Only Me'}
	];
	$scope.privacy = $scope.privacyOptions[0];
	$scope.formatOptions = [
		{value: 0, format: 'Detailed'},
		{value: 1, format: 'Quick'}
	];
	$scope.format = $scope.formatOptions[0];
	$scope.formDisplay = false;
	$scope.toggleForm = function(suggestion)
	{
		suggestion = typeof suggestion !== 'undefined' ? suggestion : '';
		if(suggestion.length)
			$scope.event.StartTime = suggestion;
		if($scope.formDisplay)
		{
			$scope.formDisplay = false;
		}else{
			$scope.formDisplay = true;
		}
	}
	var timediff = function(start, end)
	{
		var s = moment.utc(start).startOf('day');
		var e = moment.utc(end).startOf('day');
		return Math.abs(s.diff(e, 'days'));
	};
	$scope.validTime = function(timestamp)
	{
		return (new Date(timestamp)).getTime() > 0;
	}
	$scope.formatDate = function(utcTime, format)
	{
		return moment.utc(utcTime).format(format);
	}
	$scope.getPosts = function()
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/get-posts.ajax.php',
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			$scope.posts = addDays(data);
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
	};
	$scope.getFriendPosts = function(friend)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/get-posts-friend.ajax.php',
	        data    : $.param(friend),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			$scope.posts = addDays(data);
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
	}
	var addDays = function(data)
	{
		var temp = [];
		var dayNow = moment.utc().startOf('day');
		for(var i = 0; i < data.length; i++)
		{
			data[i].Type = 'post';
			temp.push(data[i]);
			if(data.length > 1 && (i+1) < data.length)
			{
				var numDays = timediff(data[i].Start, data[i+1].Start);
				for(var q = 1; q <= numDays; q++)
				{
					var block = {Type: 'day', Many: 0};
					block.Day = moment.utc(data[i].Start).startOf('day').add(q, 'days');
					block.Today = (dayNow.diff(block.Day) == 0)? true: false;
					if(q == 1)
						block.Spacer = 'disable';
					temp.push(block);
				}
				/*
				* If want to shorten wide spaces of days
				*
				if(numDays > 7)
				{
					for(var q = 1; q < 4; q++)
					{
						var block = {Type: 'day', Many: 0};
						switch(q) 
						{
							case(1):
								block.Spacer = 'disable';
								block.Day = moment.utc(data[i].Start).startOf('day').add(q, 'days');
								block.Today = (dayNow.diff(block.Day) == 0)? true: false;
							break;
							case(2):
								block.Many = numDays;
							break;
							case(3):
								block.Day = moment.utc(data[i].Start).startOf('day').add(numDays, 'days');
								block.Today = (dayNow.diff(block.Day) == 0)? true: false;
							break;
						}
						temp.push(block);
					}
				}else{
					//normal code to show days
				}
				*/
			}
		}
		return temp;
	}
	$scope.updateFollow = function(post)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/get-single.ajax.php',
	        data    : $.param(post),
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			console.log("single updated.");
			post.Follows = data.Follows;
		}).
		error(function(response){
			alert("Servers were unable to be accessed at this time.");
			console.log("status: " + response.status);
			console.log("data: " + response.data);
		});
	};
	$scope.reset = function()
	{
		$scope.event.Message = "";
		$scope.quick();
	};
	$scope.quick = function()
	{
		$scope.event.StartDate = "";
		$scope.event.StartTime = "";
		$scope.event.EndDate = "";
		$scope.event.EndTime = "";
		$scope.event.Location = "";
	};
	$scope.addPost = function()
	{
		if($scope.format == $scope.formatOptions[1])
			$scope.quick();
		if(!$scope.addEnd)
		{
			$scope.event.EndDate = "";
			$scope.event.EndTime = "";
		}
		$scope.event.Privacy = $scope.privacy.value;
		if($scope.event.StartDate+' '+$scope.event.StartTime > $scope.event.EndDate+' '+$scope.event.EndTime
			&& $scope.event.EndDate+' '+$scope.event.EndTime > 0)
		{
			alert('Event must start before it ends.');
			return;
		}
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/set-posts.ajax.php',
	        data    : $.param($scope.event),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("post added.");
				$scope.reset();
				$scope.getPosts();
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
		$scope.toggleForm();
	};
	$scope.delete = function(post)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/delete-post.ajax.php',
	        data    : $.param(post),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("post deleted.");
				post.Active = 0;
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
	}
	$scope.undoDelete = function(post)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/undo-delete-post.ajax.php',
	        data    : $.param(post),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("post reactivated.");
				post.Active = 1;
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
	}
	$scope.followPost = function(post)
	{
		$http({
	        method  : 'POST',
	        url     : 'theme/ajax/follow-post.ajax.php',
	        data    : $.param(post),  // pass in data as strings
	        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    }).
		success(function(data){
			if(data.length === 0)
			{
				console.log("post followed.");
				$scope.updateFollow(post);
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
	}
	$scope.getPosts();
}]);