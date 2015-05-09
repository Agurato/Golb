<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$actionAvailable = true;

	if($_SESSION["userLevel"] < 3) {
		header('Location: ../index.php');
	}

	if(empty($_GET["name"])) {
		header('Location: ../admin.php');
	}

	$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

	$postsList = array();
	$postsQuery = 'SELECT * FROM `is_used` WHERE `categoryName` = "'.$_GET["name"].'";';
	$postsResult = mysqli_query($linkDB, $postsQuery);
	for($i=0 ; $i<mysqli_num_rows($postsResult) ; $i++) {
		$row = mysqli_fetch_assoc($postsResult);
		$postsList[] = $row["postID"];
	}
	print_r($postsList).'<br />';

	$postsToDelete = array();
	foreach ($postsList as $key => $value) {
		$postsQuery = 'SELECT * FROM `is_used` WHERE `postID` = '.$value.';';
		$postsResult = mysqli_query($linkDB, $postsQuery);
		if(mysqli_num_rows($postsResult) == 1) {
			$postsToDelete[] = $value;
		}
	}
	print_r($postsToDelete).'<br />';

	$deleteQuery = 'DELETE FROM `is_used` WHERE `categoryName` = "'.$_GET["name"].'";';
	mysqli_query($linkDB, $deleteQuery);

	$deleteQuery = 'DELETE FROM `category` WHERE `name` = "'.$_GET["name"].'";';
	mysqli_query($linkDB, $deleteQuery);

	foreach ($postsToDelete as $key => $value) {
		$deleteQuery = 'DELETE FROM `post` WHERE `id` = '.$value.';';
		mysqli_query($linkDB, $deleteQuery);
	}
	
	mysqli_close($linkDB);

	header('Location: ../admin.php');

	endHTML();
?>