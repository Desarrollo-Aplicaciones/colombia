<?php
include '../clases/conectar.php';
$conexion = new Conectar();
$con = $conexion->conexion();
$query = $con->query("select * from tipo_identificacion;");
$result = array();

while ($row = $query->fetch_array()) {
    $result[] = $row;
}

echo json_encode($result);

