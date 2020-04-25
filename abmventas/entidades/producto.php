<?php

class Producto {
    private $idproducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $fk_idtipoproducto;

    public function __construct(){
        $this->cantidad=0;
        $this->precio=0.00;
    }

    public function __get($atributo) {return $this->$atributo; }

    public function __set($atributo, $valor) {$this->$atributo = $valor;return $this;}

    public function cargarFormulario($request){

        $this->idproducto = isset($request['id'])? $request['id'] : "";
        $this->nombre = isset($request['txtNombre'])? $request['txtNombre'] : "";
        $this->cantidad = isset($request['txtcantidad'])? $request['txtcantidad']: 0;
        $this->precio = isset($request['txtPrecio'])? $request['txtPrecio']: 0;
        $this->descripcion = isset($request['txtDescripcion'])? $request['txtDescripcion'] : "";
        $this->fk_idtipoproducto = isset($request['lstCategoria'])? $request['lstCategoria'] :"";
    }

    public function insertar(){
       
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
      
        $sql = "INSERT INTO productos (
                    nombre, 
                    cantidad, 
                    precio, 
                    descripcion, 
                    fk_idtipoproducto
                ) VALUES (
                    '" . $this->nombre ."', 
                    " . $this->cantidad .", 
                    " . $this->precio .", 
                    '" . $this->descripcion ."', 
                    '" . $this->fk_idtipoproducto ."'
                );";
        
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        
        $this->idproducto = $mysqli->insert_id;
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE productos 
                SET nombre = '". $this->nombre ."',
                    cantidad = ". $this->cantidad .",
                    precio = ". $this->precio .",
                    descripcion = '". $this->descripcion ."',
                    fk_idtipoproducto =  ". $this->fk_idtipoproducto ."
                WHERE idproducto = " . $this->idproducto;
          
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;
      
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT  idproducto, 
                        nombre, 
                        cantidad, 
                        precio, 
                        descripcion, 
                        fk_idtipoproducto 
                FROM productos 
                WHERE idproducto = " . $this->idproducto;

            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }

            if($fila = $resultado->fetch_assoc()){
            $this->nombre = $fila['nombre'];
            $this->cantidad = $fila['cantidad'];
            $this->precio = $fila['precio'];
            $this->descripcion = $fila['descripcion'];
            $this->fk_idtipoproducto = $fila['fk_idtipoproducto'];
        }  
        $mysqli->close();

    }

    public function cargarGrilla(){
        
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT  A.idproducto,
                        A.nombre,
                        A.cantidad,
                        A.precio,
                        A.descripcion,
                        A.fk_idtipoproducto,
                        B.nombre AS nombre_categoria
                FROM productos A
                INNER JOIN tipo_productos B ON A.fk_idtipoproducto=B.idtipoproducto";

        $resultado= $mysqli->query($sql);
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado=array();
        if($resultado){
           
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Producto();
                $entidadAux->idproducto = $fila['idproducto'];
                $entidadAux->nombre = $fila['nombre'];
                $entidadAux->cantidad = $fila['cantidad'];
                $entidadAux->precio = $fila['precio'];
                $entidadAux->descripcion = $fila['descripcion'];
                $entidadAux->fk_idtipoproducto = $fila['fk_idtipoproducto'];
                $entidadAux->nombre_categoria= $fila ['nombre_categoria'];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

}


?>