<?php

class Categoria {
  
      private $idtipoproducto;
      private $nombre;
  
      public function __construct (){}

      public function __get($propiedad) { return $this->$propiedad;}

      public function __set($propiedad, $valor) { $this->$propiedad = $valor; return $this;}

      public function cargarFormulario ($request){

        $this->idtipoproducto =isset($request['id'])? $request['id'] : "";      
        $this->nombre =isset($request['txtNombre'])? $request['txtNombre'] : "";
      }

      public function insertar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO tipo_productos (nombre)
                VALUES ('" . $this->nombre ."');";
        $mysqli->query($sql);
        $mysqli->close();
      }

      public function obtenerPorId(){
        $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
        $sql="SELECT nombre
              FROM tipo_productos
              WHERE idtipoproducto =" . $this->idtipoproducto;
        $resultado= $mysqli->query($sql);
        //convierte string en array

        if ($fila=$resultado->fetch_assoc()){
                  $this->nombre = $fila['nombre'];
        }          
        $mysqli->close();
    }

    public function eliminar(){
      $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
      $sql = "DELETE FROM tipo_productos
              WHERE idtipoproducto = " . $this->idtipoproducto;
      $mysqli->query($sql);
      $mysqli->close();
    }

    public function actualizar(){

      $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
      $sql = "UPDATE tipo_productos SET
              nombre = '".$this->nombre."'
              WHERE idtipoproducto = " . $this->idtipoproducto;
      $mysqli->close();
    }

    public function obtenerTodos(){
      $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
      $sql="SELECT idtipoproducto,
                   nombre 
            FROM tipo_productos";

      $resultado= $mysqli->query($sql);

      $aResultado=array();
      
      if($resultado){
      
        while($fila= $resultado->fetch_assoc()){
          
          $entidadAux= new Categoria();
          $entidadAux->idtipoproducto =$fila['idtipoproducto'];
          $entidadAux->nombre =$fila['nombre'];
          $aResultado[]=$entidadAux;
        }
      }
      return $aResultado;
    }
}
?>
