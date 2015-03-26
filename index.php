<?php
	include_once('begin.php');
	include_once('util.inc.php');
	beginHTML('Golb','css/style.css');
?>
	<div class="container">
		<!-- HEADER -->
		<div id="header">
			
			<?php
				loginForm();
			?>

			<!-- Title & subtitle -->
			<div class="titles">
				<h1>Golb</h1>
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
						<li class="leftAlign"><a href="index.php">Profil</a></li>
						<li class="rightAlign"><a href="index.php">Administration</a></li>
						<li class="rightAlign"><a href="index.php">S'inscrire</a></li>
						<li class="rightAlign"><a href="index.php#loginModal">Se connecter</a></li>
					</ul>
				</div>
				<div class="info">
					<p>
						Ici contenu du blog
						<!-- <img src="img/example-image.jpg" class="img-rounded img-centered" alt="Photo test"/> -->
					</p>
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