<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>The History of Four-Footed Beasts and Serpents</title>
	<style type="text/css">
		body {
			background: #fff;
			color: #000;
			width: 384px;
			margin: 0;
			padding: 20px 0;
			font-family: Times, serif;
			text-align: center;
		}
		h1, p {
			margin: 0;
			padding: 0;
		}
		.container {
			border-top: 6px solid #000; 
			border-bottom: 6px solid #000; 
			padding: 2px 0;
		}
		.title {
			font-size: 24px;
			line-height: 1em;
			border-top: 2px solid #000;
			border-bottom: 2px solid #000;
			padding: 8px 0 10px 0;
			margin-bottom: 30px;
			text-transform: uppercase;
			letter-spacing: 0.25em;
			font-weight: normal;
		}
		.title-small {
			font-size: 20px;
			font-variant: small-caps;
			text-transform: none;
			line-height: 1.5em;

		}
		.edition {
			font-style: italic;
			font-size: 20px;
			margin-top: 20px;
			margin-bottom: 5px;
		}
		.description {
			font-size: 24px;
			line-height: 28px;
			padding-bottom: 1em;
			border-bottom: 2px solid #000;
		}
	</style>
</head>
<body>

	<div class="container">
		<h1 class="title">
			<span class="title-small">The History of</span><br />
			Four-footed Beasts<br />
			<span class="title-small">and</span><br />
			Serpents
		</h1>

	<img class="illustration" src="<?php echo $ROOT_DIRECTORY; ?>img/<?php echo $edition_data[0]; ?>" />

		<p class="edition">
			<?php echo ($delivery_count+1); ?> of <?php echo count($EDITIONS); ?>
		</p>

	<p class="description"><?php echo $edition_data[1] ?></p>
	</div>

</body>
</html>
