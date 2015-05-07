<?php
	include_once('globals.inc.php');

	/* 
	Used only in admin.php
	Return the list of all users in a <table>
	*/
	function usersTable($accountsFile = "users/accounts.csv", $imgDir = "img/") {
		$result = '';
		$result .= '<table id="accountsList">';

		// Column names
		$result .= '<tr class="userHeader"><th>Username</th><th>Mail</th><th>User level</th>';
		$result .= '<th>Signature</th><th colspan="4">Options</th></tr>';

		// We open the file
		if(($handle = fopen($accountsFile, "r")) !== false) {
			// For each line
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					// We display the infos
					$result .= '<tr class="userData"><td>'.$data[0].'</td><td>'.$data[2].'</td>';
					$result .= '<td>'.$data[3].'</td><td>'.$data[4].'</td><td class="userOption">';
					if($data[3]>=2) {
						$result .= '<img src="'.$imgDir.'check.png" alt="checkLevel2" height="25" />';
					}
					else {
						$result .= '<a href="utils/updateLevel.php?user='.$data[0].'&amp;level=2"><img src="'.$imgDir.'check_empty.png" alt="checkLevel2" height="25" /></a>';
					}
					$result .= '</td><td class="userOption">';
					if($data[3]>=3) {
						$result .= '<img src="'.$imgDir.'check.png" alt="checkLevel3" height="25" />';
					}
					else {
						$result .= '<a href="utils/updateLevel.php?user='.$data[0].'&amp;level=3"><img src="'.$imgDir.'check_empty.png" alt="checkLevel2" height="25" /></a>';
					}
					$result .= '</td><td class="userOption"><a href="users/user.php?name='.strtolower($data[0]).'"><img src="'.$imgDir.'edit.png" alt="edit" height="25" /></a></td>';
					$result .= '<td class="userOption"><a href="admin.php?account='.$data[0].'#delAccountModal"><img src="'.$imgDir.'delete.png" alt="delete" height="25" /></a></td>';
					$result .= '</tr>';
				}
			}
			fclose($handle);
		}

		// To add a new user
		$result .= '<tr><td class="addUser" colspan="8" ><a href="admin.php#registerModal"><img src="'.$imgDir.'add.png" alt="add" height="25" />Ajouter</a></td></tr>';
		$result .= '</table>';

		return $result;
	}

	/*
	Initialize the database (creates it if not existing & create the tables)
	Parameters are the ones used to connect to the database
	*/
	function initDB() {
		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS);
		$query = mysqli_query($linkDB, "CREATE DATABASE IF NOT EXISTS `".DB_NAME."`");
		mysqli_close($linkDB);

		$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);
		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `post` (
				`id` int NOT NULL UNIQUE AUTO_INCREMENT,
				`link` varchar(767) NOT NULL,
				`title` varchar(767) NOT NULL,
				`description` varchar(767),
				`author` varchar(32) NOT NULL,
				`cat` varchar(767) NOT NULL,
				`date` timestamp NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `mark` (
				`score` int NOT NULL,
				`author` varchar(32) NOT NULL,
				`date` timestamp NOT NULL,
				`postID` int NOT NULL,
				PRIMARY KEY (`author`, `postID`),
				KEY `postID` (`postID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"ALTER TABLE `mark`
				ADD CONSTRAINT `mark_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`id`);");

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `comment` (
				`commentID` int NOT NULL UNIQUE AUTO_INCREMENT,
				`comment` varchar(767) NOT NULL,
				`author` varchar(32) NOT NULL,
				`date` timestamp NOT NULL,
				`postID` int NOT NULL,
				PRIMARY KEY (`commentID`, `postID`),
				KEY `postID` (`postID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"ALTER TABLE `comment`
				ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`id`);");

		return $linkDB;
	}

	function postInfos($linkDB, $postRow) {
		$tableResult = '';

		$categories = str_replace('/', ' / ', $postRow["cat"]);

		$commentNumber = 0;
		$commentQuery = "SELECT * FROM `comment` WHERE `postID` = ".$postRow["id"].";";
		$commentResult = mysqli_query($linkDB, $commentQuery);
		$commentNumber = mysqli_num_rows($commentResult);

		$sumMark = 0;
		$markNumber = 0;
		$markQuery = "SELECT * FROM `mark` WHERE `postID` = ".$postRow["id"].";";
		$markResult = mysqli_query($linkDB, $markQuery);
		$markNumber = mysqli_num_rows($markResult);

		for($j=0 ; $j<$markNumber ; $j++) {
			$marks = mysqli_fetch_assoc($markResult);
			$sumMark += $marks["score"];
		}

		$tableResult .= '<table class="post">'."\n";

		$tableResult .= '<tr class="postInfos"><td class="postAuthor" rowspan="6"><span class="postAuthorName">'.$postRow["author"].'</span><br />';
		$tableResult .= '<span class="postDate">'.$postRow["date"].'</span><br /><img src="img/user.png" alt="user" height="80" /></td></tr>'."\n";

		$tableResult .= '<tr class="postInfos"><th class="postID">#'.$postRow["id"].'</th><th class="postTitle" colspan="5">'.$postRow["title"].'</th></tr>'."\n";

		$tableResult .= '<tr class="postInfos"><td class="postLink" colspan="6">';
		$tableResult .= '<a href="'.$postRow["link"].'"><img src="img/urlLink.png" alt="url" height="15" /> '.$postRow["link"].'</a></td></tr>'."\n";

		$tableResult .= '<tr class="postInfos"><td class="postDesc" colspan="6">'.str_replace("\n", "<br />", $postRow["description"]).'<br /></td></tr>'."\n";

		$tableResult .= '<tr class="postInfos"><td class="postCat" colspan="6">Catégorie(s) : <strong><em>'.$categories.'</em></strong></td></tr>'."\n";

		$tableResult .= '<tr class="postInfos"><td class="postComments" colspan="5"><a href="post.php?id='.$postRow["id"].'">'.$commentNumber.' commentaire(s)</a>';
		$tableResult .= '<a href="post.php?id='.$postRow["id"].'#commentPostModal" class="commentPost">Commenter</a></td>';

		if(empty($_SESSION["login"])) {
			$tableResult .= '<td style="width:90px" class="postStar">';
		}
		if(! empty($_SESSION["login"])) {
			$tableResult .= '<td class="postStar">';
		}

		if($markNumber == 0) {
			for($j=1 ; $j<6 ; $j++) {
				$tableResult .= '<img src="img/star_cross.png" alt="star'.$j.'" height="15" />';
			}
		}
		else {
			$starNumber = round($sumMark/$markNumber) / 2;
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
			$tableResult .= ' / <a href="?id='.$postRow["id"].'#changeScoreModal" class="postUserMark">';

			$markQuery = "SELECT * FROM `mark` WHERE `postID` = ".$postRow["id"].";";
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

		return $tableResult;
	}

	function getPosts($linkDB, $order) {
		$tableResult = '';

		$query = "SELECT * FROM `post` ORDER BY `id` DESC LIMIT 20";
		$result = mysqli_query($linkDB, $query);

		for($i=0 ; $i<mysqli_num_rows($result) ; $i++) {

			$values = mysqli_fetch_assoc($result);
			$tableResult .= postInfos($linkDB, $values);
			
		}

		return $tableResult;
	}

	function getFullPost($linkDB, $postID) {
		$tableResult = '';

		$tableResult = '<div id="postDiv">';

		$postQuery = 'SELECT * FROM `post` WHERE `id`='.$postID;
		$postResult = mysqli_query($linkDB, $postQuery);
		$post = mysqli_fetch_assoc($postResult);
		$tableResult .= postInfos($linkDB, $post);

		$commentQuery = 'SELECT * FROM `comment` WHERE `postID`='.$postID;
		$commentResult = mysqli_query($linkDB, $commentQuery);

		$tableResult .= '</div><div id="commentDiv">';

		for($i=0 ; $i<mysqli_num_rows($commentResult) ; $i++) {
			$comment = mysqli_fetch_assoc($commentResult);

			$tableResult .= '<table class="postComment">';

			$tableResult .= '<tr class="postComment"><td class="commentAuthor" rowspan="3"><img src="img/user.png" alt="user" height="40" class="userPic" />';
			$tableResult .= '<span class="commentAuthorName">'.$comment["author"].'</span>';
			$tableResult .= '<br /><span class="commentDate">'.$comment["date"].'</span></td></tr>';
			$tableResult .= '<tr class="postComment"><th class="commentID">#'.$postID.'.'.$comment["commentID"].'</th><th><a href="#header">'.$post["title"].'</a></th></tr>';
			$tableResult .= '<tr class="postComment"><td colspan="2">'.str_replace("\n", "<br />", $comment["comment"]).'</td></tr>';

			$tableResult .= '</table>';
		}
		
		$tableResult .= '</div>';

		return $tableResult;
	}
?>