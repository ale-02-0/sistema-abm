<?php

class Venta {
  
      private $idventa;
      private $fk_idcliente;
      private $fk_idproducto;
      private $fecha;
      private $cantidad;
      private $importe;
      private $total;

        public function __construct (){ 

          $this->cantidad=0;
          $this->importe=0.0;
          $this->total=0.0;
        }

        public function __get($propiedad) { return $this->$propiedad;}

        public function __set($propiedad, $valor) { $this->$propiedad = $valor; return $this;}
        
        public function cargarFormulario($request){ 

          $this->idventa = isset($request['id'])? $request['id'] : "";
          $this->fk_idcliente = isset($request['lstCliente'])? $request['lstCliente'] : "";
          $this->fk_idproducto = isset($request['lstProducto'])? $request['lstProducto'] : "";
          $this->fecha = isset($request['txtFecha'])? $request['txtFecha'] : "";
          $this->cantidad = isset($request['txtCantidad'])? $request['txtCantidad'] : 0;
          $this->importe = isset($request['txtImporte'])? $request['txtImporte'] : 0;
          $this->total = isset($request['txtTotal'])? $request['txtTotal'] : 0;
        }

        public function insertar(){

            $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql= "INSERT INTO ventas (fk_idcliente,
                                        fk_idproducto,
                                        fecha,
                                        cantidad,
                                        importe,
                                        total)
                    VALUES (" . $this->fk_idcliente .",
                            " . $this->fk_idproducto .", 
                            '". $this->fecha ."',
                            " . $this->cantidad .",
                            " . $this->importe .",
                            " . $this->total .");";  
            $mysqli->query($sql);
            $this->idventa = $mysqli->insert_id;
            $mysqli->close();
        }

        public function obtenerPorId(){

          $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
          $sql= "SELECT fk_idcliente,
                        fk_idproducto,
                        fecha,
                        cantidad,
                        importe,
                        total                        
                FROM ventas
                WHERE idventa=" . $this->idventa;

          $resultado=$mysqli->query($sql);
  
          if ($fila=$resultado ->fetch_assoc()){

            $this->fk_idcliente =$fila['fk_idcliente'];
            $this->fk_idproducto =$fila['fk_idproducto'];
            $this->fecha =$fila['fecha'];
            $this->cantidad =$fila['cantidad'];
            $this->importe =$fila['importe'];
            $this->total =$fila['total'];
          }

          $resultado= $mysqli->query($sql);
              if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
              }
          $mysqli->close();
        }

        public function actualizar(){

          $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO,  Config::BBDD_CLAVE,  Config::BBDD_NOMBRE);
          $sql= "UPDATE ventas 
                SET
                  fk_idcliente =  " . $this->fk_idcliente .",
                  fk_idproducto = " . $this->fk_idproducto .", 
                  fecha = '" .  $this->fecha ."',
                  cantidad =  " . $this->cantidad .",
                  importe = " . $this->importe .", 
                  total =     " . $this->total ."
                WHERE idventa = " . $this->idventa;
            $mysqli->query($sql);
            $mysqli->close();
       }
        
        public function eliminar(){
          
          $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
          $sql= "DELETE FROM ventas
                WHERE idventa=" . $this->idventa;
          $mysqli->query($sql);
          $mysqli->close();
        }

        public function cargarGrilla(){

          $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
          $sql = "SELECT 
                        A.idventa,
                        A.fecha,
                        A.cantidad,
                        A.fk_idcliente,
                        B.nombre as nombre_cliente,
                        A.fk_idproducto,
                        A.total,
                        A.importe,
                        C.nombre as nombre_producto
      FROM ventas A
      INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
      INNER JOIN productos C ON A.fk_idproducto = C.idproducto
      ORDER BY A.fecha DESC";

          $resultado= $mysqli->query($sql);
          if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
      
          $aResultado=array();
  
            if($resultado){

                while($fila= $resultado->fetch_assoc()){ 
                    $entidadAux= new Venta();
                    $entidadAux->idventa =$fila['idventa'];
                    $entidadAux->fk_idcliente =$fila['fk_idcliente']; 
                    $entidadAux->nombre_cliente= $fila ['nombre_cliente'];
                    $entidadAux->fk_idproducto =$fila['fk_idproducto']; 
                    $entidadAux->nombre_producto= $fila ['nombre_producto'];
                    $entidadAux->fecha =$fila['fecha']; 
                    $entidadAux->cantidad =$fila['cantidad']; 
                    $entidadAux->importe =$fila['importe']; 
                    $entidadAux->total =$fila['total']; 
                    $aResultado[]=$entidadAux;
              } 
        }
        return $aResultado;
        }

      public function obtenerFacturacionMensual($mes){

        $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql= "SELECT SUM(total) 
              AS total
              FROM ventas
              WHERE month(fecha)= $mes";
                  if (!$resultado = $mysqli->query($sql)) {
                    printf("Error en query: %s\n", $mysqli->error . " " . $sql);
                }
        $fila= $resultado->fetch_assoc();
        return $fila ['total'];
      }

      public function obtenerFacturacionAnual($anio){
        
        $mysqli= new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql= "SELECT SUM(total) 
              AS total
              FROM ventas
              WHERE YEAR(fecha)= $anio";
                  if (!$resultado = $mysqli->query($sql)) {
                    printf("Error en query: %s\n", $mysqli->error . " " . $sql);
                }
        $fila= $resultado->fetch_assoc();
        return $fila ['total'];
      }             
} ?>