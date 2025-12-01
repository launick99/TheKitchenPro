<?php

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php");
        return;
    }

    function limpiar($dato) {
        return htmlspecialchars(trim($dato));
    }

    $errores = [];

    $nombre = limpiar($_POST['nombre'] ?? '');
    $apellido = limpiar($_POST['apellido'] ?? '');
    $mail  = limpiar($_POST['mail'] ?? '');
    $cel = limpiar($_POST['cel'] ?? '');
    $comentario = limpiar($_POST['comentario'] ?? '');

    if (empty($nombre) || strlen($nombre) < 5) {
        $errores[] = "El nombre es obligatorio y debe tener al menos 2 caracteres.";
    }
    if (empty($apellido) || strlen($apellido) < 5) {
        $errores[] = "El apellido es obligatorio y debe tener al menos 2 caracteres.";
    }
    if (empty($mail) || strlen($apellido) < 5) {
        $errores[] = "El email ingresado no es válido.";
    }
    // Googleado
    if (!empty($cel) && !preg_match('/^\d{8,15}$/', $cel)) {
        $errores[] = "El teléfono debe contener solo números (entre 8 y 15 dígitos).";
    }

?>

<?php if(!empty($errores)): ?>
    <div class="container my-5">
        <div class="alert alert-danger">
            <h5 class="alert-heading">Se encontraron errores en el formulario:</h5>
            <ul class="mb-3">
                <?php foreach ($errores as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="index.php?section=contacto" class="btn btn-kitchenpro btn btn-warning">Volver</a>
        </div>
    </div>
<?php else: ?>
    <div class="container my-5">
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <h2 class="card-title text-center">¡Gracias por tu mensaje!</h2>
                <p class="text-center">Hemos recibido tus datos y pronto nos pondremos en contacto.</p>
                <h3>Detalles enviados:</h3>
                <hr>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Nombre:</strong> <?= $nombre ?></li>
                    <li class="list-group-item"><strong>Apellido:</strong> <?= $apellido ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?= $mail ?></li>
                    <li class="list-group-item"><strong>Teléfono:</strong> <?= $cel ?: 'No proporcionado' ?></li>
                    <li class="list-group-item"><strong>Comentario:</strong><br><?= $comentario ?: 'Sin comentario :o' ?></li>
                </ul>
                <a href="index.php" class="btn btn-kitchenpro btn btn-warning">Volver al inicio</a>
            </div>
        </div>
    </div>
<?php endif; ?>
