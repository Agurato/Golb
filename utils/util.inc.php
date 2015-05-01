<?php

	// Modal window to sign in
	function loginForm($loginPage = "utils/login.php") {
		// Input the form to log in
		// $loginPage = page where infos are checked and where you are logged in
			// If ?page=something : redirect to something after you are logged in (home page otherwise)
	?>
		<!-- Login dialog box -->
		<div id="loginModal" class="modalDialog">
			<div>
				<!-- ERROR DISPLAY -->
				<?php
					if(isset($_GET["error"])) {
						if($_GET["error"] == true) {
							echo "<p>Nom d'utilisateur ou mot de passe incorrect</p>";
						}
						else {
							echo "<p>Pas d'erreur !</p>";
						}
					}
					echo '<form method="post" action="'.$loginPage.'">';
				?>
					<!-- Form inputs -->
					<div>
						<p class="loginForm">
							<label for="login">Nom d'utilisateur</label>
							<input type="text" name="login" id="login" maxlength="32" />
						</p>
					</div>
					<div class="leftForm">
						<p class="loginForm">
							<label for="password">Mot de passe</label>
							<input type="password" name="password" id="password" maxlength="72" />
							<input type="submit" class="send" value="Se connecter" />
						</p>
					</div>
					<p class="loginForm">
						<a href="index.php#passwordLost" style="font-size:10pt">Mot de passe perdu ?</a><br />
					</p>
				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	// Modal window to sign up
	function registerForm($registerPage = "utils/register.php") {
	?>
		<!-- Register dialog box -->
		<div id="registerModal" class="modalDialog">
			<div>
				<!-- ERROR DISPLAY -->
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Nom d'utilisateur déjà utilisé";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Nome d'utilisateur invalide";
								$count ++;
							}
							if(strpos($error, "3") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Adresse e-mail déjà utilisée";
								$count ++;
							}
							if(strpos($error, "4") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Adresse e-mail invalide";
								$count ++;
							}
							if(strpos($error, "5") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Les mots de passe ne correspondent pas";
								$count ++;
							}
							echo "</p>";
						}
					}
					echo '<form method="post" action="'.$registerPage.'">';
				?>
					<!-- Form inputs -->
					<p class="registerForm">
						<label for="usernameSignup" >Nom d'utilisateur</label><br />
						<input type="text" name="usernameSignup" id="usernameSignup" maxlength="32" />
					</p>
					<p class="registerForm">
						<label for="emailSignup">Adresse e-mail</label><br />
						<input type="text" name="emailSignup" id="emailSignup" />
					</p>
					<p class="registerForm">
						<label for="passwordSignup1">Mot de passe</label><br />
						<input type="password" name="passwordSignup1" id="passwordSignup1" maxlength="72"/>
					</p>
					<p class="registerForm">
						<label for="passwordSignup2">Ré-entrez le mot de passe</label><br />
						<input type="password" name="passwordSignup2" id="passwordSignup2" maxlength="72"/>
					</p>
					<p class="registerForm">
						<input type="submit" class="send" value="S'enregistrer" />
					</p>
				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	// Modal window to change the password
	function changePasswordForm($chgPasswordPage = "utils/passwordChange.php") {
	?>
		<div id="chgPasswordModal" class="modalDialog">
			<div>
				<!-- ERROR DISPLAY -->
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Mauvais mot de passe !";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Les nouveaux mots de passe sont différents !";
								$count ++;
							}
							echo "</p>";
						}
					}
					echo '<form method="post" action="'.$chgPasswordPage.'">';
				?>
					<!-- Form inputs -->
					<p class="registerForm">
						<label for="passwordChange1">Mot de passe actuel</label><br />
						<input type="password" name="passwordChange1" id="passwordChange1" maxlength="72"/>
					</p>
					<p class="registerForm">
						<label for="passwordChange2">Nouveau mot de passe</label><br />
						<input type="password" name="passwordChange2" id="passwordChange2" maxlength="72"/>
					</p>
					<p class="registerForm">
						<label for="passwordChange3">Ré-entrez le nouveau mot de passe</label><br />
						<input type="password" name="passwordChange3" id="passwordChange3" maxlength="72"/>
					</p>
					<p class="registerForm">
						<a href="index.php#passwordLost" style="float:right;margin-right:5px;">Mot de passe perdu ?</a><br />
						<input type="submit" class="send" value="Change password" />
					</p>

				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	// Modal window to delete an account
	function deleteAccountForm($deletePage = "utils/deleteAccount.php") {
	?>
		<div id="delAccountModal" class="modalDialog">
			<div>
				<!-- ERROR DISPLAY -->
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Mauvais mot de passe !";
							}
							echo "</p>";
						}
					}
					if(isset($_GET['account'])) {
						echo '<form method="post" action="'.$deletePage.'?account='.$_GET['account'].'">';
					}
					else {
						echo '<form method="post" action="'.$deletePage.'">';
					}
				?>
					<!-- Form inputs -->
					<p class="registerForm">
						<label for="passwordDelete">Mot de passe</label><br />
						<input type="password" name="passwordDelete" id="passwordDelete" maxlength="72"/>
					</p>
					<p>
						<input type="radio" name="deleteOption" id="deletePartly" value="deletePartly" checked="checked" />
						<label for="deletePartly">Détruire les données du compte (posts, commentaires et notes seront sauvegardés)</label>
						<br />
						<input type="radio" name="deleteOption" id="deleteAll" value="deleteAll" />
						<label for="deleteAll">Détruire les données du compte et tous les posts, commentaires et notes liés à celui-ci</label>
					</p>
					<p class="registerForm">
						<a href="index.php#passwordLost" style="float:right;margin-right:5px;">Mot de passe perdu ?</a><br />
						<?php
						if(isset($_GET['account'])) {
							echo '<input type="submit" class="send" value="Détruire le compte '.$_GET['account'].'" />';
						}
						else {
							echo '<input type="submit" class="send" value="Détruire le compte" />';
						}
						?>
					</p>

				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	function retrievePasswordForm($retrievePasswordPage = "utils/passwordRetrieve.php") {
	?>
		
	<?php
	}

	/* 
	Used only in admin.php
	Return the list of all users in a <table>
	*/
	function usersTable($accountsFile = "users/accounts.csv", $imgDir = "img/") {
		$result = '';
		$result .= '<table>';

		// Column names
		$result .= '<tr><th>Username</th><th>Mail</th><th>User level</th><th>Signature</th><th colspan="2">Options</th></tr>';

		// We open the file
		if(($handle = fopen($accountsFile, "r")) !== false) {
			// For each line
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					// We display the infos
					$result .= '<tr><td class="usernameTD">'.$data[0].'</td><td class="mailTD">'.$data[2].'</td>';
					$result .= '<td class="userlevelTD">'.$data[3].'</td><td class="signatureTD">'.$data[4].'</td>';
					$result .= '<td class="img"><a href="users/'.strtolower($data[0]).'"><img src="'.$imgDir.'edit.png" alt="edit" height="25" /></a></td>';
					$result .= '<td class="img"><a href="admin.php?account='.$data[0].'#delAccountModal"><img src="'.$imgDir.'delete.png" alt="delete" height="25" /></a></td>';
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
				`id` int NOT NULL AUTO_INCREMENT,
				`link` varchar(767) NOT NULL,
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
				`score` float(2) NOT NULL,
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

		mysqli_close($linkDB);
	}
?>