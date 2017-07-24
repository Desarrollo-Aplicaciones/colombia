<?php

if (is_file('clases/conectar.php')) {
    include 'clases/conectar.php';
}
if (is_file('../clases/conectar.php')) {
    include '../clases/conectar.php';
}

class Tercero {

    private $id_customer;
    private $nombre;
    private $id_tipo_identificacion;
    private $identificacion;
    private $telefono;
    private $fecha_nacimiento;
    private $fecha_sist;

    function getId_tipo_identificacion() {
        return $this->id_tipo_identificacion;
    }

    function setId_tipo_identificacion($id_tipo_identificacion) {
        $this->id_tipo_identificacion = $id_tipo_identificacion;
    }

    function getId_customer() {
        return $this->id_customer;
    }

    function setId_customer($id_customer) {
        $this->id_customer = $id_customer;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getFecha_nacimiento() {
        return $this->fecha_nacimiento;
    }

    function getFecha_sist() {
        return $this->fecha_sist;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setFecha_nacimiento($fecha_nacimiento) {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    function setFecha_sist($fecha_sist) {
        $this->fecha_sist = $fecha_sist;
    }

    /**
     * @example path devuelve los campos y las reglas de validacion de cada campo
     * @return type array
     */
    function campos() {
        $campos = array(
            'id_customer' => null,
            'nombre' => null,
            'id_tipo_identificacion' => null,
            'select_id_tipo_identificacion' => null,
            'identificacion' => null,
            'telefono' => null,
            'fecha_nacimiento' => null,
        );

        return array('campos' => $campos);
    }

    function Guardar() {



        $conexion = new Conectar();
        $con = $conexion->conexion();
        
        //guardar
        if ($this->getId_customer() == "") {
            $sql = "insert into customer ";
            $sql .= "(nombre,id_tipo_identificacion,identificacion,telefono,fecha_nacimiento)";
            $sql .= " value ";
            $sql .= "(";
            $sql .= " '" . $this->getNombre() . "', ";
            $sql .= " " . $this->getId_tipo_identificacion() . ", ";
            $sql .= " '" . $this->getIdentificacion() . "', ";
            $sql .= " '" . $this->getTelefono() . "', ";
            $sql .= " '" . $this->getFecha_nacimiento() . "' ";
            $sql .= ");";
            $con->query($sql);

            //editar
        } else {

            $sql = "update customer ";
            $sql .= " set nombre=";
            $sql .= " '" . $this->getNombre() . "', ";
            $sql .= " id_tipo_identificacion=";
            $sql .= " " . $this->getId_tipo_identificacion() . ", ";
            $sql .= " identificacion=";
            $sql .= " '" . $this->getIdentificacion() . "', ";
            $sql .= " telefono=";
            $sql .= " '" . $this->getTelefono() . "', ";
            $sql .= " fecha_nacimiento=";
            $sql .= " '" . $this->getFecha_nacimiento() . "' ";
            $sql .= " where id_customer=" . $this->getId_customer();
            $con->query($sql);
        }
    }

    function Eliminar($id_customer) {
        try {
            $conexion = new Conectar();
            $con = $conexion->conexion();
            $sql = "delete from customer where id_customer=" . $id_customer . ";";
            $con->query($sql);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }



}
