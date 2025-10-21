<?php
    require_once 'classes/Vista.php';

    $seccion = $_GET['section'] ?? 'inicio';

    $vista = new Vista($seccion);

    $view = $vista->getView();
    $pageTitle = $vista->getTitle();
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
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom border-warning">
                <div class="container">
                    <a class="navbar-brand" href="./">
                        <h1 style="font-size: 1.5rem !important;" class="m-0">The Kitchen Pro</h1>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link <?= Vista::isActive('inicio', $seccion) ? 'active' : '' ?>" href="?section=inicio">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= Vista::isActive('catalogo', $seccion) ? 'active' : '' ?>" href="?section=catalogo">Catálogo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= Vista::isActive('alumno', $seccion) ? 'active' : '' ?>" href="?section=alumno">Alumno</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= Vista::isActive('contacto', $seccion) ? 'active' : '' ?>" href="?section=contacto">Contacto</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="flex-grow-1">
            <?php 
                try {
                    // $asd = 1 / 0;
                    include_once($view); 
                } catch (\Throwable $th) {
                    include_once('views/errors/500.php'); 
                }
            ?>
        </main>
        <footer class="text-center text-lg-start text-dark" style="background-color:#ffffe9">
            <div class="bg-primary">
                <section class="d-flex flex-column flex-md-row justify-content-between p-4 text-white container">
                    <div class="me-5">
                        <h2>Siguenos en nuestras redes!</h2>
                    </div>
                    <div>
                        <a href="https://www.instagram.com/cute.cats.cure/" target="_blank" class="text-white me-4">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/groups/538474476739820" target="_blank" class="text-white me-4">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/PepitoTheCat" class="text-white me-4" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://doodles.google/doodle/tamas-18th-birthday/" target="_blank" class="text-white me-4">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>
                </section>
            </div>
            <section class="links mb-4">
                <div class="container text-center text-md-start mt-5 ">
                    <div class="row mt-3">
                        <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                            <p class="h6 text-uppercase fw-bold">The Kitchen Pro</p>
                            <hr>
                            <p>
                                La cocina que amas tener, la cocina que siempre soñaste tener.
                            </p>
                        </div>
                        <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                            <h2 class="text-uppercase fw-bold h6">Productos</h2>
                            <hr>
                            <p>
                                <a href="?section=catalogo&categorias%5B%5D=accesorios_para_cafe" class="text-dark">Accesorios Para Café</a>
                            </p>
                            <p>
                                <a href="?section=catalogo&categorias%5B%5D=electrodomesticos" class="text-dark">Electrodomésticos</a>
                            </p>
                            <p>
                                <a href="?section=catalogo&categorias%5B%5D=limpieza" class="text-dark">Limpieza</a>
                            </p>
                            <p>
                                <a href="?section=catalogo&categorias%5B%5D=organizacion" class="text-dark">Organización</a>
                            </p>
                        </div>
                        <div class="col-md-6 col-lg-4 mx-auto mb-md-0 mb-4">
                            <h2 class="h6 text-uppercase fw-bold">Contacto</h2>
                            <hr>
                            <p>
                                <i class="fas fa-home mr-3"></i>
                                <a href="https://maps.app.goo.gl/DwpMkj7S8PfrK3Ey7" target="_blank" class="text-dark"> Av. ficticia 1234, La Pampa</a>
                            </p>
                            <p>
                                <i class="fas fa-envelope mr-3"></i>
                                <a href="mailto:nose@soyuntest.gob.ar" class="text-dark"> nose@soyuntest.gob.ar</a>
                            </p>
                            <p>
                                <i class="fas fa-phone mr-3"></i>
                                <a href="tel:5491166669998"  class="text-dark"> +54 9 11 6666 9998</a>
                            </p>
                            <p>
                                <i class="fas fa-print mr-3"></i>
                                <a href="tel:5491166669999" class="text-dark">+54 9 11 6666 9999</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>
