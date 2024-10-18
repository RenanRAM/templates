<!DOCTYPE html>
<html>
<head>
	<?php echo $meta; ?>
	<?php echo $css; ?>
	<title>teste</title>
</head>
<body>
	<?php
	echo <<<CON
		<h1>$titulo, esta é uma view de teste</h1>
		<h2>Hoje é dia: $dia</h2>
	CON;
	?>
	
</body>
</html>