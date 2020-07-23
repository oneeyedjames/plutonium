<style type="text/css">
	form.dom-form label { display: block; font-weight: bold; }
	form.dom-form div.form-field { margin: 1em 0; }
	form.dom-form input[type="text"],
	form.dom-form select {
		width: 100%;
		box-sizing: border-box;
		font-size: 11px;
		line-height: 16px;
		padding: 1px 2px;
		border: 1px solid;
		height: 20px;
	}
</style>
<form action="<?php echo $this->action; ?>" method="post" class="dom-form">
	<?php if ($this->domain->id) : ?>
		<input type="hidden" name="_method" value="PUT">
	<?php endif; ?>
	<div class="form-field">
		<label for="domain-name">Name</label>
		<input id="domain-name" type="text" name="domain[name]"
			value="<?php echo $this->domain->name; ?>">
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label for="domain-domain">Domain</label>
		<input id="domain-domain" type="text" name="domain[domain]"
			value="<?php echo $this->domain->domain; ?>">
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label for="domain-host">Host</label>
		<select id="domain-host" name="domain[host_id]">
			<?php foreach ($this->hosts as $host) : ?>
				<option value="<?php echo $host->id; ?>"<?php
					if ($this->domain->host_id == $host->id)
						echo ' selected="selected"';
				?>><?php echo $host->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<br style="clear: left;">
	<div class="form-field" style="width: 50%; float: left;">
		<label>Created</label>
		<pu:date format="datetime_format_long"
			time="<?php echo $this->domain->created; ?>">
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label>Updated</label>
		<pu:date format="datetime_format_long"
			time="<?php echo $this->domain->updated; ?>">
	</div>
	<div style="clear: left;">
		<input type="Submit" value="Save">
	</div>
</form>
<script type="text/javascript">
	var nameField = document.getElementById('host-name');
	var slugField = document.getElementById('host-slug');

	nameField.addEventListener('input', function() {
		slugField.value = nameField.value.replace(/\s+/, '-').toLowerCase();
	});
</script>
