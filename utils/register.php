<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');

	// If all the infos have been sent
	if(isset($_POST["usernameSignup"]) && isset($_POST["passwordSignup1"]) && isset($_POST["passwordSignup2"]) && isset($_POST["emailSignup"])) {
		$register = true;
		$error = "";

		$mail = $_POST["emailSignup"];

		// INVALID USERNAME
		// If there is any ';' => ERROR
		if(ctype_alnum($_POST["usernameSignup"]) == false) {
			$register = false;
			$error .= "2";
		}

		// INVALID EMAIL
		// If there is any ';' => ERROR
		if(strpos($mail, ':') !== false) {
			$register = false;
			$error .= "4";
		}
		// If no ';' and no '@' => ERROR
		elseif(strpos($mail, '@') === false) {
			$register = false;
			$error .= "4";
		}
		// If no ';' but '@', but no '.' after '@' => ERROR
		elseif(strpos(explode('@', $mail)[1], '.') === false) {
			$register = false;
			$error .= "4";
		}

		// INCORRECT PASSWORDS
		// If they are not the same => ERROR
		if($_POST["passwordSignup1"] != $_POST["passwordSignup2"]) {
			$register = false;
			$error .= "5";
		}

		// We open the file
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					// If the username is already used
					if(strtolower($data[0]) == strtolower($_POST["usernameSignup"])) {
						$register = false;
						$error .= "1";
					}
					// If the mail is already used
					if(strtolower($data[2]) == strtolower($mail)) {
						$register = false;
						$error .= "3";
					}
				}
			}
			fclose($handle);
		}

		// If we are allowed to register
		if($register) {
			// We create the array containing the new user's infos (username, password, e-mail, user access, signature)
			$newUser = array($_POST["usernameSignup"], password_hash($_POST["passwordSignup1"], PASSWORD_BCRYPT), $mail, 1, "");

			// We open the file and add the user to it
			$file = fopen("../users/accounts.csv", "a");
			fputcsv($file, $newUser, ":");
			fclose($file);

			// We create his own directory for his profile page
			// $userDir = "../users/".strtolower($_POST["usernameSignup"]);
			// mkdir($userDir);

			// $file = fopen($userDir."/.userAccount.csv", "w");
			// fputcsv($file, $newUser, ":");
			// fclose($file);

			// copy("../users/usersIndex.php", $userDir."/index.php");
			// copy("../img/user.png", $userDir."/profilePic.png");

			// Redirection
			if(isset($_GET["page"])) {
				header('Location: ../'.$_GET["page"]);
			}
			else {
				header('Location: ../index.php#loginModal');
			}
		}

		// If there has been errors
		if($error != "") {
			if(isset($_GET["page"])) {
				header('Location: ../'.$_GET["page"].'?error='.$error.'#registerModal');
			}
			else {
				header('Location: ../index.php?error='.$error.'#registerModal');
			}
		}
		
	}
	endHTML();
?>