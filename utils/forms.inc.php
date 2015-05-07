<?php
	include_once('globals.inc.php');
	// $db = unserialize(DB_CONNECT);

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
						<a href="index.php#registerModal" style="font-size:10pt">Pas de compte ? Inscrivez-vous</a><br />
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
					<p class="generalForm">
						<label for="usernameSignup" >Nom d'utilisateur</label><br />
						<input type="text" name="usernameSignup" id="usernameSignup" maxlength="32" />
					</p>
					<p class="generalForm">
						<label for="emailSignup">Adresse e-mail</label><br />
						<input type="text" name="emailSignup" id="emailSignup" />
					</p>
					<p class="generalForm">
						<label for="passwordSignup1">Mot de passe</label><br />
						<input type="password" name="passwordSignup1" id="passwordSignup1" maxlength="72"/>
					</p>
					<p class="generalForm">
						<label for="passwordSignup2">Ré-entrez le mot de passe</label><br />
						<input type="password" name="passwordSignup2" id="passwordSignup2" maxlength="72"/>
					</p>
					<p class="generalForm">
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
					<p class="generalForm">
						<label for="passwordChange1">Mot de passe actuel</label><br />
						<input type="password" name="passwordChange1" id="passwordChange1" maxlength="72"/>
					</p>
					<p class="generalForm">
						<label for="passwordChange2">Nouveau mot de passe</label><br />
						<input type="password" name="passwordChange2" id="passwordChange2" maxlength="72"/>
					</p>
					<p class="generalForm">
						<label for="passwordChange3">Ré-entrez le nouveau mot de passe</label><br />
						<input type="password" name="passwordChange3" id="passwordChange3" maxlength="72"/>
					</p>
					<p class="generalForm">
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
					<p class="generalForm">
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
					<p class="generalForm">
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

	function newPostForm($newPostPage = "utils/newPost.php") {
	?>
		<div id="newPostModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Il faut un titre";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Il faut un lien";
								$count ++;
							}
							if(strpos($error, "3") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Il faut au moins une catégorie";
								$count ++;
							}
							echo "</p>";
						}
					}

					echo '<form method="post" action="'.$newPostPage.'">';
				?>
					<p class="generalForm">
						<label for="postTitle" >Titre *</label><br />
						<input type="text" name="postTitle" id="postTitle" maxlength="32" />
					</p>
					<p class="generalForm">
						<label for="postLink" >Lien *</label><br />
						<input type="text" name="postLink" id="postLink" maxlength="767" />
					</p>
					<p class="generalForm" id="postCat" ><label for="postCat">Catégorie(s) *</label><br />
					<?php
						foreach ($GLOBALS["categories"] as $key => $value) {
							$value = strtolower($value);
							echo '<input type="checkbox" class="postCategory" name="postCat[]" value="'.$value.'" id="'.$value.'Cat" />';
							echo '<label for="'.$value.'Cat" >'.strtoupper($value).'</label>'."\n";
						}
					?>
					</p>
					<p class="generalForm">
						<span style="float:right;margin-right:5px;">Les * indiquent les champs obligatoires</span><br />
						<input type="submit" class="send" value="Poster" />
					</p>
				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	function changeScore($scorePage = "utils/changeScore.php") {
	?>
		<div id="changeScoreModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Il faut rentrer un score";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Le score doit être un nombre";
								$count ++;
							}
							if(strpos($error, "3") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "ID du post manquant";
								$count ++;
							}
							echo "</p>";
						}
					}

					if(basename($_SERVER['PHP_SELF']) == 'index.php') {
						echo '<form method="post" action="'.$scorePage.'?postID='.$_GET["id"].'">';
					}
					elseif(basename($_SERVER['PHP_SELF']) == 'post.php') {
						echo '<form method="post" action="'.$scorePage.'?fromPost=true&amp;postID='.$_GET["id"].'">';
					}
				?>
					<p class="loginForm">
						Rentrez une note entre 0 et 10.
					</p>

					<div class="leftForm">
						<p class="loginForm">
							<label for="newScore" style="width:50px;">Note</label>
							<input type="text" name="newScore" id="newScore" maxlength="4" style="width:250px;" />
							<input type="submit" class="send" value="Noter" />
						</p>
					</div>
				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}

	function addComment($scorePage = "utils/addComment.php") {
	?>
		<div id="commentPostModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Il faut rentrer un commentaire";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Commentaire trop long (750 char. max)";
								$count ++;
							}
							if(strpos($error, "3") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "ID du post manquant";
								$count ++;
							}
							echo "</p>";
						}
					}

					echo '<form method="post" action="'.$scorePage.'?postID='.$_GET["id"].'">';
				?>
					<p class="generalForm">
						<label for="comment" >Commentaire</label><br />
						<textarea rows="5" cols="50" name="comment" id="comment"></textarea>
					</p>
					<p class="generalForm">
						<input type="submit" class="send" value="Commenter" />
					</p>
				</form>
				<a href="" title="Close" class="close" >Fermer</a>
			</div>
		</div>
	<?php
	}
?>