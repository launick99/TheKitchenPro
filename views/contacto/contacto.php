<?php
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : new Usuario();
?>

<section class="container my-5" id="contacto">
    <h2 class="text-center mb-4">Contacto</h2>
    <p>¡Nos encanta saber de ti! Rellena el formulario y cuéntanos todo lo que necesitas.</p>
    <form action="?section=gracias" method="post">
        <!-- Nombre y apellido -->
        <div class="row">
            <div class="col-12 col-sm-6 mb-2">
                <label class="form-label" for="nombre">Nombres</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Marcos Eduardo" value="<?= $usuario->getNombre() ?>" required>
            </div>
            <div class="col col-sm-6 mb-2">
                <label class="form-label" for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ej: Galperín Lebach" required>
            </div>
        </div>
        <!-- Mail -->
        <div class="mb-2">
            <label class="form-label" for="mail">Email</label>
            <input type="email" id="mail" name="mail" class="form-control" placeholder="Ej: MarcosEdu@gmail.com" value="<?= $usuario->getMail() ?>" required>
        </div>
        <!-- Telefono -->
        <div class="mb-2">
            <label class="form-label" for="cel">Telefono</label>    
            <input type="number" id="cel" name="cel" placeholder="Ej: 5491187651234" class="form-control">
        </div>
        <!-- Comentario -->
        <div class="mb-2">
            <label class="form-label" for="comentario">Comentario</label>
            <textarea class="form-control" id="comentario" name="comentario" style="resize: none;" rows="4"></textarea>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4">
                <button type="submit" class="btn btn-kitchenpro btn btn-warning w-100 mb-4">
                    <span>Enviar!</span>
                </button>
            </div>
        </div>
    </form>
</section>