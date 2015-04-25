<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();
	
	session_destroy();
	unset($_SESSION['login']);
	if(isset($_GET["page"])) {
		header('Location: '.$_GET["page"]);
	}
	else {
		header('Location: index.php');
	}
	endHTML();
?>