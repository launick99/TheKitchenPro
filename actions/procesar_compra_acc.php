<?php
    require_once '../functions/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $nombre         = trim($_POST['nombre']         ?? '');
    $tarjeta        = trim($_POST['tarjeta']        ?? '');
    $cvv            = trim($_POST['cvv']            ?? '');
    $vencimiento    = trim($_POST['vencimiento']    ?? '');

    /* ----------------------------------
    |  Validaciones (innecesaria)
    +---------------------------------- */
    $error = false;

    if (empty($nombre) || strlen($nombre) < 2) {
        Alerta::agregarAlerta("danger","El nombre es obligatorio.");
        $error = true;
    }
    // preg_match sacado de google
    if (!preg_match('/^\d{4} \d{4} \d{4} \d{4}$/', $tarjeta)) {
        Alerta::agregarAlerta("danger","Número de tarjeta inválido.");
        $error = true;
    }

    if (!preg_match('/^\d{3}$/', $cvv)) {
        Alerta::agregarAlerta("danger","CVV inválido.");
        $error = true;
    }

    if (empty($vencimiento) || strlen($vencimiento) != 7) {
        Alerta::agregarAlerta("danger","Fecha de vencimiento inválida.");
        $error = true;
    }

    if($error){
        $url = 
            '&nombre='      . urlencode($nombre) .
            '&tarjeta='     . urlencode($tarjeta) .
            '&vencimiento=' . urlencode($vencimiento);
        header("location: ../index.php?section=checkout$url");
    }

    /* ----------------------------------
    |  Guardar datos
    +---------------------------------- */
    $items = Carrito::listar();
    $usuario = $_SESSION['usuario'] ?? false;
    try {
        if(!$usuario){
            Alerta::agregarAlerta("warning", "Sesion expirada, ingrese nuevamente");
            header("location: ../index.php?section=login");
            return;
        }
        $datosCompra = [
            "usuario_id" => $usuario->getId(),
            "importe" => Carrito::precioFinal() - Carrito::getEnvio(),
            "importe_envio" => Carrito::getEnvio(),
            "fecha" => date('Y-m-d H:i:s'),
        ];

        Checkout::insert($datosCompra, $items);
        Carrito::limpiar();
        Alerta::agregarAlerta("success", "Compra exitosa!");
        header("location: ../index.php?section=historial");
        return;

    } catch (\Throwable $th) {
        Alerta::agregarAlerta("warning", "No se pudo finalizar la compra: ". $th->getMessage());
    }
    header("location: ../index.php?section=catalogo");
    return;
?>