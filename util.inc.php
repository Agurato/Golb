<?php
	include_once('begin.php');
	// beginHTML('Golb','css/style.css');

	function loginForm($loginPage = "login.php") {
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

	function registerForm($registerPage = "register.php") {
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
						<label for="usernameSignup" >Username</label><br />
						<input type="text" name="usernameSignup" id="usernameSignup" />
					</p>
					<p class="registerForm">
						<label for="emailSignup">E-mail address</label><br />
						<input type="text" name="emailSignup" id="emailSignup" />
					</p>
					<p class="registerForm">
						<label for="passwordSignup1">Password</label><br />
						<input type="password" name="passwordSignup1" id="passwordSignup1" maxlength="72"/>
					</p>
					<p class="registerForm">
						<label for="passwordSignup2">Re-enter password</label><br />
						<input type="password" name="passwordSignup2" id="passwordSignup2" maxlength="72"/>
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

	function changePasswordForm($chgPasswordPage = "passwordChange.php") {
	?>
		<div id="chgPasswordModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Wrong password !";
								$count ++;
							}
							if(strpos($error, "2") !== false) {
								if($count > 0) {
									echo "<br />";
								}
								echo "New passwords are different !";
								$count ++;
							}
							echo "</p>";
						}
					}
					echo '<form method="post" action="'.$chgPasswordPage.'">';
				?>
					
				<p class="registerForm">
					<label for="passwordChange1">Actual password</label><br />
					<input type="password" name="passwordChange1" id="passwordChange1" maxlength="72"/>
				</p>
				<p class="registerForm">
					<label for="passwordChange2">New password</label><br />
					<input type="password" name="passwordChange2" id="passwordChange2" maxlength="72"/>
				</p>
				<p class="registerForm">
					<label for="passwordChange3">Re-enter the new password</label><br />
					<input type="password" name="passwordChange3" id="passwordChange3" maxlength="72"/>
				</p>
				<p class="registerForm">
					<a href="index.php#passwordLost" style="float:right;margin-right:5px;">Password lost ?</a><br />
					<input type="submit" class="send" value="Change password" />
				</p>


				</form>
				<a href="" title="Close" class="close" >Close</a>
			</div>
		</div>
	<?php
	}

	function deleteAccountForm($deletePage = "deleteAccount.php") {
	?>
		<div id="delAccountModal" class="modalDialog">
			<div>
				<?php
					if(isset($_GET["error"])) {
						$error = $_GET["error"];
						$count = 0;
						if($error != "") {
							echo "<p>";
							if(strpos($error, "1") !== false) {
								echo "Wrong password !";
							}
							echo "</p>";
						}
					}
					echo '<form method="post" action="'.$deletePage.'">';
				?>
					
				<p class="registerForm">
					<label for="passwordDelete">Password</label><br />
					<input type="password" name="passwordDelete" id="passwordDelete" maxlength="72"/>
				</p>
				<p>
					<input type="radio" name="deleteOption" id="deletePartly" value="deletePartly" checked="checked" />
					<label for="deletePartly">Delete only my account (posts, comments &amp; notes will be saved)</label>
					<br />
					<input type="radio" name="deleteOption" id="deleteAll" value="deleteAll" />
					<label for="deleteAll">Delete my account and all my posts, comments &amp; notes</label>
				</p>
				<p class="registerForm">
					<a href="index.php#passwordLost" style="float:right;margin-right:5px;">Password lost ?</a><br />
					<input type="submit" class="send" value="Delete my account" />
				</p>


				</form>
				<a href="" title="Close" class="close" >Close</a>
			</div>
		</div>
	<?php
	}

	function retrievePasswordForm($retrievePasswordPage = "passwordRetrieve.php") {
	?>
		
	<?php
	}
?>