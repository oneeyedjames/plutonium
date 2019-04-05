
<table>
	<tr>
		<th>Slug</th>
		<th>Name</th>
		<th>Description</th>
	</tr>
	<?php foreach ($this->modules as $name => $module) : ?>
		<tr>
			<td><?php echo $name; ?></td>
			<td><?php echo $module['package']; ?></td>
			<td><?php echo $module['description']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>