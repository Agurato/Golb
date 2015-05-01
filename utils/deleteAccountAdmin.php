<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$delete = true;
	$lineCounter = 0;
	$lineToEdit = 0;
	$error = "";

	// If the password and the account name are there
	if(isset($_POST["passwordDelete"]) && isset($_GET["account"])) {

		// The admin can't delete his own account with the administration page
		if(strtolower($_SESSION["login"]) == strtolower($_GET["account"])) {
			$delete = false;
		}

		// We open the list of account
		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				// We get the line corresponding to the name account send with $_GET
				if(strtolower($data[0]) == strtolower($_GET["account"])) {
					// We check the admin password
					if(($adminFile = fopen("../users/".strtolower($_SESSION['login'])."/.userAccount.csv", "r")) !== false) {
						if(($admin = fgetcsv($adminFile, 1000, ":")) !== false) {
							if(! password_verify($_POST["passwordDelete"], $admin[1])) {
								$delete = false;
								$error .= "1";
							}
						}
						fclose($adminFile);
					}
					
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
				// We delete the line corresponding to the account and reassemble all the lines of the file
				unset($fileLines[$lineToEdit]);
				$fileLines = array_values($fileLines);

				// We re-write the "accounts.csv" file
				file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);
				// We delete the profile page of the user
				rmdir_recursive("../users/".strtolower($_GET["account"]));
				// Redirection
				header('Location: ../admin.php');
			}
		}

		// If there's an error
		if($error != "") {
			header('Location: ../admin.php?account='.$_GET["account"].'&error='.$error.'#delAccountModal');
		}

		header('Location: ../admin.php');
	}

	// To delete the directory of the user (recursively)
	function rmdir_recursive($dir) {
		$filesList = array_diff(scandir($dir), array('.', '..'));
		foreach ($filesList as $file) {
			if(is_dir($dir."/".$file)) {
				rmdir_recursive($dir."/".$file);
			}
			else {
				unlink($dir."/".$file);
			}
		}
		rmdir($dir);
	}

	endHTML();
?>