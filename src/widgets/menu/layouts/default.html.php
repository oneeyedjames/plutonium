<ul class="wig-menu">
	<?php foreach ($this->pages as $page) : ?>
	<li style="padding-left: <?php echo ($page->depth - 1) * 12; ?>px">
		<a href="<?php echo $page->url; ?>"><?php echo $page->title; ?></a>
	</li>
	<?php endforeach; ?>
</ul>