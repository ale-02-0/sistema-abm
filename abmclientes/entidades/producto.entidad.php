<?php

class Producto{
    private $idproducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarDesdeRequest($form){
        $this->idproducto = isset($form["id"])?$form["id"]:0;
        $this->nombre = $form["txtNombre"];
        $this->cantidad = $form["txtCantidad"];
        $this->precio = $form["txtPrecio"];
        $this->descripcion = $form["txtDescripcion"];
    }

    public function insertar(){
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $mysqli->query("INSERT INTO productos (nombre, cantidad, precio, descripcion) 
        VALUES (
            '$this->nombre', 
            $this->cantidad, 
            $this->precio, 
            '$this->descripcion'
        )");
        $this->idproducto = $mysqli->insert_id;
    }

    public function borrar(){
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE); 
        $obj->query("DELETE FROM productos WHERE idproducto = $this->idproducto");
    }

    public function actualizar(){
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $resultado = $this->mysqli->query("UPDATE productos
            SET
            nombre = '$this->nombre',
            cantidad = $this->cantidad,
            precio = $this->precio,
            descripcion = '$this->descripcion'
            WHERE idproducto = $this->idproducto
            ");
    }

    public function obtenerTodos(){
        $aProductos = array();
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $consulta = "SELECT 
            idproducto,
            nombre,
            cantidad,
            precio,
            descripcion 
            FROM productos ORDER BY idproducto DESC";
        $resultado = $obj->query($consulta);

        while ($fila = $resultado->fetch_assoc()) {
            $producto = new Producto();
            $producto->idproducto = $fila["idproducto"];
            $producto->nombre = $fila["nombre"];
            $producto->cantidad = $fila["cantidad"];
            $producto->precio = $fila["precio"];
            $producto->descripcion = $fila["descripcion"];
            $aProductos[] = $producto;
        }
        return $aProductos;
    }
    public function obtenerUnoPorId($id){
        $obj = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $consulta = "SELECT 
            idproducto,
            nombre,
            cantidad,
            precio,
            descripcion 
            FROM productos WHERE idproducto = $id";
            //print_r($consulta);exit;
        $resultado = $obj->query($consulta);

        $fila = $resultado->fetch_assoc();

        $this->idproducto = $fila["idproducto"];
        $this->nombre = $fila["nombre"];
        $this->cantidad = $fila["cantidad"];
        $this->precio = $fila["precio"];
        $this->descripcion = $fila["descripcion"];
        
        return true;
    }

}

?>