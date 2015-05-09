<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$newPost = true;
	$error = "";

	if(! empty($_SESSION["userLevel"])) {
		if($_SESSION["userLevel"] < 2 ) {
			header('Location: index.php');
		}
	}

	if(! empty($_GET["id"])) {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

		$checkResult = mysqli_query($linkDB, 'SELECT * FROM `post` WHERE `id` = '.$_GET["id"].';');
		if(! empty($_SESSION["login"]) && ! empty($_SESSION["userLevel"])) {
			if(($_SESSION["login"] != mysqli_fetch_assoc($checkResult)["author"]) && ($_SESSION["userLevel"] < 3)) {
				header('Location: ../index.php');
			}
		}

		$deleteQuery = 'DELETE FROM `is_used` WHERE `postID` = '.$_GET["id"].';';
		echo $deleteQuery.'<br />';
		mysqli_query($linkDB, $deleteQuery);

		$deleteQuery = 'DELETE FROM `mark` WHERE `postID` = '.$_GET["id"].';';
		echo $deleteQuery.'<br />';
		mysqli_query($linkDB, $deleteQuery);

		$deleteQuery = 'DELETE FROM `comment` WHERE `postID` = '.$_GET["id"].';';
		echo $deleteQuery.'<br />';
		mysqli_query($linkDB, $deleteQuery);

		$deleteQuery = 'DELETE FROM `post` WHERE `id` = '.$_GET["id"].';';
		echo $deleteQuery.'<br />';
		mysqli_query($linkDB, $deleteQuery);

		mysqli_close($linkDB);

	}

	header('Location: ../index.php');

	endHTML();
?>