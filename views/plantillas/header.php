<?php
    $paginas = ['inicio', 'catalogo', 'alumno', 'contacto'];
?>

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
                    <?php foreach ($paginas as $pagina){ ?>
                        <li class="nav-item">
                            <a class="nav-link <?= Vista::isActive($pagina, $seccion) ? 'active' : '' ?>" href="?section=<?= $pagina ?>">
                                <?php echo $pagina ?>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['usuario']->getNombreUsuario()) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                                <li>
                                    <a class="nav-link ps-2" href="?section=historial">
                                        Historial
                                    </a>
                                </li>
                                <li>
                                    <form action="actions/logout.php" method="POST">
                                        <button type="submit" class="dropdown-item ps-2">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?section=login">Iniciar sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>