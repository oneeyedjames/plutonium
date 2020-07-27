<!DOCTYPE html>
<html>
	<head>
		<pu:head>

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

		<link rel="stylesheet" type="text/css" href="assets/barista.min.css">
		<script type="text/javascript" src="assets/barista.min.js"></script>
	</head>
	<body>
		<header class="navbar">
			<div class="wrapper">
				<a class="icon" href="/">
					<pu:trans phrase="title">
				</a>
			</div>
		</header>
		<article class="wrapper">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<pu:widgets location="sidebar">
				</div>
				<div class="col-md-8 col-lg-9">
					<pu:widgets location="header">
					<pu:module>
				</div>
				<div class="col-xs-12">
					<pu:widgets location="footer">
				</div>
			</div>
		</article>
	</body>
</html>
