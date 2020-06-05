<?php

class Localidad{
    private $idlocalidad;
    private $nombre;
    private $fk_idprovincia;
    private $cod_postal;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function obtenerPorProvincia($idprovincia){
        $aLocalidades = null;
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idlocalidad,
            nombre, 
            cod_postal
            FROM localidades 
            WHERE fk_idprovincia = $idprovincia
            ORDER BY idlocalidad DESC";
               if (!$mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
        $resultado = $mysqli->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            $aLocalidades[] = array(
                "idlocalidad" => $fila["idlocalidad"],
                "nombre" => $fila["nombre"],
                "cod_postal" => $fila["cod_postal"]);
       
        }
        return $aLocalidades;
    }

}


?>