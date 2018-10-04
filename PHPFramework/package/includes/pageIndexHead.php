<?php
$_SESSION['url1'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$rows_per_page = 10;
if(!$mysqli->query($query)){
	$this->errors[] = $this->prefixErr . $mysqli->error;
}else{
	$number_of_results = $mysqli->affected_rows;
	if($number_of_results != 0){
		$pages = ceil($number_of_results / $rows_per_page);
	}
}
$screen = $_GET['screen'];
if(!isset($screen)){ 
	$screen = 0;
}
$start = $screen * $rows_per_page;
$query .= " LIMIT $start, $rows_per_page";
?>