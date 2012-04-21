<h3>Hello, <?php echo $this->user->realname; ?></h3>
<form action="index.php" method="post">
	<input type="hidden" name="module" value="system" />
	<input type="hidden" name="resource" value="users" />
	<input type="hidden" name="action" value="logout" />
	<input type="submit" value="Sign Out" />
</form>