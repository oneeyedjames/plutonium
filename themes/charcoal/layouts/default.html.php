<?php
global $registry;

$page_width   = 966;
$left_width   = $this->countWidgets('left')  ? 266 : 0;
$right_width  = $this->countWidgets('right') ? 250 : 0;
$center_width = $page_width - $left_width - $right_width;
?><!DOCTYPE html>
<html>
	<head>
		<p:head />
		<link rel="stylesheet" type="text/css" href="<?php echo P_BASE_URL; ?>/themes/<?php echo $this->_name; ?>/styles/theme.css">
		<link rel="stylesheet" type="text/css" href="<?php echo P_BASE_URL; ?>/themes/<?php echo $this->_name; ?>/styles/<?php echo $this->_layout; ?>.css">
		<link rel="shortcut icon" type="image/png" href="<?php echo P_BASE_URL; ?>/images/icons/silk/world.png">
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
				<?php if ($this->countWidgets('left')) { ?>
				<div id="left" style="width: <?php echo $left_width; ?>px;">
					<div class="inside"><p:widgets location="left"></div>
				</div>
				<?php } ?>
				<div id="center" style="width: <?php echo $center_width; ?>px;">
					<?php if ($this->countWidgets('top')) { ?>
					<div id="top">
						<div class="inside"><p:widgets location="top"></div>
					</div>
					<?php } ?>
					<!--<pre class="inside"><?php
						//print_r(Plutonium_Request::getInstance()->toArray());
						//print_r(Plutonium_Session::getInstance()->toArray());
					?></pre>-->
					<div id="middle">
						<div class="inside">
							<p:message>
							<p:module>
						</div>
					</div>
					<?php if ($this->countWidgets('bottom')) { ?>
					<div id="bottom">
						<div class="inside"><p:widgets location="bottom"></div>
					</div>
					<?php } ?>
				</div>
				<?php if ($this->countWidgets('right')) { ?>
				<div id="right" style="width: <?php echo $right_width; ?>px;">
					<div class="inside"><p:widgets location="right"></div>
				</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div id="footer">
				<div class="inside" style="font-size: smaller; font-style: italic; text-align: right;">
					<p:date format="<?php echo $registry->language->translate('date_format_long'); ?>">
				</div>
			</div>
		</div>
	</body>
</html>
