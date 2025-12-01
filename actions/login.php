<?php
    require_once '../config/Database.php';
    require_once '../functions/autoload.php';

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../login.php");
        exit;
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $mail = trim($_POST['username'] ?? '');
    $contrasenia = trim($_POST['password'] ?? '');

    $error = false;

    if (empty($mail) || strlen($mail) < 5) {
        Alerta::agregarAlerta("danger", "El nombre es obligatorio y debe tener al menos 5 caracteres.");
        $error = true;
    }

    if (empty($contrasenia)) {
        Alerta::agregarAlerta("danger", "La contraseña es obligatoria.");
        $error = true;
    }

    if ($error) {
        header("Location: ../?section=login");
        return;
    }

    $usuario = Autorizacion::logIn($mail, $contrasenia);

    if ($usuario) {
        $usuario = $_SESSION['usuario'];
        if ($usuario->tieneRol()) {
            header("Location: ../?section=dashboard");
            return;
        }
        header("Location: ../?section=inicio");
        return;
    }

    if (is_null($usuario)) {
        Alerta::agregarAlerta("danger", "Usuario no encontrado :(");
    } else {
        Alerta::agregarAlerta("danger", "Contraseña incorrecta");
    }

    header("Location: ../?section=login");
