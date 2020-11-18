<style type="text/css">
	table.host-list { width: 100%; }
	table.host-list caption { text-align: left; }
	table.host-list th { text-align: left; }
</style>
<table class="host-list">
	<caption>Hosts</caption>
	<thead>
		<tr>
			<th>Name</th>
			<th>Created</th>
			<th>Updated</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->hosts as $host) : ?>
			<tr>
				<td><?php echo $host->name; ?></td>
				<td><pu:date time="<?php echo $host->created; ?>"></td>
				<td><pu:date time="<?php echo $host->updated; ?>"></td>
				<td>
					<a href="/hosts/<?php echo $host->id; ?>/">View</a>
					<a href="/hosts/<?php echo $host->id; ?>/form/">Edit</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<a href="/hosts/form/">Add New</a>
