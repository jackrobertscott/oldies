<?php
session_start();
require($_SESSION['DB_CONNECT']);
require($_SESSION['ASSETS'].'classes/Base.class.php');
require($_SESSION['ASSETS'].'classes/Post.class.php');
$post = new Post($_SESSION['TABLE_POSTS']);
$postExtras = array(
	"Location" => $_POST['Location'],
	"Start" => $_POST['StartDate'].' '.$_POST['StartTime'],
	"End" => $_POST['EndDate'].' '.$_POST['EndTime'],
	"Privacy" => $_POST['Privacy']
);
if(empty($_POST['StartDate']))
{
	date_default_timezone_set($_SESSION['TIMEZONE']);
	$postExtras['Start'] = date("Y-m-d H:i:s", time());
}
$post->create($_POST['Message'], $postExtras);
return json_encode($post->getErrors());
?>