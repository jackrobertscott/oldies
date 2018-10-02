<h3>ERRORS FOUND</h3>
<?php 
foreach($errors as $e){
	echo '<p>- ' . $e . '</p>';
}
?>
<script>
    $(document).ready(function(){
        $(".desc").css("background-color", "#feccd7");
    });
</script>