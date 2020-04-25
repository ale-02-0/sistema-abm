<?php

class Cliente {
  
  private $idcliente;
  private $nombre;
  private $cuit;
  private $telefono;
  private $correo;
  private $fecha_nac;

      public function __construct (){ }

      public function __get($propiedad) { return $this->$propiedad;}

      public function __set($propiedad, $valor) { $this->$propiedad = $valor; return $this;}

      public function cargarFormulario($request){

        $this->idcliente = isset($request['id'])? $request['id'] : "";
        $this->nombre = isset($request['txtNombre'])? $request['txtNombre'] : "";
        $this->cuit = isset($request['txtCuit'])? $request['txtCuit']: "";
        $this->telefono = isset($request['txtTelefono'])? $request['txtTelefono']: "";
        $this->correo = isset($request['txtEmail'])? $request['txtEmail'] : "";
        $this->fecha_nac = isset($request['txtFechaNac'])? $request['txtFechaNac'] :"";

    }

      public function insertar(){

          $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
          $sql = "INSERT INTO clientes (nombre,
                                        cuit,
                                        telefono,
                                        correo,
                                        fecha_nac)
                  VALUES ('" . $this->nombre ."',
                          '" . $this->cuit ."', 
                          '" . $this->telefono ."',
                          '" . $this->correo ."',
                          '" . $this->fecha_nac ."');";
          $mysqli->query($sql);
          $this->idcliente = $mysqli->insert_id;
          $mysqli->close();
      }

      public function obtenerPorId(){

          $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
          $sql="SELECT  idcliente,
                        nombre,
                        cuit,
                        telefono,
                        correo,
                        fecha_nac 
                FROM clientes
                WHERE idcliente =" . $this->idcliente;
          $resultado= $mysqli->query($sql);
         
          if ($fila=$resultado->fetch_assoc()){
                    $this->nombre = $fila['nombre'];
                    $this->cuit = $fila['cuit'];
                    $this->telefono = $fila['telefono'];
                    $this->correo = $fila['correo'];
                    $this->fecha_nac = $fila['fecha_nac'];
          }
          $mysqli->close();
        }

      public function eliminar(){

        $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
        $sql="DELETE FROM clientes 
              WHERE idcliente=" . $this->idcliente;
        $mysqli->query($sql);
        $mysqli->close();
      } 
    
      public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE clientes SET
                nombre = '".$this->nombre."',
                cuit = '".$this->cuit."',
                telefono = '".$this->telefono."',
                correo = '".$this->correo."',
                fecha_nac =  '".$this->fecha_nac."'
                WHERE idcliente = " . $this->idcliente;
          
          if (!$mysqli->query($sql)) {
              printf("Error en query: %s\n", $mysqli->error . " " . $sql);
          }
        $mysqli->close();
      }

      public function obtenerTodos(){
        
        $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
        $sql="SELECT idcliente,
                      nombre,
                      cuit,
                      telefono,
                      correo,
                      fecha_nac
              FROM clientes";
        $resultado= $mysqli->query($sql);

        $aResultado=array();

          if($resultado){         

            while($fila= $resultado->fetch_assoc()){              
                $entidadAux= new Cliente();
                $entidadAux->idcliente =$fila['idcliente'];
                $entidadAux->nombre =$fila['nombre'];
                $entidadAux->cuit =$fila['cuit'];
                $entidadAux->telefono =$fila['telefono'];
                $entidadAux->correo =$fila['correo'];
                $entidadAux->fecha_nac=$fila['fecha_nac'];
                $aResultado[]= $entidadAux;
              }
          }
        return $aResultado;
      }
}
?>