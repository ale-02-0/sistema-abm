<?php

class Domicilio{
    private $iddomicilio;
    private $fk_idcliente;
    private $fk_tipo;
    private $fk_idlocalidad;
    private $domicilio;


    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function insertar(){
        $mysql = new mysqli(Config::BBDD_HOST, Constante::BBDD_USUARIO, Constante::BBDD_CLAVE, Constante::BBDD_NOMBRE);
        $mysql->query("INSERT INTO domicilios (
            fk_idcliente, 
            fk_tipo, 
            fk_idlocalidad, 
            domicilio) VALUE(
            $this->fk_idcliente, 
            $this->fk_tipo, 
            $this->fk_idlocalidad, 
            '$this->domicilio')");
    }



}


?>