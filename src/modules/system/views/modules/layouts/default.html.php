<style>
	table.mod-list { width: 100%; }
	table.mod-list th { text-align: left; }
</style>
<table class="mod-list">
	<caption>Enabled Modules</caption>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th></th>
	</tr>
	<?php foreach ($this->enabled as $module) : ?>
		<tr>
			<td><?php echo $module->name; ?></td>
			<td><?php echo $module->descrip; ?></td>
			<td style="text-align: right;">
				<?php if ($module->slug != 'system') : ?>
					<form action="<?php echo $this->buildLink($module); ?>" method="POST">
						<input type="hidden" name="_method" value="DELETE">
						<button type="submit">Disable</button>
					</form>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<table class="mod-list">
	<caption>Available Modules</caption>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th></th>
	</tr>
	<?php foreach ($this->available as $name => $module) : ?>
		<tr>
			<td><?php echo $module['package']; ?></td>
			<td><?php echo $module['description']; ?></td>
			<td style="text-align: right;">
				<form action="" method="POST">
					<input type="hidden" name="name" value="<?php echo $name; ?>">
					<button type="submit">Enable</button>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
