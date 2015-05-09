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

	// If the link and title have been sent
	if(empty($_POST["postTitle"])) {
		$error .= "1";
		$newPost = false;
	}
	if(empty($_POST["postLink"])) {
		$error .= "2";
		$newPost = false;
	}
	if(empty($_POST["postCat"])) {
		$error .= "3";
		$newPost = false;
	}
	if(empty($_POST["postID"])) {
		$newPost = false;
	}

	if($newPost) {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

		$url = $_POST["postLink"];
		if(strpos($url, "://") === false) {
			$url = "http://".$url;
		}

		$checkResult = mysqli_query($linkDB, 'SELECT * FROM `post` WHERE `id` = '.$_POST["postID"].';');
		if(! empty($_SESSION["login"]) && ! empty($_SESSION["userLevel"])) {
			if(($_SESSION["login"] != mysqli_fetch_assoc($checkResult)["author"]) && ($_SESSION["userLevel"] < 3)) {
				header('Location: ../index.php');
			}
		}

		$updateQuery = 'UPDATE `post` SET `title` = "'.$_POST["postTitle"].'" WHERE `id` = '.$_POST["postID"].';';
		mysqli_query($linkDB, $updateQuery);

		$updateQuery = 'UPDATE `post` SET `link` = "'.$_POST["postLink"].'" WHERE `id` = '.$_POST["postID"].';';
		mysqli_query($linkDB, $updateQuery);

		$updateQuery = 'UPDATE `post` SET `description` = "'.$_POST["postDesc"].'" WHERE `id` = '.$_POST["postID"].';';
		mysqli_query($linkDB, $updateQuery);

		$deleteQuery = 'DELETE FROM `is_used` WHERE `postID` = '.$_POST["postID"].';';
		mysqli_query($linkDB, $deleteQuery);

		foreach ($_POST["postCat"] as $key => $value) {
			$insertQuery = 
			'INSERT INTO `is_used` (`postID`, `categoryName`)
				VALUES ('.$_POST["postID"].', "'.$value.'");';

			mysqli_query($linkDB, $insertQuery);
		}

		mysqli_close($linkDB);

		if(isset($_POST["page"])) {
			if($_POST["page"] == 'post.php') {
				header('Location: ../'.$_POST["page"].'?id='.$_POST["postID"]); 
			}
			else {
				header('Location: ../index.php');
			}
		}
		else {
			header('Location: ../index.php');
		}
	}
	else {
		if(isset($_POST["page"])) {
			if($_POST["page"] == 'post.php') {
				header('Location: ../'.$_POST["page"].'?id='.$_POST["postID"].'&error='.$error.'#newPostModal');
			} 
			else {
				header('Location: ../index.php?error='.$error.'#newPostModal');
			}
		}
		else {
			header('Location: ../index.php?error='.$error.'#newPostModal');
		}
	}

	endHTML();
?>