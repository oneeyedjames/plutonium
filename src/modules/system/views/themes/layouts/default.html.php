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
	<?php foreach ($this->enabled as $theme) : ?>
		<tr>
			<td><?php echo $theme->name; ?></td>
			<td><?php echo $theme->descrip; ?></td>
			<td style="text-align: right;">
				<a href="/themes?action=disable&name=<?php echo $theme->slug; ?>">Disable</a>
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
	<?php foreach ($this->available as $name => $theme) : ?>
		<tr>
			<td><?php echo $theme['package']; ?></td>
			<td><?php echo $theme['description']; ?></td>
			<td style="text-align: right;">
				<a href="/themes?action=enable&name=<?php echo $name; ?>">Enable</a>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
