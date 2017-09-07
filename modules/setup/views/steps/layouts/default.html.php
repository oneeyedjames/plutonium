<h1>Setup: Database</h1>

<form action="/" method="post">
	<input type="hidden" name="module" value="setup" />
	<input type="hidden" name="resource" value="steps" />
	<input type="hidden" name="action" value="database" />
	<fieldset>
		<legend>Database Credentials</legend>
		<div>
			<label for="data_database">Database:</label><br>
			<input id="data_database" type="text" name="data[database]" />
		<div>
			<label for="data_username">Username:</label><br />
			<input id="data_username" type="text" name="data[username]" />
		</div>
		<div>
			<label for="data_password">Password:</label><br />
			<input id="data_password" type="password" name="data[password]" />
		</div>
		<div><input type="submit" value="Create" /></div>
	</fieldset>
</form>
