<?php

class Venta{
    private $idventa;
    private $fecha;
    private $importe;
    private $fk_idcliente;
    private $fk_idproducto;
    private $nombre_producto;
    private $nombre_cliente;

    public function __construct(){
        $this->importe = 0.0;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarDesdeFormulario($form){
        $this->idventa = isset($form["id"])?$form["id"]:0;
        $this->fecha = $form["txtFecha"];
        $this->importe = $form["txtImporte"];
        $this->fk_idcliente = $form["lstCliente"];
        $this->fk_idproducto = $form["lstProducto"];
    }

    public function insertar(){
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $sql = "INSERT INTO ventas (fecha, importe, fk_idcliente, fk_idproducto) 
        VALUES (
            '$this->fecha', 
            $this->importe,
            $this->fk_idcliente,
            $this->fk_idproducto
        );";
   
        $obj->query($sql);
        $this->idventa = $obj->insert_id;
    }

     public function actualizar(){
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $sql = "UPDATE ventas
            SET
            fecha = '$this->fecha',
            importe = $this->importe,
            fk_idcliente = $this->fk_idcliente,
            fk_idproducto = '$this->fk_idproducto'
            WHERE idventa = $this->idventa
            ";
            print_r($sql);exit;
        $resultado = $this->mysqli->query($sql);
    }

    public function borrar(){
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $obj->query("DELETE FROM ventas WHERE idventa = $this->idventa");
    }

    public function obtenerTodos(){
        $aVentas = array();
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $sql = "SELECT 
                    A.idventa, 
                    A.fecha, 
                    A.importe, 
                    A.fk_idcliente, 
                    A.fk_idproducto,
                    B.nombre as nombre_producto,
                    C.nombre as nombre_cliente
                FROM ventas A
                INNER JOIN productos B ON B.idproducto = A.fk_idproducto
                INNER JOIN clientes C ON C.idcliente = A.fk_idcliente
                ";
        $resultado = $obj->query($sql);

        while($fila = $resultado->fetch_assoc()){
            $venta = new Venta();
            $venta->idventa = $fila["idventa"];
            $venta->fecha = $fila["fecha"];
            $venta->importe = $fila["importe"];
            $venta->fk_idcliente = $fila["fk_idcliente"];
            $venta->fk_idproducto = $fila["fk_idproducto"];
            $venta->nombre_producto = $fila["nombre_producto"];
            $venta->nombre_cliente = $fila["nombre_cliente"];
            $aVentas[] = $venta;
        }
        return $aVentas;
    }

    public function obtenerUnoPorId($idventa){
        $aVentas = array();
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $sql = "SELECT 
                    A.idventa, 
                    A.fecha, 
                    A.importe, 
                    A.fk_idcliente, 
                    A.fk_idproducto
                FROM ventas A
                WHERE A.idventa = $idventa
                ";
        $resultado = $obj->query($sql);
        $fila = $resultado->fetch_assoc();

        $this->idventa = $fila["idventa"];
        $this->fecha = $fila["fecha"];
        $this->importe = $fila["importe"];
        $this->fk_idcliente = $fila["fk_idcliente"];
        $this->fk_idproducto = $fila["fk_idproducto"];
        return ($this);
    }

}


?>