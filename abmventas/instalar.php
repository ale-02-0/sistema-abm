<?php
include_once "config.php";
include_once "entidades/usuario.php";
/*
$usuario = new Usuario();
$usuario->usuario = "agarcia";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Alejandra";
$usuario->apellido = "garcia";
$usuario->correo = "romagarale@gmail.com";
$usuario->insertar();*/

$usuario = new Usuario();
$usuario->usuario = "valeria";
$usuario->clave = $usuario->encriptarClave("clave");
$usuario->nombre = "Valeria";
$usuario->apellido = "Valeria";
$usuario->correo = "valeria@gmail.com";
$usuario->insertar();


?>