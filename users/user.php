<?php
	include_once('../utils/begin.php');
	include_once('../utils/util.inc.php');
	include_once('../utils/forms.inc.php');
	beginHTML('Golb','../css/styles.css');
	beginSession();
?>
	<!-- HEADER -->
	<div id="header">
		<?php
			if(($_SESSION["login"] == null) || empty($_GET["name"])) {
				header('Location: ../index.php');
			}

			$currentUser = array();
			if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
				while(($data = fgetcsv($handle, 1000, ":")) !== false) {
					if(count($data) == 5) {
						// If the username is already used
						if(strtolower($data[0]) == strtolower($_GET["name"])) {
							$currentUser = $data;
						}
					}
				}
				fclose($handle);
			}
			if(empty($currentUser)) {
				header('Location: ../index.php');
			}

			// $dirname is the array which contains the names of the directories in the path
			$dirname = explode("/", getcwd());
			// The login form is login.php (at the root of the project) and we want to redirect to this page once logged in
			loginForm("../utils/login.php?page=users/user.php?name=".$_GET["name"]);
			// The login form is register.php (at the root of the project) and we want to redirect to this page once registered
			registerForm("../utils/register.php?page=users/user.php?name=".$_GET["name"]);
			// The password change form
			changePasswordForm("../utils/passwordChange.php?page=users/user.php?name=".$_GET["name"]);
			// The form to delete your account
			deleteAccountForm("../utils/deleteAccount.php");
			// The form to retrieve the password
			retrievePasswordForm("../utils/retrievePassword.php");

			pictureChange($_GET["name"]);

			if(!empty($_POST["email"])){
				
				$change = true;
				$error = "";

				$lineCounter = 0;
				$lineToEdit = 0;
				// We open the accounts file
				if(($handle = fopen("../users/accounts.csv", "r")) !== false) {
					while(($data = fgetcsv($handle, 1000, ":")) !== false) {
						if(count($data) == 5) {
							// We check each line to find the one with the good username
							if(strtolower($data[0]) == strtolower($_SESSION["login"])) {
								// We get the line number in the file
								$lineToEdit = $lineCounter;
							}
						}
						$lineCounter ++;
					}
					fclose($handle);
				}

				// If no error happened for the moment
				if($change) {
					// We re-open the file
					if(($fileLines = file("../users/accounts.csv")) !== false) {
						// We get the array containing all the infos about the current user
						$userInfos = explode(":", $fileLines[$lineToEdit]);

						if(count($userInfos) == 5) {
							// We change the email
							$userInfos[2] = $_POST["email"];
							// We change the array containing all the users
							$fileLines[$lineToEdit] = implode(":", $userInfos);
							// We re-write in the file
							file_put_contents("../users/accounts.csv", $fileLines, LOCK_EX);

							echo '<meta http-equiv="refresh" content="0;URL='.$_SERVER['REQUEST_URI'].'"> ';
							
						}
					}
				}
			}

		?>

		<!-- Title & subtitle -->
		<div class="titles">
			<h1><a href="../../index.php">Golb</a></h1>
			<p class="note">Petit Golb amusant pour les golbers affutés</p>
		</div>			
	</div>

	<!-- CONTENT -->
	<div id="contentmain">
		<div id="menu">
			<ul class="topmenu">
				<li class="leftAlign"><a href="../index.php">Accueil</a></li>
				<li class="leftAlign"><a href="../index.php">News</a></li>
				<li class="leftAlign"><a href="../index.php">Messages</a></li>
				<?php
				if(!empty($_SESSION['login'])){
					echo '<li class="rightAlign logoutLink"><a href="../utils/disconnect.php?page=index.php">Se déconnecter</a></li>';
					echo '<li class="rightAlign loginLink"><a href="../users/user.php?name='.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
					if($_SESSION["userLevel"] == 3) {
						echo '<li class="rightAlign adminLink"><a href="../admin.php">Administration</a></li>';
					}

				}
				else{
					echo '<li class="rightAlign"><a href="../index.php#registerModal">S\'inscrire</a></li>';
					echo '<li class="rightAlign"><a href="../index.php#loginModal">Se connecter</a></li>';
				}
				?>
			</ul>
		</div>
		<div id="content">
			<?php
				
				if(!empty($_FILES)){
					move_uploaded_file($_FILES['userfile']['tmp_name'], getcwd().'/pics/'.$_FILES['userfile']['name']);
					if(file_exists('pics/'.$_GET["name"].'profil.png')){
						unlink('pics/'.$_GET["name"].'profil.png');
					}
					rename('pics/'.$_FILES['userfile']['name'],$_GET["name"].'profil.png');
				}

				
				if(file_exists('pics/'.($_GET["name"].'profil.png'))){
					echo '<p id="username">'.$currentUser[0].'<img src="pics/'.$_GET["name"].'profil.png" width="100" alt="profilePic" height="100"/></p>';
				}
				else{
					echo '<p id="username">'.$currentUser[0].'<img src="pics/default.png" width="100" alt="profilePic" height="100"/></p>';

				}
				echo '<form method="post" action="user.php?name='.$_GET["name"].'#pictureChange">'

			?>
				<p class="profileForm">
					<input type="submit" value="Changer votre photo de profil" name="changePicture" id="changePicture" />
				</p>
			</form>	
			<?php
			echo '<form method="post" action="user.php?name='.$_GET["name"].'">';
			?>
				<p class="profileForm">
					<label for="email">Adresse e-mail</label><br />
					<input type="text" name="email" id="email" 
					<?php
						if(!empty($_SESSION['login'])) {
							if((strtolower($_SESSION['login']) == strtolower($currentUser[0])) || $_SESSION["userLevel"] == 3) {
								echo 'value="'.$currentUser[2].'"';
							}
						}
					?>
					/>
					<input type="submit" name="emailChange" id="emailChange" value="Changer mon adresse e-mail"/>
				</p>
			</form>
			<!--<?php
			echo '<form method="post" action="user.php?name="'.$_GET["name"].'">';
			?>
				<p class="profileForm">
					<label for="signature">Signature (512 charactères max)</label><br />
					<textarea rows="5" cols="50" name="signature" id="signature" ><?php
						echo $currentUser[4]."Signature\n\nCoucou";
					?></textarea>
				</p>
				<p class="profileForm">
					<input type="submit" class="send" value="Sauvegarder les changements" />
				</p>

			</form>
			-->
			<?php
				if(!empty($_SESSION['login'])) {
					if(strtolower($_SESSION['login']) == strtolower($currentUser[0])) {
						echo '<p><a href="#chgPasswordModal">Changer mon mot de passe</a></p>';
						echo '<p><a href="#delAccountModal">Détruire mon compte</a></p>';
					}
				}
			?>
		</div>
	</div>

	<!-- FOOTER -->
	<div id="footer">
		<p>
			A website by Vincent Monot and Lucas Nicosia
		</p>
	</div>

<?php
	endHTML();
?>