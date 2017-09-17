<h1>Setup: Database</h1>

<form action="/" method="post">
	<input type="hidden" name="module" value="system" />
	<input type="hidden" name="resource" value="setup" />
	<input type="hidden" name="action" value="database" />
	<fieldset>
		<legend>Database Setup</legend>
		<div>
			<label for="data_hostname">Server Name:</label><br />
			<input id="data_hostname" type="text" name="data[hostname]" value="localhost" />
		</div>
		<div>
			<label for="data_dbname">Database Name:</label><br>
			<input id="data_dbname" type="text" name="data[dbname]" value="plutonium"/>
		</div>
		<div>
			<label for="data_driver">Database Type:</label><br />
			<select id="data_driver" name="data[driver]">
				<option value="MySQLi" selected="selected">MySQLi (Recommended)</option>
				<option value="MySQL">MySQL (Legacy)</option>
				<option value="PostgreSQL">PostgreSQL</option>
				<option value="SQLite3">SQLite</option>
			</select>
		</div>
		<div>
			<label for="data_username">Username:</label><br />
			<input id="data_username" type="text" name="data[username]" value="plutonium" />
		</div>
		<div>
			<label for="data_password">Password:</label><br />
			<input id="data_password" type="password" name="data[password]" value="plutonium" />
		</div>
		<div><input type="submit" value="Create" /></div>
	</fieldset>
</form>
