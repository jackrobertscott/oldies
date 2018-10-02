<?php
session_start();
$TITLE = "Requests";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

$query = "SELECT Sender, Active FROM Friends WHERE Receiver = '$myId' AND Active = '0'";
include('includes/pageIndexHead.php');

include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<?php if(!empty($errors)): ?>
			<?php include('includes/error-notice.php'); ?>
		<?php else: ?>
			<?php
			if($result = $mysqli->query($query))
			{
				if($mysqli->affected_rows > 0)
				{
					echo '<p>See your friends requests.</p>';
				}else
				{
					echo '<p>You currently have no friend requests</p>';
				}
			}
			?>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<?php
	if($mysqli->affected_rows > 0)
	{
		echo '<div class="space-friend-top"></div>';
		while($assoc = $result->fetch_assoc())
		{
			if($_SESSION['Id'] == $assoc['Sender']) continue;
			$userId = $assoc['Sender'];
			$queryUser = "SELECT Id, FirstName, LastName, University FROM Users WHERE Id = '$userId'";
			if($resultUser = $mysqli->query($queryUser))
			{
				$row = $resultUser->fetch_assoc();
				echo 	'<div class="wrap-req reqNum'.$row['Id'].'">
							<p>'.$row['FirstName'].' '.$row['LastName'].'<br>'.$row['University'].'</p>
							<form class="friendAdd">
								<input type="hidden" name="friendId" value="'.$row['Id'].'">
								<input type="submit" class="req-add friendadd'.$row['Id'].'" value="Add">
							</form>
							<form class="friendRem">
								<input type="hidden" name="friendId" value="'.$row['Id'].'">
								<input type="submit" class="req-remove friendrem'.$row['Id'].'" value="Ignore" style="">
							</form>
						</div>';
				$resultUser->free();
			}
		}
		$result->free();
	}else
	{
		echo 	'<div class="pretty-pic">
					<h2>:/</h2>
				</div>';
	}
	?>
	<?php include('includes/pageIndexFoot.php'); ?>
</div>
<?php
include("includes/footer.php");
?>