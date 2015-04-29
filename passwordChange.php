<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();

	$change = true;
	$error = "";

	$lineCounter = 0;
	$lineToEdit = 0;

	if(isset($_POST["passwordChange1"]) && isset($_POST["passwordChange2"]) && isset($_POST["passwordChange3"])) {

		if($_POST["passwordChange2"] != $_POST["passwordChange3"]) {
			$change = false;
			$error .= "2";
		}

		if(($handle = fopen("users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					if(strtolower($data[0]) == strtolower($_SESSION["login"])) {
						if(! password_verify($_POST["passwordChange1"], $data[1])) {
							$change = false;
							$error .= "1";
						}
						$lineToEdit = $lineCounter;
					}
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		if($change) {
			if(($fileLines = file("users/accounts.csv")) !== false) {
				$userInfos = explode(":", $fileLines[$lineToEdit]);

				if(count($userInfos) == 5) {
					$userInfos[1] = password_hash($_POST["passwordChange2"], PASSWORD_BCRYPT);
					$fileLines[$lineToEdit] = implode(":", $userInfos);
					file_put_contents("users/accounts.csv", $fileLines, LOCK_EX);

					if(isset($_GET["page"])) {
						header('Location: '.$_GET["page"]);
					}
					else {
						header('Location: index.php');
					}
				}
			}
		}

		if($error != "") {
			if(isset($_GET["page"])) {
				header('Location: '.$_GET["page"].'?error='.$error.'#chgPasswordModal');
			}
			else {
				header('Location: index.php?error='.$error.'#chgPasswordModal');
			}
		}
	}

	endHTML();
?>