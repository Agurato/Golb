<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();

	$newPost = true;
	$error = "";

	// If the link and title have been sent
	if(empty($_POST["postTitle"])) {
		$error .= "1";
		$newPost = false;
	}
	if(empty($_POST["postLink"])) {
		$error .= "2";
		$newPost = false;
	}
	if(empty($_POST["postCat"])) {
		$error .= "3";
		$newPost = false;
	}

	if($newPost) {
		echo "Tout est ok !<br />";
		$linkDB = mysqli_connect($_GET["server"], $_GET["user"], $_GET["pw"], $_GET["db"]);

		$insertQuery = 
		'INSERT INTO `post` (`link`, `title`, `description`, `author`, `cat_html`, `cat_css`, `cat_php`, `cat_sql`)
			VALUES ("'.$_POST["postLink"].'", "'.$_POST["postTitle"].'", "'.$_POST["postDesc"].'", "'.$_SESSION["login"].'", ';

		if(in_array("html", $_POST["postCat"])) {
			$insertQuery .= '1, ';
		} else {
			$insertQuery .= '0, ';
		}
		if(in_array("css", $_POST["postCat"])) {
			$insertQuery .= '1, ';
		} else {
			$insertQuery .= '0, ';
		}
		if(in_array("php", $_POST["postCat"])) {
			$insertQuery .= '1, ';
		} else {
			$insertQuery .= '0, ';
		}
		if(in_array("sql", $_POST["postCat"])) {
			$insertQuery .= '1';
		} else {
			$insertQuery .= '0';
		}

		$insertQuery .= ')';

		echo $insertQuery;
		mysqli_query($linkDB, $insertQuery);

		if(isset($_GET["page"])) {
			header('Location: ../'.$_GET["page"]); 
		}
		else {
			header('Location: ../index.php');
		}
	}
	else {
		echo "error = ".$error."<br />";
		if(isset($_GET["page"])) {
			header('Location: ../'.$_GET["page"].'?error='.$error.'#newPostModal'); 
		}
		else {
			header('Location: ../index.php?error='.$error.'#newPostModal');
		}
	}

	endHTML();
?>