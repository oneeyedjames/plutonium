<?php if ($this->getVar('id')) : ?>
	<h3>Edit Page</h3>
<?php else : ?>
	<h3>New Page</h3>
<?php endif; ?>

<style>
	input[type="text"], textarea { width: 100%; }
</style>
<form action="/" method="POST">
	<p>
		<label>Name</label>
		<input type="text" name="name" value="<?php echo $this->page->name; ?>">
	</p>
	<p>
		<label>Body</label>
		<textarea name="body" value="<?php echo $this->page->body; ?>"></textarea>
	</p>
	<p>
		<label>Created</label>
		<pu:date format="datetime_format_long" date="<?php echo $this->page->created; ?>">
	</p>
	<p>
		<label>Updated</label>
		<pu:date format="datetime_format_long" date="<?php echo $this->page->updated; ?>">
	</p>
	<p>
		<button type="submit">Save</button>
	<p>
</form>