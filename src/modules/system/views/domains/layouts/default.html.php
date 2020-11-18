<style type="text/css">
	table.dom-list { width: 100%; }
	table.dom-list caption { text-align: left; }
	table.dom-list th { text-align: left; }
</style>
<table class="dom-list">
	<caption>Domains</caption>
	<thead>
		<tr>
			<th>Name</th>
			<th>Domain</th>
			<th>Host</th>
			<th>Created</th>
			<th>Updated</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->domains as $domain) :
			$host = $this->hosts[$domain->host_id]; ?>
			<tr>
				<td><?php echo $domain->name; ?></td>
				<td><?php echo $domain->domain; ?></td>
				<td>
					<a href="/hosts/<?php echo $host->id; ?>/"><?php echo $host->name; ?></a>
				</td>
				<td>
					<pu:date format="datetime_format_long"
						time="<?php echo $domain->created; ?>">
				</td>
				<td>
					<pu:date format="datetime_format_long"
						time="<?php echo $domain->updated; ?>">
				</td>
				<td>
					<form action="/domains/<?php echo $domain->id; ?>/" method="POST">
						<a href="/domains/<?php echo $domain->id; ?>/form/">Edit</a>
						<input type="hidden" name="_method" value="DELETE">
						<input type="submit" value="Delete">
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<a href="/domains/form/">Add New</a>
