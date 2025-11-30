<?php
require_once '../config/Database.php';
require_once '../functions/autoload.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}


$errores = [];

$mail = trim($_POST['username'] ?? '');
$contrasenia = trim($_POST['password'] ?? '');

if (empty($mail) || strlen($mail) < 5) {
    $errores[] = "El nombre es obligatorio y debe tener al menos 5 caracteres.";
}
if (empty($contrasenia)) {
    $errores[] = "La contraseña es obligatoria.";
}

if(count($errores) != 0){
    $_SESSION['login_errores'] = $errores;
    header("Location: ../?section=login");
    return;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$usuario = Autorizacion::logIn($mail, $contrasenia);

if($usuario){
    $usuario = $_SESSION['usuario'];
    if($usuario->tieneRol()){
        header("Location: ../?section=dashboard");
        return;
    }
    header("Location: ../?section=inicio");
    return;
}

if(is_null($usuario)){
    $errores[] = "Usuario no encontrado :(";
}else{
    $errores[] = "Contraseña incorrecta";
}


$_SESSION['login_errores'] = $errores;
header("Location: ../?section=login");