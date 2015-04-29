<?php
	include_once('begin.php');
	include_once('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();
?>
	<div class="container">
		<!-- HEADER -->
		<div id="header">
			<!-- Login dialog box -->
			<?php
				loginForm();
				registerForm();
			?>

			<!-- Title & subtitle -->
			<div class="titles">
				<h1><a href="index.php">Golb</a></h1>
				<p class="note">Petit Golb amusant pour les golbers affutés</p>
			</div>			
		</div>

		<!-- CONTENT -->
		<div id="contentmain">
			<div class="content">
				<div class="menu">
					<ul class="topmenu">
						<li class="leftAlign"><a href="index.php">Accueil</a></li>
						<li class="leftAlign"><a href="index.php">News</a></li>
						<li class="leftAlign"><a href="index.php">Messages</a></li>
						<?php
						if(!empty($_SESSION['login'])){
							// echo '<li class="leftAlign"><a href="index.php">Profil</a></li>';
							// echo '<li class="rightAlign"><a href="index.php?deconnect=true">Se déconnecter</a></li>';
							echo '<li class="rightAlign"><a href="disconnect.php?page=index.php">Se déconnecter</a></li>';
							echo '<li class="rightAlign login"><a href="users/'.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
							if($_SESSION["userLevel"] == "2") {
								echo '<li class="rightAlign"><a href="admin.php">Administration</a></li>';
							}

						}
						else{
							echo '<li class="rightAlign"><a href="index.php#registerModal">S\'inscrire</a></li>';
							echo '<li class="rightAlign"><a href="index.php#loginModal">Se connecter</a></li>';
						}
						?>
					</ul>
				</div>
				<div class="info">
					<form method="post" action="index.php" class="selecter">
							<div class="selecter">
								<select name="Selectioner une rubirque" id="rubrique">
									<option value="Rubrique">Rubrique</option>
									<option value="XHTML">XHTML</option>
									<option value="CSS">CSS</option>
									<option value="PHP">PHP</option>
								</select>
							</div>
							<div>
								<input type="submit" name="valider" value="Valider" id="valider"/>
							</div>
					</form>
				</div>
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