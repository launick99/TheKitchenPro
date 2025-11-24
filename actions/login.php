<?php
require_once '../config/Database.php';
require_once '../classes/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

/**
 * Limpia un dato de entrada
 * @param string $dato
 */
function limpiar(string $dato) {
    return htmlspecialchars(trim($dato));
}

$errores = [];

$mail = limpiar($_POST['username'] ?? '');
$contrasenia = limpiar($_POST['password'] ?? '');

if (empty($mail) || strlen($mail) < 5) {
    $errores[] = "El nombre es obligatorio y debe tener al menos 5 caracteres.";
}
if (empty($contrasenia)) {
    $errores[] = "La contraseña es obligatoria.";
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (count($errores) == 0) {
    $usuario = (new Usuario())->login($mail, $contrasenia);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario->id;
        $_SESSION['usuario_nombre'] = $usuario->nombre ?? $usuario->nombre_usuario ?? $usuario->email;
        
        setcookie(
            'usuario_cookie',
            $usuario->nombre_usuario ?? $usuario->id,
            time() + 3600,
            '/', 
            '',
            false,
            true
        );
                
        header("Location: ../?section=inicio");
        exit;
    } else {
        $errores[] = "Usuario o contraseña incorrectos.";
    }
}

$_SESSION['login_errores'] = $errores;
header("Location: ../?section=login");