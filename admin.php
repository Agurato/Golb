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
			loginForm();
			registerForm("utils/register.php?page=admin.php");
			deleteAccountForm("utils/deleteAccountAdmin.php");
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
					echo '<li class="rightAlign loginLink"><a href="users/'.strtolower($_SESSION['login']).'">'.$_SESSION['login'].'</a></li>';
					if($_SESSION["userLevel"] == "2") {
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
			<?php
				if(! empty($_SESSION['userLevel'])) {
					if($_SESSION['userLevel'] == 2) {
						echo usersTable("users/accounts.csv", "img/");
					}
				}
				else {
					header('Location: index.php');
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