<?php 
echo "<br>Cambiado8<br>";




$command = "wget -q http://www.farmalisto.com.co/admin8256/searchcron.php?token=HcY7Naja";
$output = shell_exec($command);
echo "<pre>carga de productos faltantes: $output</pre>";


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

sleep(3);
$command = "ls -ln /home/ubuntu/bk_bd_search/";
$output = shell_exec($command);
echo "<pre>Anterior: $output</pre>";

$command = "rm -rf /home/ubuntu/bk_bd_search/bk_mysq_prod.sql";
$output = shell_exec($command);
//echo "<pre>$output</pre>";

$command = "ls -ln /home/ubuntu/bk_bd_search/";
$output = shell_exec($command);
echo "<pre>Luego borrar $output</pre>";

/* conexion cambiar quitar comentario

$command = "mysqldump -u farmalisto  -pF4rm4l1st02015** -hcolombia.cuznbgafgkfl.us-east-1.rds.amazonaws.com -P3808 farmalisto_colombia ps_search_index ps_search_word --compatible=postgresql --skip-triggers --compact --no-create-info > /home/ubuntu/bk_bd_search/bk_mysq_prod.sql";
$output = shell_exec($command);*/
//echo "<pre>bk mysql: $output</pre>";

$command = "ls -ln /home/ubuntu/bk_bd_search/";
$output = shell_exec($command);
echo "<pre>Nuevo Generado: $output</pre>";


$command = "sed -i \"1i set schema 'search'; TRUNCATE TABLE  ps_search_index; TRUNCATE TABLE ps_search_word;\" /home/ubuntu/bk_bd_search/bk_mysq_prod.sql";
$output = shell_exec($command);
echo "<br><pre>insertar texto truncate: $output</pre>";


$command = "export PGPASSWORD='nyT.19*xS'; psql -U bus_col -h search.cuznbgafgkfl.us-east-1.rds.amazonaws.com -d farmalisto_colombia < /home/ubuntu/bk_bd_search/bk_mysq_prod.sql";
$output = shell_exec($command);
echo "<pre>insertar postgresql $output</pre>";

?>