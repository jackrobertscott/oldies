<?php
include('includes/Permutation.class.php');
if(!empty($_GET['word']))
{
	$word = strip_tags($_GET['word']);
	$perm = new Permutation($word);
}else{
	header("Location: index.php?word=empty");
}
include('includes/header.php');
?>

<a href="index.php">
	<div class="back">Back</div>
</a>
<span><?php echo $perm->getNumPerms(); ?> results for: <?php echo '<strong>'.$word.'</strong>'; ?></span>
<span>Time taken: <?php echo $perm->getTime(); ?> seconds</span>
<div id="hide" class="toggle">-</div>
<div id="show" class="toggle" style="display: none;">+</div>
<ul>
	<span id="results">
	<?php
	foreach ($perm->getPerms() as $value) 
	{
		echo '<li>'.$value.'</li>';
	}
	?>
	</span>
</ul>
		
<?php include('includes/footer.php'); ?>