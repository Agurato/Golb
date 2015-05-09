<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$actionAvailable = true;
	$error = "";

	$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);

	// If the name is empty, error
	if(empty($_POST["catName"])) {
		$error .= "1";
		$actionAvailable = false;
	}
	// Else, if the category already exists, error
	else {
		$searchResult = mysqli_query($linkDB, 'SELECT * FROM `category`;');
		for($i=0 ; $i<mysqli_num_rows($searchResult) ; $i++) {
			if(strtolower($_POST["catName"]) == strtolower(mysqli_fetch_assoc($searchResult)["name"])) {
				$error .= "2";
				$actionAvailable = false;
			}
		}
	}
	// If there is no action defined, error
	if(empty($_GET["action"])) {
		$error .= "3";
		$actionAvailable = false;
	}

	if($actionAvailable) {
		if($_GET["action"] == "add") {
			$insertQuery = 'INSERT INTO `category` (`name`) VALUES ("'.$_POST["catName"].'");';
			echo $insertQuery;
			mysqli_query($linkDB, $insertQuery);
		}
		else if($_GET["action"] == "delete") {

		}
		mysqli_close($linkDB);
		header('Location: ../admin.php');
	}
	else {
		echo "error = ".$error."<br />";
		mysqli_close($linkDB);

		header('Location: ../admin.php?error='.$error.'#addCategoryModal');
	}

	endHTML();
?>