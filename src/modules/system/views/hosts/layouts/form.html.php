<style type="text/css">
	form.host-form label { display: block; font-weight: bold; }
	form.host-form input[type="text"] { width: 100%;	 box-sizing: border-box; }
	form.host-form textarea { width: 100%; box-sizing: border-box; }
	form.host-form div.form-field { margin: 1em 0; }
</style>
<form action="<?php echo $this->action; ?>" method="post" class="host-form">
	<?php if ($this->host->id) : ?>
		<input type="hidden" name="_method" value="PUT">
	<?php endif; ?>
	<div class="form-field" style="width: 50%; float: left;">
		<label for="host-name">Name</label>
		<input id="host-name" type="text" name="data[name]"
			value="<?php echo $this->host->name; ?>">
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label for="host-slug">Slug</label>
		<input id="host-slug" type="text" disabled="disabled"
			value="<?php echo $this->host->slug; ?>">
	</div>
	<div class="form-field" style="clear: left;">
		<label for="host-descrip">Description</label>
		<textarea id="host-descrip" name="data[descrip]"><?php echo $this->host->descrip; ?></textarea>
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label>Created</label>
		<pu:date format="datetime_format_long"
			time="<?php echo $this->host->created; ?>">
	</div>
	<div class="form-field" style="width: 50%; float: left;">
		<label>Updated</label>
		<pu:date format="datetime_format_long"
			time="<?php echo $this->host->updated; ?>">
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
