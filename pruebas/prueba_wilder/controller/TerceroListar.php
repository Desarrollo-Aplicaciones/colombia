<?php

include '../clases/conectar.php';
$conexion = new Conectar();
$con = $conexion->conexion();

$sql = "
select c.id_customer,
c.nombre,
c.id_tipo_identificacion,
c.identificacion,
c.telefono,
c.fecha_nacimiento,
c.fecha_sist, 
null as select_id_tipo_identificacion,
ti.nombre as nombre_tipo_identificacion

from customer c
inner join tipo_identificacion ti on (ti.id_tipo_identificacion=c.id_tipo_identificacion)
 
;";
$query = $con->query($sql);
$resultTercero = array();

while ($row = $query->fetch_array()) {
    $resultTercero[] = $row;
}

echo json_encode($resultTercero);
?>

