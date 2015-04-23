<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();
	
	session_destroy();
	unset($_SESSION['login']);
	echo '<meta http-equiv="refresh" content="0;URL='.$_GET["page"].'" /> ';
	endHTML();
?>