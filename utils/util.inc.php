<?php

	function loginForm($loginPage = "utils/login.php") {
		// Input the form to log in
		// $loginPage = page where infos are checked and where you are logged in
			// If ?page=something : redirect to something after you are logged in (home page otherwise)
	?>
		<!-- Login dialog box -->
		<div id="loginModal" class="modalDialog">
			<div>
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

	function changePasswordForm($chgPasswordPage = "utils/passwordChange.php") {
	?>
		<div id="chgPasswordModal" class="modalDialog">
			<div>
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

	function deleteAccountForm($deletePage = "utils/deleteAccount.php") {
	?>
		<div id="delAccountModal" class="modalDialog">
			<div>
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

	function usersTable($accountsFile = "users/accounts.csv", $imgDir = "img/") {
		$result = '';
		$result .= '<table>';

		$result .= '<tr><th>Username</th><th>Mail</th><th>User level</th><th>Signature</th><th colspan="2">Options</th></tr>';

		if(($handle = fopen($accountsFile, "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ":")) !== false) {
				if(count($data) == 5) {
					$result .= '<tr><td class="usernameTD">'.$data[0].'</td><td class="mailTD">'.$data[2].'</td>';
					$result .= '<td class="userlevelTD">'.$data[3].'</td><td class="signatureTD">'.$data[4].'</td>';
					$result .= '<td class="img"><a href="users/'.strtolower($data[0]).'"><img src="'.$imgDir.'edit.png" alt="edit" height="25" /></a></td>';
					$result .= '<td class="img"><a href="admin.php?account='.$data[0].'#delAccountModal"><img src="'.$imgDir.'delete.png" alt="delete" height="25" /></a></td>';
					$result .= '</tr>';
				}
			}
			fclose($handle);
		}

		$result .= '<tr><td class="img" colspan="6" ><a href="admin.php#registerModal"><img src="'.$imgDir.'add.png" alt="add" height="25" />Ajouter</a></td></tr>';
		$result .= '</table>';

		return $result;
	}
?>