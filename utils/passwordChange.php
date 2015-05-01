<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$change = true;
	$error = "";

	$lineCounter = 0;
	$lineToEdit = 0;

	// If the 3 passwords have been send (1 = actual, 2 = new, 3 = new verification)
	if(isset($_POST["passwordChange1"]) && isset($_POST["passwordChange2"]) && isset($_POST["passwordChange3"])) {

		// If the new passwords are different, ERROR
		if($_POST["passwordChange2"] != $_POST["passwordChange3"]) {
			$change = false;
			$error .= "2";
		}

		// We open the accounts file
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					// We check each line to find the one with the good username
					if(strtolower($data[0]) == strtolower($_SESSION["login"])) {
						// If the actual password is incorrect, ERROR
						if(! password_verify($_POST["passwordChange1"], $data[1])) {
							$change = false;
							$error .= "1";
						}
						// We get the line number in the file
						$lineToEdit = $lineCounter;
					}
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		// If no error happened for the moment
		if($change) {
			// We re-open the file
			if(($fileLines = file("../users/accounts.csv")) !== false) {
				// We get the array containing all the infos about the current user
				$userInfos = explode(":", $fileLines[$lineToEdit]);

				if(count($userInfos) == 5) {
					// We change the password
					$userInfos[1] = password_hash($_POST["passwordChange2"], PASSWORD_BCRYPT);
					// We change the array containing all the users
					$fileLines[$lineToEdit] = implode(":", $userInfos);
					// We re-write in the file
					file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);

					// Redirection
					if(isset($_GET["page"])) {
						header('Location: ../'.$_GET["page"]);
					}
					else {
						header('Location: ../index.php');
					}
				}
			}
		}

		// If there has been errors
		if($error != "") {
			if(isset($_GET["page"])) {
				header('Location: ../'.$_GET["page"].'?error='.$error.'#chgPasswordModal');
			}
			else {
				header('Location: ../index.php?error='.$error.'#chgPasswordModal');
			}
		}
	}

	endHTML();
?>