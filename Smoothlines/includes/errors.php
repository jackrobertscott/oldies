<div class="text corner" style="background-color: #f7e7ed;">
	<ul>
	<u style="color: #942d3a;">Errors Found:</u><br>
	<?php 
	foreach($errors as $e){
		echo '<br><li style="font-size: 12px;color: #942d3a;">- ' . $e . '</li>';
	}
	?>
	</ul>
</div>