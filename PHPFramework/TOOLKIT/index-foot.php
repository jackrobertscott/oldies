<?php
/*
*****************************************
Place where want page navigation to go. 
NOTE: Must compliment index-head.php
*****************************************
*/
?>
<?php if(!empty($pages) && $number_of_results > 0): ?>
	<span class="inde-foot">
	<?php
	$url_len = strlen($_SESSION['url1']);
	$and_pos = strpos($_SESSION['url1'], '&screen=');
	$que_pos = strpos($_SESSION['url1'], '?screen=');
	if($and_pos !== false)
	{ 
		$_SESSION['url1'] = substr($_SESSION['url1'], 0, ($url_len - ($url_len - $and_pos))); 
	}elseif($que_pos !== false)
	{
		$_SESSION['url1'] = substr($_SESSION['url1'], 0, ($url_len - ($url_len - $que_pos)));
	}
	$is_get = strpos($_SESSION['url1'], '?'); //This must be after string removal (lines above)
	if($screen > 0)
	{
		if($is_get == false)
		{
	  		$url = $_SESSION['url1'] . "?screen=" . ($screen - 1);
	  		echo "<a href=\"$url\">Previous</a> | ";
	  	}else{
	  		$url = $_SESSION['url1'] . "&screen=" . ($screen - 1);
	  		echo "<a href=\"$url\">Previous</a> | ";
	  	}
	}
	for ($i = 1; $i < ($pages + 1); $i++)
	{
		if($is_get == false)
		{
			$url = $_SESSION['url1'] . "?screen=" . ($i - 1);
		}else{
			$url = $_SESSION['url1'] . "&screen=" . ($i - 1);
		}
		if(($i >= $screen - 1) && ($i <= $screen + 3))
		{
			if($screen == $pages - 1 && $pages == $i)
			{
				if($i == $screen + 1)
				{
					echo "<a href=\"$url\"><u>$i</u></a> ";
				}else{
					echo "<a href=\"$url\">$i</a> ";
				}
			}else{
				if($i == $screen + 1)
				{
					echo "<a href=\"$url\"><u>$i</u></a> | ";
				}else{
					echo "<a href=\"$url\">$i</a> | ";
				}
			}
		}
	}
	if($screen < ($pages - 1))
	{
		if($is_get == false)
		{
			$url = $_SESSION['url1'] . "?screen=" . ($screen + 1);
	  		echo " <a style=\"text-align:center;\" href=\"$url\">Next</a>";
		}else{
			$url = $_SESSION['url1'] . "&screen=" . ($screen + 1);
	  		echo "| <a style=\"text-align:center;\" href=\"$url\">Next</a>";
		}
	}
	?>
	</span>
<?php endif ; ?>