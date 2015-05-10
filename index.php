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
			$linkDB = mysqli_connect(SERVER_NAME, USER_NAME, USER_PASS, DB_NAME);
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

			<form method="get" action="index.php" class="selecter">
				<div>
				<select name="category" id="category">
					<option value="all">Tout</option>
					<?php
						$options = mysqli_query($linkDB, 'SELECT * FROM `category`');
						for($i=0 ; $i<mysqli_num_rows($options) ; $i++) {
							$row = mysqli_fetch_assoc($options);
							echo '<option value="'.$row["name"].'"';
							if(! empty($_GET["category"]) && $_GET["category"] == $row["name"]) {
								echo ' selected="selected"';
							}
							echo ' >'.$row["name"].'</option>'."\n";
						}
					?>
				</select>
				<select name="order" id="order">
				<?php
					echo '<option value="idDESC"';
					if(! empty($_GET["order"]) && $_GET["order"] == 'idDESC') {
						echo ' selected="selected"';
					}
					echo ' >Du plus récent au plus ancien</option>';

					echo '<option value="idASC"';
					if(! empty($_GET["order"]) && $_GET["order"] == 'idASC') {
						echo ' selected="selected"';
					}
					echo ' >Du plus ancien au plus récent</option>';

					echo '<option value="dateDESC"';
					if(! empty($_GET["order"]) && $_GET["order"] == 'dateDESC') {
						echo ' selected="selected"';
					}
					echo ' >Du plus récent au plus ancien (modifié)</option>';

					echo '<option value="dateASC"';
					if(! empty($_GET["order"]) && $_GET["order"] == 'dateASC') {
						echo ' selected="selected"';
					}
					echo ' >Du plus ancien au plus récent (modifié)</option>';

					echo '<option value="score"';
					if(! empty($_GET["order"]) && $_GET["order"] == 'score') {
						echo ' selected="selected"';
					}
					echo ' >Du mieux noté au moins bien noté</option>';
				?>
				</select>

				<input type="submit" name="filter" value="Valider" id="filter" />
				</div>
			</form>

			<?php
				if(!empty($_SESSION["login"]) && $_SESSION["userLevel"] > 1) {
					echo '<p><a href="index.php#newPostModal">Nouveau post</a></p>';
				}
				else if(empty($_SESSION["login"])) {
					echo '<p><a href="index.php#loginModal">Nouveau post</a></p>';
				}
				else {
					echo '<p>Votre inscription est en cours</p>';
				}

				$page = 1;
				if(isset($_GET["page"])) {
					if(! ($page = intval($_GET["page"]))) {
						$page = 1;
					}
					else if($page < 1) {
						$page = 1;
					}
					else {
						$page = $_GET["page"];
					}
				}

				$url = explode('/', $_SERVER["REQUEST_URI"])[3];
				if(isset($_GET["filter"])) {
					echo '<meta http-equiv="refresh" content="0; url='.str_replace('&', '&amp;', explode('&filter=', $url)[0]).'" />';
				}
				
				$category = 'all';
				if(! empty($_GET["category"])) {
					$category = $_GET["category"];
				}
				$order = 'id';
				if(! empty($_GET["order"])) {
					$order = $_GET["order"];
				}
				
				echo getPosts($linkDB, ($page-1)*20, $category, $order);

				echo accessPages($linkDB, $url, $page);

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