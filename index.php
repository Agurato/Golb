<?php
	include_once('utils/begin.php');
	include_once('utils/util.inc.php');
	include_once('utils/forms.inc.php');
	include_once('utils/globals.inc.php');
	beginHTML('Golb','css/styles.css');
	beginSession();
?>

	<!-- HEADER -->
	<div id="header">
		<!-- Login dialog box -->
		<?php
			$linkDB = initDB();
			loginForm();
			registerForm();
			newPostForm($linkDB);
			changeScore();
		?>

		<!-- Title & subtitle -->
		<div class="titles">
			<h1><a href="index.php">Golb</a></h1>
			<p class="note">Petit Golb amusant pour les golbers affutés</p>
		</div>			
	</div>

	<!-- CONTENT -->
	<div id="contentmain">
		<div id="menu">
			<ul class="topmenu">
				<li class="leftAlign"><a href="index.php">Accueil</a></li>
				<li class="leftAlign"><a href="index.php">News</a></li>
				<li class="leftAlign"><a href="index.php">Messages</a></li>
				<?php
				if(!empty($_SESSION['login'])){
					echo '<li class="rightAlign logoutLink"><a href="utils/disconnect.php?page=index.php">Se déconnecter</a></li>';
					echo '<li class="rightAlign loginLink"><a href="users/user.php?name='.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
					if($_SESSION["userLevel"] == 3) {
						echo '<li class="rightAlign adminLink"><a href="admin.php">Administration</a></li>';
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
			<form method="post" action="index.php" class="selecter">
					<div class="selecter">
						<select name="Selectioner une rubrique" id="rubrique">
							<option value="Rubrique">Rubrique</option>
							<option value="XHTML">XHTML</option>
							<option value="CSS">CSS</option>
							<option value="PHP">PHP</option>
							<option value="MySQL">MySQL</option>
						</select>
					</div>
					<div>
						<input type="submit" name="valider" value="Valider" id="valider"/>
					</div>
			</form>

			<?php
				if(!empty($_SESSION["login"]) && $_SESSION["userLevel"] > 1) {
					echo '<a href="index.php#newPostModal">Nouveau post</a>';
				}
				else if(empty($_SESSION["login"])) {
					echo '<a href="index.php#loginModal">Nouveau post</a>';
				}
				else {
					echo '<p>Votre inscription est en cours</p>';
				}
				echo getPosts($linkDB, "date");

				mysqli_close($linkDB);
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