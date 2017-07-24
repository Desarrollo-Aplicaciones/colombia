<?php

include '../model/Tercero.php';


$mensaje = "Hubo un error";

try {

    $con = new Conectar();
    $con->conexion();
    $postdata = file_get_contents("php://input");





    if (isset($postdata) && !empty($postdata)) {
        $datos = json_decode($postdata, true);

//        echo "<pre>";
//        print_r($datos);
//        die;

        $guardarTercero = new Tercero();

        $guardarTercero->setNombre($datos["nombre"]);
        $guardarTercero->setId_tipo_identificacion($datos["id_tipo_identificacion"]);
        $guardarTercero->setIdentificacion($datos["identificacion"]);
        $guardarTercero->setTelefono($datos["telefono"]);
        $guardarTercero->setFecha_nacimiento($datos["fecha_nacimiento"]);

        //editar
        if ($datos["id_customer"] != "") {
            $guardarTercero->setId_customer($datos["id_customer"]);
        }



        $guardarTercero->Guardar();
    }

    $respuesta = array('codigo' => 1, 'mensaje' => 'Guardado con exito');
    echo json_encode($respuesta);
    die;
} catch (Exception $exc) {
    $respuesta = array('codigo' => 0, 'mensaje' => $exc->getMessage());
    echo json_encode($respuesta);
    die;
}
?>



