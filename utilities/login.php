<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');

	$connected = false;

	if(isset($_POST["login"]) && isset($_POST["password"])) {

		if(($handle = fopen("users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 4) {
					if(strtolower($data[0]) == strtolower($_POST["login"])) {
						// Put the real case on the username
						$_POST["login"] = $data[0];
						if(password_verify($_POST["password"], $data[1])) {
							$connected = true;
							beginSession();
							$_SESSION["userLevel"] = $data[3];
							if(isset($_GET["page"])) {
								header('Location: '.$_GET["page"]); 
							}
							else {
								header('Location: index.php');
							}
						}
					}
				}
			}
			fclose($handle);
		}
	}

	if(! $connected) {
		if(isset($_GET["page"])) {
			header('Location: '.$_GET["page"].'?error=true#loginModal'); 
		}
		else {
			header('Location: index.php?error=true#loginModal');
		}
	}

	endHTML();
?>