<?php
	include_once('begin.php');
	include_once('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$lineCounter = 0;
	$lineToEdit = 0;

	// If you are not allowed, redirection
	if ($_SESSION["userLevel"] < 3) {
		header('Location: ../index.php');
	}

	if (empty($_GET["user"]) || empty($_GET["level"])) {
		header('Location: ../admin.php');
	}

	// We open the list of account
	if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
		while(($data = fgetcsv($handle, 1000, ":")) !== false) {
			// We get the line corresponding to the name account sent with $_GET
			if(strtolower($data[0]) == strtolower($_GET["user"])) {
				
				// We save the line number in accounts.csv corresponding to the account to delete
				$lineToEdit = $lineCounter;
			}
			$lineCounter ++;
		}
		fclose($handle);
	}

	// We re-open the file
	if(($fileLines = file("../users/accounts.csv")) !== false) {
		// We get the array containing all the infos about the current user
		$userInfos = explode(":", $fileLines[$lineToEdit]);

		if(count($userInfos) == 5) {
			// We change the password
			if($_GET["level"] > $userInfos[3]) {
				$userInfos[3] = $_GET["level"];
			}
			// We change the array containing all the users
			$fileLines[$lineToEdit] = implode(":", $userInfos);
			// We re-write in the file
			file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);
		}
	}

	// Redirection
	header('Location: ../admin.php');

	endHTML();
?>