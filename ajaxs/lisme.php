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

$url_post = explode(':', _DB_SERVER_);

$mysqli = null;

if ( count($url_post) > 1 ) {
  
  $mysqli = new mysqli($url_post[0], _DB_USER_, _DB_PASSWD_, _DB_NAME_, $url_post[1]);

} else {

  $mysqli = new mysqli(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

}

  /* comprobar conexión */
  if ($mysqli->connect_errno) {
      printf("Conexión fallida: %s\n", $mysqli->connect_errno);
      return false;
      exit();
  }
   $par_busca = explode(" ", $search);
   $query ='';
   
  if ($option === 'medico') {

 
    $query = "SELECT med_rule.id_cart_rule, a.id_medico,  a.nombre, CONCAT('[',GROUP_CONCAT(c.nombre SEPARATOR \", \"),']') especialidad, rule.minimum_amount, rule.reduction_percent,reduction_amount
              FROM ps_medico a INNER JOIN ps_medic_especialidad b
              ON (a.id_medico = b.id_medico)
              INNER JOIN ps_especialidad_medica c 
              ON (c.id_especialidad = b.id_especialidad)
              INNER JOIN ps_medico_rule med_rule
              ON(a.id_medico = med_rule.id_medico)
              INNER JOIN ps_cart_rule rule
              ON (med_rule.id_cart_rule = rule.id_cart_rule )
              WHERE a.nombre like '%" . $par_busca[0] . "%'";


    for ($i = 1; $i < count($par_busca); $i++) {

        if (strlen($par_busca[$i]) >= 3) {
            $query.=" AND  a.nombre like '%" . $par_busca[$i] . "%' ";
        }
    }

    $query.=" GROUP BY a.id_medico, med_rule.id_cart_rule ORDER BY a.nombre ASC LIMIT 0, 10";
    
    $result = $mysqli->query($query);
//    echo'<pre>';
//    print_r($query); exit();
    if ( $result->num_rows > 0) {

		echo "{\"results\": [";
		
    /* obtener array asociativo */
    while ($row = $result->fetch_assoc()) {
        $cadena="{\"id\": \"".$row['id_cart_rule']."\", \"value\": \"".utf8_encode($row['nombre'])."\", \"info\": \"".utf8_encode($row['especialidad'])."\""
                . ", \"minimum_amount\":\"".$row['minimum_amount']."\",\"reduction_percent\":\"".$row['reduction_percent']."\",\"reduction_amount\":\"".$row['reduction_amount']."\"}";
        // eliminar saltos de linea
        $arr[] =  preg_replace("/[\n|\r|\n\r]/", ' ', $cadena);
    }
    echo implode(", ", $arr);
		echo "]}";
    /* liberar el resultset */
    $result->free();
} else {
  echo "{\"results\": []}";  
}  
} elseif ($option === 'cupon') {
     $date = date('Y-m-d H:i:s');
     
    $query =    "select rule.id_cart_rule,rule.`code`,rule.description, rule.minimum_amount, rule.reduction_percent,reduction_amount
                    from
                    ps_cart_rule rule 
                    WHERE rule.active=1
                    AND '" . $date . "' BETWEEN rule.date_from  AND rule.date_to AND
                    rule.description like '%" . $par_busca[0] . "%'";

    for ($i = 1; $i < count($par_busca); $i++) {

        if (strlen($par_busca[$i]) >= 3) {
            $query.=" AND  rule.description '%" . $par_busca[$i] . "%' ";
        }
    }
   // echo $query;    exit();
    $result = $mysqli->query($query);
    if ( $result->num_rows > 0 ) {

		echo "{\"results\": [";
		
    /* obtener array asociativo */
    while ($row = $result->fetch_assoc()) {

        $cadena="{\"id\": \"".$row['id_cart_rule']."\", \"value\": \"".utf8_encode($row['description'])."\", \"info\": \"".utf8_encode($row['code'])."\""
                . ", \"minimum_amount\":\"".$row['minimum_amount']."\",\"reduction_percent\":\"".$row['reduction_percent']."\",\"reduction_amount\":\"".$row['reduction_amount']."\"}";
        // eliminar saltos de linea
        $arr[] =  preg_replace("/[\n|\r|\n\r]/", ' ', $cadena);
	   
    }
    echo implode(", ", $arr);
		echo "]}";
    /* liberar el resultset */
    $result->free();
}else{
  echo "{\"results\": []}";
}
}





/* cerrar la conexión */
$mysqli->close();
?>