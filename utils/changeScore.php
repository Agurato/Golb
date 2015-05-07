<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$changeScore = true;
	$error = "";

	// If the link and title have been sent
	if(empty($_POST["newScore"])) {
		$error .= "1";
		$changeScore = false;
	}
	else {
		if (is_numeric($_POST["newScore"])) {
			$score = round($_POST["newScore"]);

			if($score > 10) {
				$score = 10;
			}
			elseif ($score < 0) {
				$score = 0;
			}
		}
		else {
			$error .= "2";
			$changeScore = false;
		}
	}

	if(empty($_GET["postID"])) {
		$error .= "3";
		$changeScore = false;
	}

	if($changeScore) {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

		$insertQuery = 
		'INSERT INTO `mark` (`score`, `author`, `postID`)
			VALUES ("'.$score.'", "'.$_SESSION["login"].'", "'.$_GET["postID"].'");';

		// If the query didn't work (there is already a score for this author ad this post), we update the score
		if(! mysqli_query($linkDB, $insertQuery)) {
			$updateQuery =
			'UPDATE `mark` SET `score`='.$score.' WHERE `author`="'.$_SESSION["login"].'" AND `postID`="'.$_GET["postID"].'";';
			mysqli_query($linkDB, $updateQuery);
		}

		mysqli_close($linkDB);

		if(! empty($_GET["fromPost"])) {
			if($_GET["fromPost"] == "true") {
				header('Location: ../post.php?id='.$_GET["postID"]);
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

		if(! empty($_GET["fromPost"])) {
			if($_GET["fromPost"] == "true") {
				header('Location: ../post.php?id='.$_GET["postID"].'&error='.$error.'#changeScoreModal');
			}
			else {
				header('Location: ../index.php?id='.$_GET["postID"].'&error='.$error.'#changeScoreModal');
			}
		}
		else {
			header('Location: ../index.php?id='.$_GET["postID"].'&error='.$error.'#changeScoreModal');
		}
	}

	endHTML();
?>