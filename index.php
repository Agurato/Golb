<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	?>
	<div class="container">
		<div class="header">
			<h1>Golb</h1>
			<p class="note">Petit Golb amusant pour les golbers affutés</p>
		</div>
		<div class="contentmain">
			<div class="content">
				<div class="menu">
					<ul class="topmenu topmenualign">
						<li><a href="index.php">Accueil</a></li>
						<li><a href="index.php">News</a></li>
						<li><a href="index.php">Messages</a></li>
						<li><a href="index.php">Profil</a></li>
						<li><a href="index.php"></a></li>

					</ul>
				</div>
			<div>
				<p>
					Ici contenu du blog
					<!-- <img src="img/example-image.jpg" class="img-rounded img-centered" alt="Photo test"/> -->
				</p>
			</div>
			</div>
			<div class="sidebar">

			</div>
		</div>
		<div class="footer">
			<p>
				A website by Vincent Monot and Lucas Nicosia
			</p>
		</div>
	</div>
	<?php
	endHTML();
?>