<?php
	include_once('begin.php');
	// beginHTML('Golb','css/style.css');

	function loginForm() {
		?>
		<!-- Login dialog box -->
			<div id="loginModal" class="modalDialog">
				<div>
					<form method="post" action="index.php">
						<p>
							<label for="login" >Login</label>
							<input type="text" name="login" />
							<input type="submit" class="send" value="Login" />
						</p>
						<p>
							<label for="password">Password</label>
							<input type="password" name="password" id="password" />
						</p>
					</form>
					<a href="#close" title="Close" class="close" >Close</a>
				</div>
			</div>
		<?php
	}



	// endHTML()
?>