<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$delete = true;
	$lineCounter = 0;
	$lineToEdit = 0;
	$error = "";

	if(isset($_POST["passwordDelete"]) && isset($_GET["account"])) {

		if(strtolower($_SESSION["login"]) == strtolower($_GET["account"])) {
			$delete = false;
		}

		if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				echo "Line n°".$lineCounter."<br />";
				if(strtolower($data[0]) == strtolower($_GET["account"])) {

					if(($adminFile = fopen("../users/".strtolower($_SESSION['login'])."/.userAccount.csv", "r")) !== false) {
						if(($admin = fgetcsv($adminFile, 1000, ":")) !== false) {
							if(! password_verify($_POST["passwordDelete"], $admin[1])) {
								echo '<p>Mot de passe pas ok</p>';
								$delete = false;
								$error .= "1";
							}
							else {
								echo '<p>Mot de passe ok</p>';
							}
						}
						fclose($adminFile);
					}
					
					$lineToEdit = $lineCounter;
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		if($delete) {
			if(($fileLines = file("../users/accounts.csv")) !== false) {
				unset($fileLines[$lineToEdit]);
				$fileLines = array_values($fileLines);

				file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);
				rmdir_recursive("../users/".strtolower($_GET["account"]));

				header('Location: ../admin.php');
			}
		}

		if($error != "") {
			header('Location: ../admin.php?account='.$_GET["account"].'&error='.$error.'#delAccountModal');
		}

		header('Location: ../admin.php');
	}

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