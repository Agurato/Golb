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
		if(strpos($_POST["username"], ';') !== false) {
			$register = false;
			$error .= "2";
		}

		// INVALID EMAIL
		// If there is any ';' => ERROR
		if(strpos($mail, ';') !== false) {
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

		// INVALID EMAIL OR USERNAME (ALREADY USED)
		$usernameAvailable = true;
		$emailAvailable = true;

		if(($handle = fopen("accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ";")) !== false) {
				if(count($data) == 3) {
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
			$newUser = array($_POST["username"], password_hash($_POST["password1"], PASSWORD_BCRYPT), $mail);

			$file = fopen("accounts.csv", "a");

			fputcsv($file, $newUser, ";");

			fclose($file);

			echo '<meta http-equiv="refresh" content="0;URL=index.php#loginModal" />';
		}

		if($error != "") {
			echo '<meta http-equiv="refresh" content="0;URL=index.php?error='.$error.'#registerModal" /> ';
		}
		
	}
	endHTML();
?>