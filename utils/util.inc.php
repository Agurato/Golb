<?php
	include_once('globals.inc.php');

	/* 
	Used only in admin.php
	Return the list of all users in a <table>
	*/
	function usersTable($linkDB, $accountsFile = "users/accounts.csv", $imgDir = "img/") {
		$result = '';
		$result .= '<table id="accountsList">';

		// Column names
		$result .= '<tr class="userHeader"><th>Username</th><th>Mail</th><th>User level</th>';
		$result .= '<th>Nombre de posts</th><th colspan="4">Options</th></tr>';

		// We open the file
		if(($handle = fopen($accountsFile, "r")) !== false) {
			// For each line
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {

					$postNumber = 0;
					$postQuery = 'SELECT * FROM `post` WHERE `author` = "'.$data[0].'";';
					$postResult = mysqli_query($linkDB, $postQuery);
					$postNumber = mysqli_num_rows($postResult);

					// We display the infos
					$result .= '<tr class="userData"><td>'.$data[0].'</td><td>'.$data[2].'</td>';
					$result .= '<td>'.$data[3].'</td><td>'.$postNumber.'</td><td class="userOption">';
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

	function categoriesTable($linkDB, $imgDir = "img/") {
		$result = '';

		$result .= '<table id="categoriesList">';
		$result .= '<tr class="catHeader"><th>Nom de la catégorie</th><th>Nombre d\'utilisations</th><th colspan="2">Options</th></tr>';

		$categoriesQuery = 'SELECT * FROM `category`;';
		$categoriesResult = mysqli_query($linkDB, $categoriesQuery);
		for($i=0 ; $i<mysqli_num_rows($categoriesResult) ; $i++) {
			$values = mysqli_fetch_assoc($categoriesResult);
			$result .= '<tr class="catData"><td>'.$values["name"].'</td><td>';

			$categoryUses = 0;
			$categoryQuery = 'SELECT * FROM `is_used` WHERE `categoryName` = "'.$values["name"].'";';
			$categoryResult = mysqli_query($linkDB, $categoryQuery);
			$categoryUses = mysqli_num_rows($categoryResult);
			
			$result .= $categoryUses.'</td>';
			$result .= '<td class="catOption"><a href="?name='.$values["name"].'#addCategoryModal"><img src="'.$imgDir.'edit.png" alt="edit" height="25" /></a></td>';
			$result .= '<td class="catOption"><a href="utils/deleteCategory.php?name='.$values["name"].'"><img src="'.$imgDir.'delete.png" alt="delete" height="25" /></a></td>';

			$result .= '</tr>';
		}

		$result .= '<tr><td class="addCat" colspan="4" ><a href="admin.php#addCategoryModal"><img src="'.$imgDir.'add.png" alt="add" height="25" />Ajouter</a></td></tr>';
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

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `category` (
				`name` varchar(16) NOT NULL,
				PRIMARY KEY (`name`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"INSERT INTO `category` (`name`)
				VALUES (\"HTML\");");

		$query = mysqli_query($linkDB,
			"CREATE TABLE IF NOT EXISTS `is_used` (
				`categoryName` varchar(16) NOT NULL,
				`postID` int NOT NULL,
				PRIMARY KEY (`categoryName`, `postID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$query = mysqli_query($linkDB,
			"ALTER TABLE `is_used`
				ADD CONSTRAINT `is_used_ibfk_1` FOREIGN KEY (`categoryName`) REFERENCES `category` (`name`);");
		$query = mysqli_query($linkDB,
			"ALTER TABLE `is_used`
				ADD CONSTRAINT `is_used_ibfk_2` FOREIGN KEY (`postID`) REFERENCES `post` (`id`);");

		return $linkDB;
	}

	function postInfos($linkDB, $postRow) {
		$tableResult = '';

		$categories = '';
		$categoriesQuery = 'SELECT * FROM `is_used` WHERE `postID` = '.$postRow["id"].";";
		$categoriesResult = mysqli_query($linkDB, $categoriesQuery);
		for($i=0 ; $i<mysqli_num_rows($categoriesResult) ; $i++) {
			$cats = mysqli_fetch_assoc($categoriesResult);
			if($i > 0) {
				$categories .= ' / ';
			}
			$categories .= $cats["categoryName"];
		}

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
		$tableResult .= '<span class="postDate">'.$postRow["date"].'</span><br />';
		if(file_exists('users/'.strtolower($postRow["author"]).'profil.png')){
			$tableResult .= '<img src="users/'.strtolower($postRow['author']).'profil.png" alt="user" height="40" class="userPic" />';
		}
		else{
			$tableResult .= '<img src="users/default.png" alt="user" height="40" class="userPic" />';

		}
		$tableResult .= '</td></tr>'."\n";

		if(! empty($_SESSION["login"]) && ! empty($_SESSION["userLevel"])) {
			if((strtolower($postRow["author"]) == strtolower($_SESSION["login"])) || $_SESSION["userLevel"] == 3 ) {
				$tableResult .= '<tr class="postInfos"><th class="postID">#'.$postRow["id"].'</th><th class="postTitle" colspan="4">'.$postRow["title"].'</th>';
				$tableResult .= '<td class="postOptions"><a href="?id='.$postRow["id"].'#newPostModal"><img src="img/edit.png" alt="edit" height="20" /></a> ';
				$tableResult .= '<a href="utils/deletePost.php?id='.$postRow["id"].'"><img src="img/delete.png" alt="delete" height="20" /></a></td></tr>'."\n";
			}
			else {
				$tableResult .= '<tr class="postInfos"><th class="postID">#'.$postRow["id"].'</th><th class="postTitle" colspan="5">'.$postRow["title"].'</th></tr>';
			}
		}
		else {
			$tableResult .= '<tr class="postInfos"><th class="postID">#'.$postRow["id"].'</th><th class="postTitle" colspan="5">'.$postRow["title"].'</th></tr>';
		}

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

	function getPosts($linkDB, $beginning, $filter, $order, $direction) {
		$tableResult = '';

		$query = 'SELECT * FROM `post`';

		$checkFilter = array();
		$checkFilterResult = mysqli_query($linkDB, 'SELECT `name` FROM `category`');
		for($i=0 ; $i<mysqli_num_rows($checkFilterResult) ; $i++) {
			$checkFilter[] = mysqli_fetch_assoc($checkFilterResult)['name'];
		}

		if(in_array($filter, $checkFilter)) {
			$query .= ' WHERE `id` IN (SELECT `postID` FROM `is_used` WHERE `categoryName` = "'.$filter.'")';
		}

		if(($direction == 'ASC' || $direction == 'DESC') && in_array($order, array('id', 'date'))) {
			$query .= ' ORDER BY `'.$order.'` '.$direction;
		}
		else {
			$query .= ' ORDER BY `id` ASC';
		}
		$query .= ' LIMIT '.$beginning.', 20;';

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

			$tableResult .= '<tr class="postComment"><td class="commentAuthor" rowspan="3">';
			if(file_exists('users/'.strtolower($comment["author"]).'profil.png')){
				$tableResult .= '<img src="users/'.strtolower($comment['author']).'profil.png" alt="user" height="40" class="userPic" />';
			}
			else{
				$tableResult .= '<img src="users/default.png" alt="user" height="40" class="userPic" />';
			}
			$tableResult .= '<span class="commentAuthorName">'.$comment["author"].'</span>';
			$tableResult .= '<br /><span class="commentDate">'.$comment["date"].'</span></td></tr>';
			$tableResult .= '<tr class="postComment"><th class="commentID">#'.$postID.'.'.$comment["commentID"].'</th><th><a href="#header">'.$post["title"].'</a></th></tr>';
			$tableResult .= '<tr class="postComment"><td colspan="2">'.str_replace("\n", "<br />", $comment["comment"]).'</td></tr>';

			$tableResult .= '</table>';
		}
		
		$tableResult .= '</div>';

		return $tableResult;
	}

	function accessPages($linkDB, $url, $page) {
		$result = '';

		$pageNumberResult = mysqli_query($linkDB, 'SELECT * FROM `post`;');
		$pageNumber = floor(mysqli_num_rows($pageNumberResult)/20) + 1;

		echo '<div id="pageNumbers">';

		if($page-2 > 1) {
			$result .= '<a href="index.php?page=1" class="pageNumber">1</a>';
			if($page-3 > 1) {
				if($page-3 == 2) {
					$result .= '<a href="index.php?page=2" class="pageNumber">2</a>';
				}
				else {
					$result .= '<span class="pageNumber">...</span>';
				}
			}
		}

		$i = 0;
		for($i=$page-2 ; $i<=$page+2 ; $i++) {
			if(($i > 0) && ($i <= $pageNumber)) {
				if($i == $page) {
					$result .= '<a href="index.php?page='.$i.'" class="pageNumber actualPage">'.$i.'</a>';
				}
				else {
					$result .= '<a href="index.php?page='.$i.'" class="pageNumber">'.$i.'</a>';
				}
			}
		}

		if($page+2 < $pageNumber) {
			if($page+3 < $pageNumber) {
				if($page+3 == $pageNumber-1) {
					$result .= '<a href="index.php?page='.($pageNumber-1).'" class="pageNumber">'.($pageNumber-1).'</a>';
				}
				else {
					$result .= '<span class="pageNumber">...</span>';
				}
			}
			$result .= '<a href="index.php?page='.$pageNumber.'" class="pageNumber">'.$pageNumber.'</a>';
		}

		echo '</div>';

		return $result;
	}
?>