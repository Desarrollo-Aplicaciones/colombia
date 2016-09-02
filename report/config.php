<?php
if ($_POST['meta']>0 && $_POST['tema'])
{
	$ar = fopen(dirname(__FILE__)."/report.dat","w") or
	die("Problemas en la configuración");
	fputs($ar,"meta:".$_POST['meta']);
	fputs($ar,"\n");
	fputs($ar,"tema:".$_POST['tema']);
	fputs($ar,"\n");
	$link = explode($_SERVER['PHP_SELF']);
	//$dir=
	header( "Location:index.php");
}
else{
	require_once("report.php");
	$cabecera = new Ventas();
	$tema = explode(".", $cabecera->currentTheme());
	?>
	<form action="config.php" method="post">
	<p>Meta: &nbsp;<input type="number" name="meta" value="<?php echo $cabecera->meta;?>"/></p>
	<p>Tema: &nbsp;<select name="tema" >
	<?php
	if ($gestor = opendir('Themes')) {
		while (false !== ($entrada = readdir($gestor))) {
			if ($entrada != "." && $entrada != "..") {
				$opcion = explode(".",$entrada);
				echo '<option value="'.$entrada.'"';
				if ($opcion[0]==$tema[0])
					echo "selected";
				echo '>'.$opcion[0].'</option>';
			}
			}
			closedir($gestor);
	}
	?>
	</select> (actual: <?php echo $tema[0]; ?>)
	</p>
	<input type="submit" value="Guardar"/>
	</form>
<?php }?>