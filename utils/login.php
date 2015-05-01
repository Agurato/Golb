<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');

	$connected = false;

	// If a login and password have been sent
	if(isset($_POST["login"]) && isset($_POST["password"])) {

		// We open the file containing the list of all users
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					if(strtolower($data[0]) == strtolower($_POST["login"])) {
						// Put the real case on the username
						$_POST["login"] = $data[0];
						// If the password is ok
						if(password_verify($_POST["password"], $data[1])) {
							$connected = true;
							// We start the session
							beginSession();

							// We stock usefull variables in $_SESSION
							$_SESSION["email"] = $data[2];
							$_SESSION["userLevel"] = $data[3];
							$_SESSION["signature"] = $data[4];
							// Redirection to the page where he was when he clicked "Se connecter"
							if(isset($_GET["page"])) {
								header('Location: ../'.$_GET["page"]); 
							}
							else {
								header('Location: ../index.php');
							}
						}
					}
				}
			}
			fclose($handle);
		}
	}

	// If he made it here, he is redirected with errors to display
	if(! $connected) {
		if(isset($_GET["page"])) {
			header('Location: ../'.$_GET["page"].'?error=true#loginModal'); 
		}
		else {
			header('Location: ../index.php?error=true#loginModal');
		}
	}

	endHTML();
?>