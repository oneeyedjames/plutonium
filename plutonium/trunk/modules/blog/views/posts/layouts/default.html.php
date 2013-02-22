<style type="text/css">
	.date { font-size: smaller; font-style: italic; }
	.subject { font-size: larger; font-weight: bold; }
</style>
<h3><?php echo count($this->posts); ?> Posts</h3>
<br />
<?php foreach ($this->posts as $post) { ?>
<div class="post">
	<pre><?php echo $post->feed->name; ?></pre>
	<div class="subject"><?php echo $post->subject; ?></div>
	<div class="date">
		Posted by <?php echo $post->user_id; ?>
		on <?php echo $post->created->format('date_format_long'); ?>
		at <?php echo $post->created->format('time_format_long'); ?>
	</div>
	<br />
	<div class="message"><?php echo $post->message; ?></div>
	<?php if ($post->updated > $post->created) { ?>
	<br />
	<div class="date">Updated <?php echo $post->updated; ?></div>
	<?php } ?>
</div>
<?php } ?>
