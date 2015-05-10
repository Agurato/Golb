<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$delete = true;
	$lineCounter = 0;
	$error = "";

	// If the password has been sent
	if(isset($_POST["passwordDelete"])) {
		// We open the accounts file
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					if(strtolower($data[0]) == strtolower($_SESSION["login"])) {
						if(! password_verify($_POST["passwordDelete"], $data[1])) {
							$delete = false;
							$error .= "1";
						}
						// We get the line number corresponding to the user
						$lineToEdit = $lineCounter;
					}
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		if($delete) {
			if(($fileLines = file("../users/accounts.csv")) !== false) {
				if(isset($_POST["deleteOption"])) {
					// If the option to delete all posts is selected
					if($_POST["deleteOption"] == "deleteAll") {
						$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);
						// We delete the marks the user gave
						$deleteQuery = 'DELETE FROM `mark` WHERE `author` = "'.$_SESSION["login"].'";';
						mysqli_query($linkDB, $deleteQuery);

						// We delete the comments he wrote
						$deleteQuery = 'DELETE FROM `comment` WHERE `author` = "'.$_SESSION["login"].'";';
						mysqli_query($linkDB, $deleteQuery);

						// We delete the marks of the posts he wrote
						$deleteQuery = 'DELETE FROM `mark` WHERE `postID` IN (SELECT `id` FROM `post` WHERE `author` = "'.$_SESSION["login"].'");';
						mysqli_query($linkDB, $deleteQuery);

						// We delete the comments of the posts he wrote
						$deleteQuery = 'DELETE FROM `comment` WHERE `postID` IN (SELECT `id` FROM `post` WHERE `author` = "'.$_SESSION["login"].'");';
						mysqli_query($linkDB, $deleteQuery);

						// We delete the posts he wrote
						$deleteQuery = 'DELETE FROM `post` WHERE `author` = "'.$_SESSION["login"].'";';
						mysqli_query($linkDB, $deleteQuery);

						mysqli_close($linkDB);
					}
				}

				// We delete the corresponding line & re-assemble the array of lines
				unset($fileLines[$lineToEdit]);
				$fileLines = array_values($fileLines);

				// We re-write in the file the changed array
				file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);
				// He is disconnected
				header('Location: disconnect.php');
			}
		}

		if($error != "") {
			header('Location: ../users/'.strtolower($_SESSION["login"]).'?error='.$error.'#delAccountModal');
		}
	}

	endHTML();
?>