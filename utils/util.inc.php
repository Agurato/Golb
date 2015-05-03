<?php

	/* 
	Used only in admin.php
	Return the list of all users in a <table>
	*/
	function usersTable($accountsFile = "users/accounts.csv", $imgDir = "img/") {
		$result = '';
		$result .= '<table id="accountsList">';

		// Column names
		$result .= '<tr class="userHeader"><th>Username</th><th>Mail</th><th>User level</th>';
		$result .= '<th>Signature</th><th colspan="2">Options</th></tr>';

		// We open the file
		if(($handle = fopen($accountsFile, "r")) !== false) {
			// For each line
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					// We display the infos
					$result .= '<tr class="userData"><td>'.$data[0].'</td><td>'.$data[2].'</td>';
					$result .= '<td>'.$data[3].'</td><td>'.$data[4].'</td>';
					$result .= '<td><a href="users/'.strtolower($data[0]).'"><img src="'.$imgDir.'edit.png" alt="edit" height="25" /></a></td>';
					$result .= '<td><a href="admin.php?account='.$data[0].'#delAccountModal"><img src="'.$imgDir.'delete.png" alt="delete" height="25" /></a></td>';
					$result .= '</tr>';
				}
			}
			fclose($handle);
		}

		// To add a new user
		$result .= '<tr><td class="img" colspan="6" ><a href="admin.php#registerModal"><img src="'.$imgDir.'add.png" alt="add" height="25" />Ajouter</a></td></tr>';
		$result .= '</table>';

		return $result;
	}

	/*
	Initialize the database (creates it if not existing & create the tables)
	Parameters are the ones used to connect to the database
	*/
	function initDB($servername, $username, $password, $dbname) {
		$linkDB = mysqli_connect($servername, $username, $password);
		$query = mysqli_query($linkDB, "CREATE DATABASE IF NOT EXISTS `".$dbname."`");
		mysqli_close($linkDB);

		$linkDB = mysqli_connect($servername, $username, $password, $dbname);
		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `post` (
				`id` int NOT NULL UNIQUE AUTO_INCREMENT,
				`link` varchar(767) NOT NULL,
				`title` varchar(767) NOT NULL,
				`description` varchar(767),
				`author` varchar(32) NOT NULL,
				`avgMark` float(2),
				`cat_html` boolean DEFAULT FALSE,
				`cat_css` boolean DEFAULT FALSE,
				`cat_php` boolean DEFAULT FALSE,
				`cat_sql` boolean DEFAULT FALSE,
				`date` timestamp NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `mark` (
				`score` int NOT NULL,
				`author` varchar(32) NOT NULL,
				`date` timestamp NOT NULL,
				`id` int NOT NULL,
				PRIMARY KEY (`score`, `author`),
				KEY `id` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"ALTER TABLE `mark`
				ADD CONSTRAINT `mark_ibfk_1` FOREIGN KEY (`id`) REFERENCES `post` (`id`);");

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `comment` (
				`comment` varchar(767) NOT NULL,
				`author` varchar(32) NOT NULL,
				`date` timestamp NOT NULL,
				`id` int NOT NULL,
				PRIMARY KEY (`comment`, `author`),
				KEY `id` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"ALTER TABLE `mark`
				ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id`) REFERENCES `post` (`id`);");

		return $linkDB;
	}

	function getPosts($linkDB, $order) {
		$tableResult = '';

		$query = "SELECT * FROM `post` ORDER BY `id` DESC LIMIT 20";
		$result = mysqli_query($linkDB, $query);


		for($i=0 ; $i<mysqli_num_rows($result) ; $i++) {
			$tableResult .= '<table class="post">';

			$row = mysqli_fetch_row($result);
			$keys = array('id', 'link', 'title', 'description', 'author', 'avgMark', 'cat1', 'cat2', 'cat3', 'cat4', 'date');
			$values = array_combine($keys, $row);

			$categories = '';
			$catArray = array(1 => 'HTML', 'CSS', 'PHP', 'SQL');
			for($j=1 ; $j<=4 ; $j++) {
				if($values["cat".$j] == 1) {
					if(strlen($categories) > 0) {
						$categories .= ' / ';
					}
					$categories .= $catArray[$j];
				}
			}

			$commentNumber = 0;
			$commentQuery = "SELECT * FROM `comment` WHERE `id` = ".$values["id"].";";
			$commentResult = mysqli_query($linkDB, $commentQuery);
			$commentNumber = mysqli_num_rows($commentResult);

			$tableResult .= '<tr class="postInfos"><td class="postAuthor" rowspan="6"><span class="postAuthorName">'.$values["author"].'</span><br />';
			$tableResult .= '<span class="postDate">'.$values["date"].'</span><br /><img src="img/user.png" alt="user" height="80" /></td></tr>'."\n";

			$tableResult .= '<tr class="postInfos"><th class="postID">#'.$values["id"].'</th><th class="postTitle" colspan="5">'.$values["title"].'</th></tr>'."\n";

			$tableResult .= '<tr class="postInfos"><td class="postLink" colspan="6">';
			$tableResult .= '<a href="'.$values["link"].'"><img src="img/urlLink.png" alt="url" height="15" /> '.$values["link"].'</a></td></tr>'."\n";

			$tableResult .= '<tr class="postInfos"><td class="postDesc" colspan="6">'.str_replace("\n", "<br />", $values["description"]).'<br /></td></tr>'."\n";

			$tableResult .= '<tr class="postInfos"><td class="postCat" colspan="6">Catégorie(s) : <strong><em>'.$categories.'</em></strong></td></tr>'."\n";

			$tableResult .= '<tr class="postInfos"><td class="postComments" colspan="5">'.$commentNumber.' commentaire(s)';
			$tableResult .= '<a href="index.php#commentPost" class="commentPost">Commenter</a></td>';

			if(empty($_SESSION["login"])) {
				$tableResult .= '<td style="width:90px" class="postStar">';
			}
			if(! empty($_SESSION["login"])) {
				$tableResult .= '<td class="postStar">';
			}

			if($values["avgMark"] == "") {
				for($j=1 ; $j<6 ; $j++) {
					$tableResult .= '<img src="img/star_cross.png" alt="star'.$j.'" height="15" />';
				}
			}
			else {
				$starNumber = round($values["avgMark"]) / 2;
				$starCount = 0;
				for($j=1 ; $j<=$starNumber ; $j++) {
					$tableResult .= '<img src="img/star_full.png" alt="star'.$j.'" height="15" />';
					$starCount ++;
				}
				if(floor($starNumber) != $starNumber) {
					$tableResult .= '<img src="img/star_half.png" alt="star'.($j-0.5).'" height="15" />';
					$starCount ++;
				}
				for($j=$starCount ; $j<5 ; $j++) {
					$tableResult .= '<img src="img/star_empty.png" alt="star'.$j.'" height="15" />';
					$starCount ++;
				}
			}

			if(! empty($_SESSION["login"])) {
				$tableResult .= ' / <a href="index.php#changeScore" class="postUserMark">';

				$markQuery = "SELECT * FROM `mark` WHERE `id` = ".$values["id"].";";
				$markResult = mysqli_query($linkDB, $markQuery);
				$markExists = false;

				for($j=0 ; $j<mysqli_num_rows($markResult) && ! $markExists ; $j++) {
					$marks = mysqli_fetch_assoc($markResult);
					if(strtolower($marks["author"]) == strtolower($_SESSION["login"])) {
						$markExists = true;
					}
				}
				if($markExists) {
					$starNumber = round($marks["score"]) / 2;
					$starCount = 0;
					for($j=1 ; $j<=$starNumber ; $j++) {
						$tableResult .= '<img src="img/star_full_red.png" alt="star'.$j.'" height="15" />';
						$starCount ++;
					}
					if(floor($starNumber) != $starNumber) {
						$tableResult .= '<img src="img/star_half_red.png" alt="star'.($j-0.5).'" height="15" />';
						$starCount ++;
					}
					for($j=$starCount ; $j<5 ; $j++) {
						$tableResult .= '<img src="img/star_empty.png" alt="star'.$j.'" height="15" />';
						$starCount ++;
					}
				}
				else {
					for($j=1 ; $j<6 ; $j++) {
						$tableResult .= '<img src="img/star_cross_red.png" alt="star'.$j.'" height="15" />';
					}
				}
				$tableResult .= '</a>';
			}

			$tableResult .= '</td></tr>'."\n";

			$tableResult .= '</table>';
		}

		return $tableResult;
	}
?>