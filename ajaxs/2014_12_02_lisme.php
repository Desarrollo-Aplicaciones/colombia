<?php
/*header( 'Content-type: text/html; charset=iso-8859-1' );*/

header("Content-Type: application/json");
	header ("Expires: Mon, 31 Mar 2014 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	

require(dirname(__FILE__).'/../config/config.inc.php');

$option='--';
if(isset($_GET['option'])&&!empty($_GET['option']))
{
$option=$_GET['option'];
} 


$search = (isset($_POST['input']) ) ? $_POST['input'] : $_GET['input'];

$mysqli = new mysqli(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

  /* comprobar conexión */
  if ($mysqli->connect_errno) {
      printf("Conexión fallida: %s\n", $mysqli->connect_errno);
      return false;
      exit();
  }
   $par_busca = explode(" ", $search);
   $query ='';
   
  if ($option === 'medico') {

 
    $query = "SELECT a.id_medico, a.nombre, CONCAT('[',GROUP_CONCAT(c.nombre SEPARATOR \", \"),']') especialidad
              FROM ps_medico a INNER JOIN ps_medic_especialidad b
              ON (a.id_medico = b.id_medico)
              INNER JOIN ps_especialidad_medica c 
              ON (c.id_especialidad = b.id_especialidad)
              WHERE a.nombre like '%" . $par_busca[0] . "%'";


    for ($i = 1; $i < count($par_busca); $i++) {

        if (strlen($par_busca[$i]) >= 3) {
            $query.=" AND  a.nombre like '%" . $par_busca[$i] . "%' ";
        }
    }

    $query.=" GROUP BY a.id_medico ORDER BY a.nombre ASC LIMIT 0, 10";
    
 if ($result = $mysqli->query($query)) {

		echo "{\"results\": [";
		
    /* obtener array asociativo */
    while ($row = $result->fetch_assoc()) {
 
	    $arr[] = "{\"id\": \"".$row['id_medico']."\", \"value\": \"".utf8_encode($row['nombre'])."\", \"info\": \"".utf8_encode($row['especialidad'])."\"}";
    }
    echo implode(", ", $arr);
		echo "]}";
    /* liberar el resultset */
    $result->free();
}   
} elseif ($option === 'cupon') {
    $query =    "select rule.id_cart_rule,rule.`code`,rule.description
                    from
                    ps_cart_rule rule 
                    WHERE rule.active=1
                    AND rule.date_from < '2014-08-27 03:12:00' and rule.date_to > '2014-08-27 03:12:00' AND
                    rule.description like '%" . $par_busca[0] . "%'";

    for ($i = 1; $i < count($par_busca); $i++) {

        if (strlen($par_busca[$i]) >= 3) {
            $query.=" AND  rule.description '%" . $par_busca[$i] . "%' ";
        }
    }
    
    if ($result = $mysqli->query($query)) {

		echo "{\"results\": [";
		
    /* obtener array asociativo */
    while ($row = $result->fetch_assoc()) {
 
	    $arr[] = "{\"id\": \"".$row['id_cart_rule']."\", \"value\": \"".utf8_encode($row['description'])."\", \"info\": \"".utf8_encode($row['code'])."\"}";
    }
    echo implode(", ", $arr);
		echo "]}";
    /* liberar el resultset */
    $result->free();
}
}





/* cerrar la conexión */
$mysqli->close();
?>