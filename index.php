<?php
    require_once 'classes/Vista.php';
    require_once 'functions/Autoload.php';

    $seccion = $_GET['section'] ?? 'inicio';
    $vista = (new Vista())->validarVista($seccion);

    $ubicacion = $vista->getUbicacion();
    $pageTitle = $vista->getTitulo();

    if (session_status() == PHP_SESSION_NONE) {
        isset($_SESSION) ? '' : session_start();
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- estilos -->
        <link href="./assets/css/bootstrap.css" rel="stylesheet">
        <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./app.css">
        <!-- TODO: pensar otro nombre -->
        <title><?= "$pageTitle | " ?>The Kitchen Pro</title>
        <!-- fontawesome -->
        <script src="https://kit.fontawesome.com/80c9de9a04.js" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex flex-column" style="min-height: 100vh;">
        <?php include_once('views/plantillas/header.php'); ?>
        <main class="flex-grow-1">
            <?php 
                try {
                    // $asd = 1 / 0;
                    include_once($ubicacion); 
                } catch (\Throwable $error) {
                    include_once('views/errors/500.php'); 
                }
            ?>
        </main>
        <?php include_once('views/plantillas/footer.php'); ?>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>
