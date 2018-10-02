<?php if(!empty($errors)): ?>
<ul class="pf-14 fs-13 error">
	<?php 
	echo ERROR_MESSAGE;
	foreach($errors as $value)
	{
		echo '<li class="mt-7">- '.$value.'</li>';
	}
	?>
</ul>
<?php endif; ?>