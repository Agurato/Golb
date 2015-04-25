<?php
	include('../../begin.php');
	include('../../util.inc.php');
	beginHTML('Golb','../test.css');
	beginSession();
?>
	<div class="container">
		<!-- HEADER -->
		<div id="header">
			<?php
				// $dirname is the array which contains the names of the directories in the path
				$dirname = explode("/", getcwd());
				// The login form is login.php (at the root of the project) and we want to redirect to this page once logged in
				loginForm("../../login.php?page=users/".$dirname[count($dirname)-1]."/index.php");
				// The login form is register.php (at the root of the project) and we want to redirect to this page once registered
				registerForm("../../register.php?page=users/".$dirname[count($dirname)-1]."/index.php");
				// The password change form
				passwordForm("../../passwordChange.php?page=users/".$dirname[count($dirname)-1]."/index.php");
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
						// echo '<li class="leftAlign"><a href="index.php">Profil</a></li>';
						// echo '<li class="rightAlign"><a href="index.php?deconnect=true">Se déconnecter</a></li>';
						echo '<li class="rightAlign logoutLink"><a href="../../disconnect.php?page=users/'.$dirname[count($dirname)-1].'">Se déconnecter</a></li>';
						echo '<li class="rightAlign loginLink"><a href="../'.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
						if($_SESSION["userLevel"] == "2") {
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
					if(($handle = fopen("../accounts.csv", "r")) !== false) {
						while(($data = fgetcsv($handle, 1000, ":")) !== false) {
							if(count($data) == 4) {
								if(strtolower($data[0]) == $dirname[count($dirname)-1]) {
									$currentUser = $data;
								}
							}
						}
						fclose($handle);
					}
					echo '<p id="username">'.$currentUser[0].'</p>';
				?>
				<form method="post" action="index.php">
					<p class="profileForm">
						<label for="email">E-mail address</label><br />
						<input type="text" name="email" id="email" 
						<?php
							if(!empty($_SESSION['login'])) {
								if(strtolower($_SESSION['login']) == strtolower($currentUser[0])) {
									echo 'value="'.$currentUser[2].'"';
								}
							}
						?>
						/>
					</p>
					<p class="profileForm">
						<input type="submit" class="send" value="Save changes" />
					</p>

				</form>

				<?php
					if(!empty($_SESSION['login'])) {
						if(strtolower($_SESSION['login']) == strtolower($currentUser[0])) {
							echo '<p><a href="index.php#chgPasswordModal">Change password</a></p>';
							echo '<p><a href="index.php#delAccountModal">Delete account</a></p>';
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
	</div>
<?php
	endHTML();
?>