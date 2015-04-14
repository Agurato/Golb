<?php
	include('begin.php');
	include('util.inc.php');
	beginHTML('Golb','css/style.css');
	beginSession();
?>
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
							//echo '<li class="rightAlign"><a href="index.php">Administration</a></li>';
							echo '<li class="rightAlign"><a href="index.php?deconnect=true">Se déconnecter</a></li>';
							echo '<li class="rightAlign login"><a href="index.php">'.$_SESSION['login'].'</a></li>';

						}
						else{
							echo '<li class="rightAlign"><a href="index.php">S\'inscrire</a></li>';
							echo '<li class="rightAlign"><a href="index.php#loginModal">Se connecter</a></li>';
						}
						?>
					</ul>
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
	session_start();
	session_destroy();
	unset($_SESSION['login']);
	echo '<meta http-equiv="refresh" content="0;URL='.$_GET["page"].'" /> ';
	endHTML();
?>