<form action="index.php" method="post">
	<input type="hidden" name="module" value="system" />
	<input type="hidden" name="resource" value="users" />
	<input type="hidden" name="action" value="login" />
	<fieldset>
		<legend>User Login</legend>
		<div>
			<label for="data_username">Username:</label><br />
			<input id="data_username" type="text" name="data[username]" />
		</div>
		<div>
			<label for="data_password">Password:</label><br />
			<input id="data_password" type="password" name="data[password]" />
		</div>
		<div>
			<input id="data_remember" type="checkbox" name="data[remember]" />
			<label for="data_remember">Remember Me</label>
		</div>
		<div><input type="submit" value="Login" /></div>
	</fieldset>
	<ul>
		<li><a href="#">Forgot your password?</a></li>
		<li><a href="#">Forgot your username?</a></li>
		<li><a href="#">Create an account.</a></li>
	</ul>
</form>