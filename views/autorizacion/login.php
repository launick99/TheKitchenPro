<?php
    isset($_SESSION) ? '' : session_start();
    $errores = $_SESSION['login_errores'] ?? [];
    unset($_SESSION['login_errores']);
?>
<section>
    <div class="container-fluid my-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="d-none d-md-block col-md-9 col-lg-6 col-xl-5">
                <img src="assets/img/login.jpg" style="max-width: 360px;"
                    class="img-fluid" alt="algo de cocina">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <h2>Iniciar Sesion</h2>
                <hr class="mb-5">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="actions/login.php" method="POST">
                    <!-- Username -->
                    <div class="form-outline mb-4">
                        <input type="text" name="username" id="username"
                            class="form-control form-control-lg <?= (!empty($errores) ? 'is-invalid' : '') ?>"
                            placeholder="ingresar usuario" />
                        <label class="form-label" for="username">usuario</label>
                    </div>
                    <!-- Password -->
                    <div class="form-outline mb-3">
                        <input type="password" name="password" id="password"
                            class="form-control form-control-lg <?= (!empty($errores) ? 'is-invalid' : '') ?>"
                            placeholder="ingresar contraseña" />
                        <label class="form-label" for="password">contraseña</label>
                    </div>
                    <!-- login -->
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" class="btn btn-warning w-100">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>