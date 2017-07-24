<?php

include '../model/Tercero.php';


$mensaje = "Hubo un error";

try {

    $con = new Conectar();
    $con->conexion();
    $postdata = file_get_contents("php://input");

    if (isset($postdata) && !empty($postdata)) {
        $datos = json_decode($postdata, true);

        if (isset($datos["id_customer"]) and $datos["id_customer"] != "") {
            $eliminarTercero = new Tercero();
            $eliminarTercero->Eliminar($datos["id_customer"]);
        }
    }

    $respuesta = array('codigo' => 1, 'mensaje' => 'Eliminado con exito');
    echo json_encode($respuesta);
    die;
} catch (Exception $exc) {
    $respuesta = array('codigo' => 0, 'mensaje' => $exc->getMessage());
    echo json_encode($respuesta);
    die;
}
?>



