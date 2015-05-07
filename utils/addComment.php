<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$addComment = true;
	$error = "";

	// If the link and title have been sent
	if(empty($_POST["comment"])) {
		$error .= "1";
		$addComment = false;
	}
	else {
		if(strlen($_POST["comment"]) > 750) {
			$error .= "2";
			$addComment = false;
		}
	}

	if($addComment) {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

		$words = explode(" ", $_POST["comment"]);
		foreach ($words as $key => $value) {
			$split = str_split($value, 40);

			for($i=0 ; $i<count($split)-1 ; $i++) {
				$split[$i] .= '-';
			}
			array_splice($words, $key, 1, $split);
			// while(strlen($value) > 40) {
			// 	echo "<p>$value est long : ".strlen($value)."</p>";
			// 	array_splice($words, $key+$i, 1, substr($value, 0, 40).'-');
			// 	$value = substr($value, 42);
			// 	$i++;
			// }
		}

		$_POST["comment"] = implode(" ", $words);

		$insertQuery = 
		'INSERT INTO `comment` (`comment`, `author`, `postID`)
			VALUES ("'.$_POST["comment"].'", "'.$_SESSION["login"].'", "'.$_GET["postID"].'");';

		echo $insertQuery;
		mysqli_query($linkDB, $insertQuery);

		mysqli_close($linkDB);

		// header('Location: ../post.php?id='.$_GET["postID"]);
	}
	else {
		echo "error = ".$error."<br />";

		// header('Location: ../post.php?id='.$_GET["postID"].'&error='.$error.'#commentPostModal');
	}

	endHTML();
?>