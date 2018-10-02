<?php if(!empty($errors)): ?>
<ul>
	<?php 
	echo ERROR_MESSAGE;
	foreach($errors as $e){
		echo '<li>- ' . $e . '</li>';
	}
	?>
</ul>
<?php endif; ?>