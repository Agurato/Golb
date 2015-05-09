<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$newPost = true;
	$error = "";

	if(! empty($_SESSION["userLevel"])) {
		if($_SESSION["userLevel"] < 2) {
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

	if($newPost) {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

		$url = $_POST["postLink"];
		if(strpos($url, "://") === false) {
			$url = "http://".$url;
		}

		$insertQuery = 
		'INSERT INTO `post` (`link`, `title`, `description`, `author`)
			VALUES ("'.$url.'", "'.$_POST["postTitle"].'", "'.$_POST["postDesc"].'", "'.$_SESSION["login"].'");';

		echo $insertQuery.'<br />';
		mysqli_query($linkDB, $insertQuery);

		$lastPost = 0;
		$lastPostQuery = "SELECT * FROM `post`;";
		$lastPostResult = mysqli_query($linkDB, $lastPostQuery);
		for($i=0 ; $i<mysqli_num_rows($lastPostResult) ; $i++) {
			$row = mysqli_fetch_assoc($lastPostResult);
			$lastPost = $row["id"];
		}

		foreach ($_POST["postCat"] as $key => $value) {
			$insertQuery = 
			'INSERT INTO `is_used` (`postID`, `categoryName`)
				VALUES ('.$lastPost.', "'.$value.'");';

			echo $insertQuery.'<br />';
			mysqli_query($linkDB, $insertQuery);
		}

		mysqli_close($linkDB);

		if(isset($_GET["page"])) {
			header('Location: ../'.$_GET["page"]); 
		}
		else {
			header('Location: ../index.php');
		}
	}
	else {
		echo "error = ".$error."<br />";
		if(isset($_GET["page"])) {
			header('Location: ../'.$_GET["page"].'?error='.$error.'#newPostModal'); 
		}
		else {
			header('Location: ../index.php?error='.$error.'#newPostModal');
		}
	}

	endHTML();
?>