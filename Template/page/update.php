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
		$img->create($user->get("Name")." Profile Picture");
		$errors = array_merge($img->getErrors());
	}
	if(empty($errors))
	{
		$args = array(
		"Name" => $_POST['name'],
		"Privacy" => $_POST['privacy']
		);
		if(!empty($img->getId()))
			$args["ImageId"] = $img->getId();
		$user->dbUpdate($args);
		$errors = $user->getErrors();
	}
}

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content center">
	<p>Account Updated.</p>
</div>
<?php endif; ?>
<div class="content m-a oh pf-14 mt-14">
	<form name="form" enctype="multipart/format-data" class="css-form" action="update.php" method="POST" novalidate>
		<label class="w-100 fl ptb-7">
			Full Name
			<input class="fr pf-7" type="text" placeholder="Name" ng-model="name" name="name" <?php if(!empty($_POST['name'])){echo 'ng-init="name=\''.$_POST['name'].'\'"';}else{echo 'ng-init="name=\''.$user->get("Name").'\'"';}?>/>
		</label>		<?php
		if($imgId = $user->get("ImageId"))
		{
			$base = new Base($imgId, TABLE_IMAGES);
			echo   '<label>
						<div style="background-image:url('.$base->get("Location").');width: 180px;height: 180px;background-position: center;background-size: cover;float: right;"></div>
					</label>';
		}
		?>
		<label class="w-100 fl ptb-7">
			Profile Picture
			<input class="fr pf-7" type="file" name="picture" ng-model="picture">
		</label>
		<label class="w-100 fl ptb-7">
			Privacy [who can see your events]
			<?php $select = $user->get("Privacy"); ?>
			<select name="privacy">
				<option value="0" <?php if($select == 0) echo 'selected'; ?>>Only Me</option>
				<option value="1" <?php if($select == 1) echo 'selected'; ?>>Friends</option>
				<option value="2" <?php if($select == 2) echo 'selected'; ?>>Anyone</option>
			</select>
		</label>
		<button class="fr pf-7 mtb-7" ng-disabled="form.$invalid">Save</button>
	</form>
</div>

<?php
include("../theme/footer.php");
?>