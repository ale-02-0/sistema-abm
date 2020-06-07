<?php
include_once "config.php";
include_once "entidades/usuario.php";

$usuario=new Usuario();
$usuario->usuario="agar";
$usuario->clave=$usuario->encriptarClave("admin123");
$usuario->nombre="Alejandra";
$usuario->apellido="Garcia";
$usuario->correo="romagarale@gmail.com";
$usuario->insertar();

?>