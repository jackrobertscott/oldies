<?php
session_start();
require($_SESSION['DB_CONNECT']);
require($_SESSION['ASSETS'].'classes/Base.class.php');
require($_SESSION['ASSETS'].'classes/Post.class.php');
$post = new Post($_SESSION['TABLE_POSTS'], $_POST['Id']);
$post->deactivate();
return json_encode($post->getErrors());
?>