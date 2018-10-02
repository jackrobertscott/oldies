<?php
session_start();
require($_SESSION['DB_CONNECT']);
require($_SESSION['ASSETS'].'classes/Base.class.php');
require($_SESSION['ASSETS'].'classes/Link.class.php');
$link = new Link($_SESSION['TABLE_FRIENDS'], $_POST['Id']);
return json_encode($link->getErrors());
?>