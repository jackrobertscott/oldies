<?php
session_start();
$TITLE = "Find Friends";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

$uniId = $user->get("UniversityId");
$uni = $user->get("University");
$query = "SELECT Id, FirstName, LastName FROM Users WHERE UniversityId = '$uniId' AND Id <> '$myId'";
if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	if(!empty($_POST['search']))
	{
		$sArray = explode(" ", strip_tags($mysqli->real_escape_string(trim($_POST['search']))));
		foreach($sArray as $value){
			$query .= " AND ((FirstName LIKE '%$value%') OR (LastName LIKE '%$value%'))";
		}
	}
}
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
					echo '<p>Find your friends.</p>';
				}else
				{
				echo 	'<h3>No Friends Found...</h3>
						<p>You must be feeling pretty lonely.</p>';
				}
			}
			?>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<form action="find.php" method="POST">
		<input type="text" placeholder="look up friends" name="search" class="ft" value="<?php if(!empty($_POST['search'])) echo $_POST['search']; ?>"/>
		<input type="submit" value="search" class="fs">
	</form>
	<?php
	if($mysqli->affected_rows > 0)
	{
		echo '<div class="space-friend-top"></div>';
        while($row = $result->fetch_assoc())
        {
            $thisUser = $row['Id'];
            $queryUser = "SELECT Id, Active FROM Friends WHERE (Sender = '$thisUser' AND Receiver = '$myId') OR (Sender = '$myId' AND Receiver = '$thisUser')";
            if($resultUser = $mysqli->query($queryUser))
            {
            	if($mysqli->affected_rows > 0)
				{
					$assoc = $resultUser->fetch_assoc();
					if(!$assoc['Active']){
						echo  	'<div class="wrap-friend">
									<p>'.$row['FirstName'].' '.$row['LastName'].'<br>'.$uni.'</p>
									<form class="friendReq">
										<input type="hidden" name="friendId" value="'.$row['Id'].'">
										<input type="submit" class="f-add friend'.$row['Id'].' sent" value="Pending">
									</form>
								</div>';
					}else{
			            echo 	'<div class="wrap-friend reqNum'.$row['Id'].'">
									<p>'.$row['FirstName'].' '.$row['LastName'].'<br>'.$uni.'</p>
									<form class="friendReq">
										<input type="hidden" name="friendId" value="'.$row['Id'].'">
										<input type="submit" class="req-remove friends friendrem'.$row['Id'].'" value="Friends">
									</form>
								</div>';
					}
				}else
				{
					echo 	'<div class="wrap-friend">
								<p>'.$row['FirstName'].' '.$row['LastName'].'<br>'.$uni.'</p>
								<form class="friendReq">
									<input type="hidden" name="friendId" value="'.$row['Id'].'">
									<input type="submit" class="f-add friend'.$row['Id'].'" value="Add">
								</form>
							</div>';
				}
            }
			$resultUser->free();
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