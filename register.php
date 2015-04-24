<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');

	if(isset($_POST["username"]) && isset($_POST["password1"]) && isset($_POST["password2"]) && isset($_POST["email"])) {
		$register = true;
		$error = "";

		$mail = $_POST["email"];

		// INVALID USERNAME
		// If there is any ';' => ERROR
		if(strpos($_POST["username"], ':') !== false) {
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
		if($_POST["password1"] != $_POST["password2"]) {
			$register = false;
			$error .= "5";
		}

		if(($handle = fopen("users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 4) {
					if($data[0] == $_POST["username"]) {
						$register = false;
						$error .= "1";
					}
					if($data[2] == $mail) {
						$register = false;
						$error .= "3";
					}
				}
			}
			fclose($handle);
		}

		if($register) {
			$newUser = array($_POST["username"], password_hash($_POST["password1"], PASSWORD_BCRYPT), $mail, "1");

			$file = fopen("users/accounts.csv", "a");
			fputcsv($file, $newUser, ":");
			fclose($file);

			$userDir = "users/".strtolower($_POST["username"]);
			mkdir($userDir);
			copy("users/vincent/index.php", $userDir."/index.php");

			if(isset($_GET["page"])) {
				header('Location: '.$_GET["page"].'#loginModal');
			}
			else {
				header('Location: index.php#loginModal');
			}
		}

		if($error != "") {
			if(isset($_GET["page"])) {
				header('Location: '.$_GET["page"].'?error='.$error.'#registerModal');
			}
			else {
				header('Location: index.php?error='.$error.'#registerModal');
			}
		}
		
	}
	endHTML();
?>