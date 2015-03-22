<?php
	function beginHTML($title, $css){
		echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>'.$title.'</title>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
		<link href="'.$css.'" type="text/css" rel="stylesheet"></link>
	</head>
	<body>
';
	}

	function endHTML(){
		echo '	</body>
</html>';
	}
?>
