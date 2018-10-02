<?php
session_start();
$TITLE = "Update";
require('../theme/config.php');
require(ASSETS.'open.php');

if(empty($_SESSION['UserId']))
{
    header("Location: ".LINK."page/login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	Base::sanPOST();
	$inpArray = array("name");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$img = new Image();
	if(!empty($_FILES))
	{
		$img->upload("picture", "tmp", 400, 400);
		$alt = array("Alt" => $user->get("Name")." Profile Picture");
		$img->addDisplayImage(TABLE_DIS, $alt);
		$errors = array_merge($img->getErrors());
	}else{
		$errors[] = array_merge($_FILES);
	}
	if(empty($errors))
	{
		$args = array(
		"Name" => $_POST['name'],
		"Privacy" => $_POST['privacy']
		);
		$user->dbUpdate($args);
		$errors = $user->getErrors();
	}
}

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
	<p class="pf-14">Account Updated.</p>
<?php endif; ?>
<form name="form" method="POST" enctype="multipart/form-data" class="css-form" action="update.php" novalidate>
	<label class="w-100-28 oh pf-14 fs-13 bg-lightgrey">
		Privacy [who can see your events]
		<?php $select = $user->get("Privacy"); ?>
		<select name="privacy" class="fr">
			<option value="0" <?php if($select == 0) echo 'selected'; ?>>Only Me</option>
			<option value="1" <?php if($select == 1) echo 'selected'; ?>>Friends</option>
			<option value="2" <?php if($select == 2) echo 'selected'; ?>>Anyone</option>
		</select>
	</label>
	<label class="w-100-28 oh pf-14 fs-13">
		Full Name
		<input class="fr pf-7" type="text" placeholder="Name" ng-model="name" name="name" <?php if(!empty($_POST['name'])){echo 'ng-init="name=\''.$_POST['name'].'\'"';}else{echo 'ng-init="name=\''.$user->get("Name").'\'"';}?>/>
	</label>		
	<?php
	/*
	if($imgId = $user->get("ImageId"))
	{
		$base = new Base($imgId, TABLE_IMAGES);
		echo   '<label class="pf-14 oh">
					<div style="background-image:url('.$base->get("Location").');width: 180px;height: 180px;background-position: center;background-size: cover;float: right;"></div>
				</label>';
	}
	*/
	?>
	<label class="w-100-28 oh pf-14 fs-13">
		New Profile Photo
		<input type="file" name="picture" class="fr pf-7">
	</label>
	<button class="fr pf-7 mlr-14 mb-14" ng-disabled="form.$invalid">Save</button>
</form>

<?php
include("../theme/footer.php");
?>