<?php
ini_set('display_errors','Off');
require_once("report.php");$cabecera=new Ventas(); ?>
<! DOCTYPE HTML>
<html>
	<head>
		<meta content="600" http-equiv="REFRESH"></meta>
		<link rel="stylesheet" type="text/css" href="report.css" >
		<link rel="stylesheet" type="text/css" href="Themes/<?php echo $cabecera->currentTheme();?>" >
		<script type="text/javascript" src="report.js"></script>
	</head>
	<body>
<?php $cabecera->actualizar();?>
			<div class="modulo"><?php $cabecera->graficarDia();?></div>
			<div class="modulo"><?php $cabecera->graficarMes();?></div>
			<div class="ultima"><?php $cabecera->last();?></div>
			<div class="icons"><?php $cabecera->window();?></div>
			<div class="tabladatos"><?php $cabecera->tablasdatos();?></div>
	</body>
</html>