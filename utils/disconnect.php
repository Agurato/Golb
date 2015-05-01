<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();
	
	// We destroy the session
	session_destroy();
	unset($_SESSION['login']);

	// Redirection
	if(isset($_GET["page"])) {
		header('Location: ../'.$_GET["page"]);
	}
	else {
		header('Location: ../index.php');
	}
	endHTML();
?>