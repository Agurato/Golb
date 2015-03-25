<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
?>
	<div class="container">
		<!-- HEADER -->
		<div class="header">
			<!-- Login dialog box -->
			<div id="loginModal" class="modalDialog">
				<div>
					<form method="get" action="index.php">
						<p>
							<label for="login">Login</label>
							<input type="text" name="login"/>
							<input type="submit" class="send" value="Login" />
						</p>
						<p>
							<label for="password">Password</label>
							<input type="password" name="password" id"=password"/>
						</p>
					</form>
					<a href="#close" title="Close" class="close">Close</a>
				</div>
			</div>

			<!-- Title & subtitle -->
			<div class="titles">
				<h1>Golb</h1>
				<p class="note">Petit Golb amusant pour les golbers affutés</p>
			</div>			
		</div>

		<!-- CONTENT -->
		<div class="contentmain">
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

		<!-- FOOTER -->
		<div class="footer">
			<p>
				A website by Vincent Monot and Lucas Nicosia
			</p>
		</div>
	</div>
<?php
	endHTML();
?>