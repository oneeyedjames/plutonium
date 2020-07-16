<style>
	table.wdg-list { width: 100%; }
	table.wdg-list th { text-align: left; }
</style>
<table class="wdg-list">
	<caption>Enabled Widgets</caption>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th></th>
	</tr>
	<?php foreach ($this->enabled as $widget) : ?>
		<tr>
			<td><?php echo $widget->name; ?></td>
			<td><?php echo $widget->descrip; ?></td>
			<td style="text-align: right;">
				<form action="<?php echo $this->buildLink($widget); ?>" method="POST">
					<input type="hidden" name="_method" value="DELETE">
					<button type="submit">Disable</button>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<table class="wdg-list">
	<caption>Available Widgets</caption>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th></th>
	</tr>
	<?php foreach ($this->available as $name => $widget) : ?>
		<tr>
			<td><?php echo $widget['package']; ?></td>
			<td><?php echo $widget['description']; ?></td>
			<td style="text-align: right;">
				<form action="" method="POST">
					<input type="hidden" name="name" value="<?php echo $name; ?>">
					<button type="submit">Enable</button>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
