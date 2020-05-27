<?php

class Cliente{
    private $idcliente;
    private $cuit;
    private $nombre;
    private $telefono;
    private $correo;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarDesdeRequest($form){
        $this->idcliente = isset($form["id"])?$form["id"]:0;
        $this->cuit = $form["txtCuit"];
        $this->nombre = $form["txtNombre"];
        $this->telefono = $form["txtTelefono"];
        $this->correo = $form["txtEmail"];
    }


    public function insertar(){
        $mysql = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $sql = "INSERT INTO clientes (cuit, nombre, telefono, correo) VALUE (
            '$this->cuit', 
            '$this->nombre', 
            $this->telefono, 
            '$this->correo')";
      
        $mysql->query($sql);
        $this->idcliente = $mysql->insert_id;
    }

    public function borrar(){
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $resulado = $mysqli->query("DELETE FROM clientes WHERE idcliente = $this->idcliente");
    }
    
    public function actualizar(){
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $resulado = $mysqli->query("UPDATE clientes
                SET
                cuit = $this->cuit,
                nombre = '$this->nombre',
                telefono = $this->telefono,
                correo = '$this->correo'
                WHERE idcliente = $this->idcliente
                ");
    }

    public function obtenerTodos(){
        $aCliente = null;
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $resultado = $mysqli->query("SELECT
	A.idcliente,
	A.cuit,
	A.nombre,
	A.telefono,
	A.correo,
	(SELECT GROUP_CONCAT('(', C.nombre, ') ', B.domicilio, ', ', D.nombre, ', ', E.nombre SEPARATOR '<br>')	
		FROM domicilios B 
		INNER JOIN tipo_domicilios C ON C.idtipo = B.fk_tipo
		INNER JOIN localidades D ON D.idlocalidad = B.fk_idlocalidad
		INNER JOIN provincias E ON E.idprovincia = D.fk_idprovincia
		WHERE B.fk_idcliente = A.idcliente
		) as domicilio
FROM
	clientes A
ORDER BY
	idcliente DESC");

        if($resultado){
            while ($fila = $resultado->fetch_assoc()) {
                $obj = new Cliente();
                $obj->idcliente = $fila["idcliente"];
                $obj->cuit = $fila["cuit"];
                $obj->nombre = $fila["nombre"];
                $obj->telefono = $fila["telefono"];
                $obj->correo = $fila["correo"];
                $obj->domicilio = $fila["domicilio"];
                $aCliente[] = $obj;

            }
            return $aCliente;
        }
    }

    public function obtenerPorId($idcliente){
        $mysqli = new mysqli(Constante::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        
        $sql = "SELECT idcliente,
            cuit,
            nombre,
            telefono,
            correo
            FROM clientes
            WHERE idcliente = $idcliente";
 
            $resultado = $mysqli->query($sql);

            if($resultado){
                $fila = $resultado->fetch_assoc();
                $this->idcliente = $fila["idcliente"];
                $this->cuit = $fila["cuit"];
                $this->nombre = $fila["nombre"];
                $this->telefono = $fila["telefono"];
                $this->correo = $fila["correo"];

                return true;
            }
    }
}


?>