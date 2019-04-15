<?php
$page_width   = 966;
$left_width   = $this->hasWidgets('left')  ? 266 : 0;
$right_width  = $this->hasWidgets('right') ? 250 : 0;
$center_width = $page_width - $left_width - $right_width;
?><!DOCTYPE html>
<html>
	<head>
		<pu:head>
		<link rel="stylesheet" type="text/css" href="<?php echo PU_URL_BASE; ?>/themes/<?php echo $this->_name; ?>/styles/theme.css">
		<link rel="stylesheet" type="text/css" href="<?php echo PU_URL_BASE; ?>/themes/<?php echo $this->_name; ?>/styles/<?php echo $this->_layout; ?>.css">
		<!-- <link rel="shortcut icon" type="image/png" href="<?php echo PU_URL_BASE; ?>/images/icons/silk/world.png"> -->
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div class="inside">
					<h1>Plutonium CMS</h1>
					<h2><?php echo ucfirst($this->_name); ?> Theme / <?php echo ucfirst($this->_layout); ?> Layout</h2>
				</div>
			</div>
			<div id="wrapper">
				<?php if ($this->hasWidgets('left')) : ?>
				<div id="left" style="width: <?php echo $left_width; ?>px;">
					<div class="inside"><pu:widgets location="left"></div>
				</div>
				<?php endif; ?>
				<div id="center" style="width: <?php echo $center_width; ?>px;">
					<?php if ($this->hasWidgets('top')) : ?>
					<div id="top">
						<div class="inside"><pu:widgets location="top"></div>
					</div>
					<?php endif; ?>
					<div id="middle">
						<div class="inside">
							<pu:message>
							<pu:module>
						</div>
					</div>
					<?php if ($this->hasWidgets('bottom')) : ?>
					<div id="bottom">
						<div class="inside"><pu:widgets location="bottom"></div>
					</div>
					<?php endif; ?>
				</div>
				<?php if ($this->hasWidgets('right')) : ?>
				<div id="right" style="width: <?php echo $right_width; ?>px;">
					<div class="inside"><pu:widgets location="right"></div>
				</div>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
			<div id="footer">
				<div class="inside" style="font-size: smaller; font-style: italic; text-align: right;">
					<pu:date format="datetime_format_long">
				</div>
			</div>
		</div>
	</body>
</html>
