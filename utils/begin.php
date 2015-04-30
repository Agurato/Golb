<?php
	function beginHTML($title, $css){
		echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>'.$title.'</title>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
		<link href="'.$css.'" type="text/css" rel="stylesheet" />
	</head>
	<body>
';
	}

	function endHTML(){
		echo '	</body>
</html>';
	}

	// function beginSession(){
	// 	if(empty($_GET['deconnect'])){
	// 		$_GET['deconnect']='false';
	// 	}
	// 	if($_GET['deconnect'] != 'true'){
	// 		session_start();
	// 	}
	// 	else{
	// 		session_start();
	// 		session_destroy();
	// 		unset($_SESSION['login']);
	// 	}
	// 	if(!empty($_SESSION["login"])){
	// 	}
	// 	else{
	// 		if(!empty($_POST)){
	// 			if(!empty($_POST['login'])&&(!empty($_POST['password']))){
	// 				$_SESSION['login']=$_POST['login'];
					
	// 			}	
	// 		}
	// 	}
	// }
	function beginSession(){
		session_start();
		if(!empty($_SESSION["login"])){
		}
		else{
			if(!empty($_POST)){
				if(!empty($_POST['login'])&&(!empty($_POST['password']))){
					$_SESSION['login']=$_POST['login'];
					
				}	
			}
		}
	}
?>
