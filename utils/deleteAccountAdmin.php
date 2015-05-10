<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$delete = true;
	$lineCounter = 0;
	$lineToEdit = 0;
	$error = "";

	// If you are not allowed, redirection
	if($_SESSION["userLevel"] < 3) {
		header('Location: ../index.php');
	}

	// If the password and the account name are there
	if(isset($_POST["passwordDelete"]) && isset($_GET["account"])) {

		// We check the admin password
		if(($adminFile = fopen("../users/accounts.csv", "r")) !== false) {
			while(($admin = fgetcsv($adminFile, 1000, ":")) !== false) {
				if(strtolower($admin[0]) == strtolower($_SESSION["login"])) {
					if(! password_verify($_POST["passwordDelete"], $admin[1])) {
						$delete = false;
						$error .= "1";
					}
				}
			}
			fclose($adminFile);
		}

		// The admin can't delete his own account with the administration page
		if(strtolower($_SESSION["login"]) == strtolower($_GET["account"])) {
			$delete = false;
			$error .= "2";
		}

		// We open the list of account
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				// We get the line corresponding to the name account send with $_GET
				if(strtolower($data[0]) == strtolower($_GET["account"])) {
					
					// We save the line number in accounts.csv corresponding to the account to delete
					$lineToEdit = $lineCounter;
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		// If no errors happened
		if($delete) {
			if(($fileLines = file("../users/accounts.csv")) !== false) {

				// If the option to delete all posts is selected
				if($_POST["deleteOption"] == "deleteAll") {
					$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);
					// We delete the marks the user gave
					$deleteQuery = 'DELETE FROM `mark` WHERE `author` = "'.$_GET["account"].'";';
					echo $deleteQuery.'<br />';
					mysqli_query($linkDB, $deleteQuery);

					// We delete the comments he wrote
					$deleteQuery = 'DELETE FROM `comment` WHERE `author` = "'.$_GET["account"].'";';
					echo $deleteQuery.'<br />';
					mysqli_query($linkDB, $deleteQuery);

					// We delete the marks of the posts he wrote
					$deleteQuery = 'DELETE FROM `mark` WHERE `postID` IN (SELECT `id` FROM `post` WHERE `author` = "'.$_GET["account"].'");';
					echo $deleteQuery.'<br />';
					mysqli_query($linkDB, $deleteQuery);

					// We delete the comments of the posts he wrote
					$deleteQuery = 'DELETE FROM `comment` WHERE `postID` IN (SELECT `id` FROM `post` WHERE `author` = "'.$_GET["account"].'");';
					echo $deleteQuery.'<br />';
					mysqli_query($linkDB, $deleteQuery);

					// We delete the posts he wrote
					$deleteQuery = 'DELETE FROM `post` WHERE `author` = "'.$_GET["account"].'";';
					echo $deleteQuery.'<br />';
					mysqli_query($linkDB, $deleteQuery);

					mysqli_close($linkDB);
				}

				// We delete the line corresponding to the account and reassemble all the lines of the file
				unset($fileLines[$lineToEdit]);
				$fileLines = array_values($fileLines);

				// We re-write the "accounts.csv" file
				file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);
				// Redirection
				// header('Location: ../admin.php');
			}
		}

		// If there's an error
		if($error != "") {
			// header('Location: ../admin.php?account='.$_GET["account"].'&error='.$error.'#delAccountModal');
		}

		// header('Location: ../admin.php');
	}

	endHTML();
?>