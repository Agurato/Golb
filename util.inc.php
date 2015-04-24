<?php
	include_once('begin.php');
	// beginHTML('Golb','css/style.css');

	function loginForm($loginPage="login.php") {
		// Input the form to log in
		// $loginPage = page where infos are checked and where you are logged in
			// If ?page=something : redirect to something after you are logged in (home page otherwise)
	?>
		<!-- Login dialog box -->
		<div id="loginModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						if($_GET["error"] == true) {
							echo "<p>Username or password incorrect</p>";
						}
						else {
							echo "<p>No error !</p>";
						}
					}
					echo '<form method="post" action="'.$loginPage.'">';
				?>
					<div>
						<p class="loginForm">
							<label for="login">Username</label>
							<input type="text" name="login" id="login" />
						</p>
					</div>
					<div class="leftForm">
						<p class="loginForm">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" />
							<input type="submit" class="send" value="Login" />
						</p>
					</div>
				</form>
				<a href="" title="Close" class="close" >Close</a>
			</div>
		</div>
	<?php
	}

	function registerForm($registerPage="register.php") {
	?>
		<!-- Register dialog box -->
		<div id="registerModal" class="modalDialog">
			<div>
				<!-- ERROR DISPLAY -->
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Username already exists";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Invalid username";
								$count ++;
							}
							if(strpos($error, "3") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Email address already exists";
								$count ++;
							}
							if(strpos($error, "4") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Invalid email address";
								$count ++;
							}
							if(strpos($error, "5") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "Passwords do not match";
								$count ++;
							}
							echo "</p>";
						}
					}
					echo '<form method="post" action="'.$registerPage.'">';
				?>
					<p class="registerForm">
						<label for="username" >Username</label><br />
						<input type="text" name="username" id="username" />
					</p>
					<p class="registerForm">
						<label for="email">E-mail address</label><br />
						<input type="text" name="email" id="email" />
					</p>
					<p class="registerForm">
						<label for="password1">Password</label><br />
						<input type="password" name="password1" id="password1" maxlength="72"/>
					</p>
					<p class="registerForm">
						<label for="password2">Re-enter password</label><br />
						<input type="password" name="password2" id="password2" maxlength="72"/>
					</p>
					<p class="registerForm">
						<input type="submit" class="send" value="Register" />
					</p>
				</form>
				<a href="" title="Close" class="close" >Close</a>
			</div>
		</div>
	<?php
	}

	// endHTML()
?>