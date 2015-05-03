<?php
	include_once('../../utils/begin.php');
	include_once('../../utils/util.inc.php');
	include_once('../../utils/forms.inc.php');
	beginHTML('Golb','../../css/styles.css');
	beginSession();
?>
	<!-- HEADER -->
	<div id="header">
		<?php
			if($_SESSION["login"] == null) {
				header('Location: ../../index.php');
			}
			// $dirname is the array which contains the names of the directories in the path
			$dirname = explode("/", getcwd());
			// The login form is login.php (at the root of the project) and we want to redirect to this page once logged in
			loginForm("../../utils/login.php?page=users/".$dirname[count($dirname)-1]."/index.php");
			// The login form is register.php (at the root of the project) and we want to redirect to this page once registered
			registerForm("../../utils/register.php?page=users/".$dirname[count($dirname)-1]."/index.php");
			// The password change form
			changePasswordForm("../../utils/passwordChange.php?page=users/".$dirname[count($dirname)-1]."/index.php");
			// The form to delete your account
			deleteAccountForm("../../utils/deleteAccount.php");
			// The form to retrieve the password
			retrievePasswordForm("../../utils/retrievePassword.php");
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
				<li class="leftAlign"><a href="../../index.php">Accueil</a></li>
				<li class="leftAlign"><a href="../../index.php">News</a></li>
				<li class="leftAlign"><a href="../../index.php">Messages</a></li>
				<?php
				if(!empty($_SESSION['login'])){
					echo '<li class="rightAlign logoutLink"><a href="../../utils/disconnect.php?page=users/'.$dirname[count($dirname)-1].'">Se déconnecter</a></li>';
					echo '<li class="rightAlign loginLink"><a href="../'.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
					if($_SESSION["userLevel"] == 2) {
						echo '<li class="rightAlign adminLink"><a href="../../admin.php">Administration</a></li>';
					}

				}
				else{
					echo '<li class="rightAlign"><a href="index.php#registerModal">S\'inscrire</a></li>';
					echo '<li class="rightAlign"><a href="index.php#loginModal">Se connecter</a></li>';
				}
				?>
			</ul>
		</div>
		<div id="content">
			<?php
				$currentUser = array();
				if(($handle = fopen(".userAccount.csv", "r")) !== false) {
					$currentUser = fgetcsv($handle, 1000, ":");
					fclose($handle);
				}
				echo '<p id="username">'.$currentUser[0].'<img src="profilePic.png" alt="profilePic" height="100"/></p>';
			?>
			<form method="post" action="profileChange.php">
				<p class="profileForm">
					<label for="email">Adresse e-mail</label><br />
					<input type="text" name="email" id="email" 
					<?php
						if(!empty($_SESSION['login'])) {
							if((strtolower($_SESSION['login']) == strtolower($currentUser[0])) || $_SESSION["userLevel"] == 2) {
								echo 'value="'.$currentUser[2].'"';
							}
						}
					?>
					/>
				</p>
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

			<?php
				if(!empty($_SESSION['login'])) {
					if(strtolower($_SESSION['login']) == strtolower($currentUser[0])) {
						echo '<p><a href="index.php#chgPasswordModal">Changer mon mot de passe</a></p>';
						echo '<p><a href="index.php#delAccountModal">Détruire mon compte</a></p>';
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