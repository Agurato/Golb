<?php
	include('begin.php');
	include('util.inc.php');
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
		$linkDB = mysqli_connect($_GET["server"], $_GET["user"], $_GET["pw"], $_GET["db"]);

		$insertQuery = 
		'INSERT INTO `mark` (`score`, `author`, `postID`)
			VALUES ("'.$score.'", "'.$_SESSION["login"].'", "'.$_GET["postID"].'");';

		// If the query didn't work (there is already a score for this author ad this post), we update the score
		if(! mysqli_query($linkDB, $insertQuery)) {
			$updateQuery =
			'UPDATE `mark` SET `score`='.$score.' WHERE `author`="'.$_SESSION["login"].'" AND `postID`="'.$_GET["postID"].'";';
			mysqli_query($linkDB, $updateQuery);
		}

		header('Location: ../index.php');
	}
	else {
		header('Location: ../index.php?postID='.$_GET["postID"].'&error='.$error.'#changeScoreModal');
	}

	endHTML();
?>