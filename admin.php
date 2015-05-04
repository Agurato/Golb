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
				<a href="index.php"><li class="leftAlign">Accueil</li></a>
				<a href="index.php"><li class="leftAlign">News</li></a>
				<a href="index.php"><li class="leftAlign">Messages</li></a>
				<?php
				if(!empty($_SESSION['login'])){
					echo '<a href="utils/disconnect.php?page=index.php"><li class="rightAlign logoutLink">Se déconnecter</li></a>';
					echo '<a href="users/'.strtolower($_SESSION['login']).'"><li class="rightAlign loginLink">'.$_SESSION['login'].'</li></a>';
					if($_SESSION["userLevel"] == "2") {
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