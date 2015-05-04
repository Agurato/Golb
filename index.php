<?php
	include_once('utils/begin.php');
	include_once('utils/util.inc.php');
	include_once('utils/forms.inc.php');
	beginHTML('Golb','css/styles.css');
	beginSession();
?>

	<!-- HEADER -->
	<div id="header">
		<!-- Login dialog box -->
		<?php
			$connectionInfos = array("servername" => "127.0.0.1", "username" => "root", "password" => "root", "dbname" =>  "golb");
			loginForm();
			registerForm();
			newPostForm($connectionInfos);
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
				<a href="index.php"><li class="leftAlign">Accueil</li></a>
				<a href="index.php"><li class="leftAlign">News</li></a>
				<a href="index.php"><li class="leftAlign">Messages</li></a>
				<?php
				if(!empty($_SESSION['login'])){
					echo '<a href="utils/disconnect.php?page=index.php"><li class="rightAlign logoutLink">Se déconnecter</li></a>';
					echo '<a href="users/user.php?name='.strtolower($_SESSION['login']).'"><li class="rightAlign loginLink">'.$_SESSION['login'].'</li></a>';
					if($_SESSION["userLevel"] == 3) {
						echo '<a href="admin.php"><li class="rightAlign adminLink">Administration</li></a>';
					}

				}
				else{
					echo '<a href="index.php#registerModal"><li class="rightAlign">S\'inscrire</li></a>';
					echo '<a href="index.php#loginModal"><li class="rightAlign">Se connecter</li></a>';
				}
				?>
			</ul>
		</div>
		<div id="content">
			<?php
				$linkDB = initDB($connectionInfos["servername"], $connectionInfos["username"], $connectionInfos["password"], $connectionInfos["dbname"]);
			?>
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