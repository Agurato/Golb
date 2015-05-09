<?php
	include_once('begin.php');
	include_once('util.inc.php');
	include_once('globals.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$actionAvailable = true;
	$error = "";

	if($_SESSION["userLevel"] < 3) {
		header('Location: ../index.php');
	}

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

	if($actionAvailable) {

		// We create the new category
		$insertQuery = 'INSERT INTO `category` (`name`) VALUES ("'.$_POST["catName"].'");';
		echo $insertQuery;
		mysqli_query($linkDB, $insertQuery);

		// If we want to rename a category
		if(! empty($_POST["oldName"])) {
			$checkCatQuery = 'SELECT * FROM `category` WHERE `name` = "'.$_POST["oldName"].'";';
			// If the old category exists
			if(mysqli_num_rows(mysqli_query($linkDB, $checkCatQuery)) > 0) {
				// We move the posts from the old category to the new one
				$updateQuery = 'UPDATE `is_used` SET `categoryName` = "'.$_POST["catName"].'" WHERE `categoryName` = "'.$_POST["oldName"].'";';
				mysqli_query($linkDB, $updateQuery);
				// We delete the old category
				$deleteQuery = 'DELETE FROM `category` WHERE `name` = "'.$_POST["oldName"].'";';
				mysqli_query($linkDB, $deleteQuery);
			}

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