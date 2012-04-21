<?php

if ($dir = opendir(dirname(__FILE__))) {
	while (($file = readdir($dir)) !== FALSE) {
		if (substr($file, -4) == '.png') {
			$icons[] = $file;
		}
	}
}

$imin = 0;
$imax = count($icons);
$icol = (int) ceil($imax / 6);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>FamFamFam Silk Icons</title>
	</head>
	<body>
		<?php for ($i = $imin; $i < $imax; $i++) { ?>
		<?php if ($i % $icol == 0) { ?>
		<ul style="list-style: none; float: left; width: 215px; margin: 0px; padding: 0px;">
		<?php } ?>
			<li style="font-family: sans-serif; font-size: small;">
				<img src="<?php echo $icons[$i]; ?>" />
				<span><?php echo $icons[$i]; ?></span>
			</li>
		<?php if ($i % $icol == $icol - 1) { ?>
		</ul>
		<?php } ?>
		<?php } ?>
	</body>
</html>
