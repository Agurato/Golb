<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();

	$delete = true;
	$lineCounter = 0;
	$error = "";

	if(isset($_POST["passwordDelete"])) {

		if(($handle = fopen("users/accounts.csv", "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					if(strtolower($data[0]) == strtolower($_SESSION["login"])) {
						if(! password_verify($_POST["passwordDelete"], $data[1])) {
							$delete = false;
							$error .= "1";
						}
						$lineToEdit = $lineCounter;
					}
				}
				$lineCounter ++;
			}
			fclose($handle);
		}

		if($delete) {
			if(($fileLines = file("users/accounts.csv")) !== false) {
				unset($fileLines[$lineToEdit]);
				$fileLines = array_values($fileLines);

				file_put_contents("users/accounts.csv", $fileLines, LOCK_EX);
				rmdir_recursive("users/".strtolower($_SESSION["login"]));
				header('Location: disconnect.php');
			}
		}

		if($error != "") {
			header('Location: users/'.strtolower($_SESSION["login"]).'?error='.$error.'#delAccountModal');
		}
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