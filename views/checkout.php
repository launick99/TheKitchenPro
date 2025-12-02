<?php
    $productos = Carrito::listar();
    $nombre         =   $_GET['nombre']         ?? null;
    $tarjeta        =   $_GET['tarjeta']        ?? null;
    $vencimiento    =   $_GET['vencimiento']    ?? null;
?>
<section class="my-5 container">
    <h2>Formulario de Pedido Online</h2>
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col">
            <div class="card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="d-block mb-4">
                                <a href="?section=carrito" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Volver al carrito
                                </a>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <p class="mb-1">Productos</p>
                                    <p class="mb-0"><?= count($productos) > 1 ? count($productos)." productos agregados" : "1 producto agregado" ?></p>
                                </div>
                            </div>
                            <?php foreach ($productos as $item) {
                                $cantidad = $item['cantidad'];
                                $producto = $item['producto'];
                            ?>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row cart-item mb-3">
                                            <div class="col-4 col-md-2">
                                                <img src="<?= $producto->getImagen() ?>" alt="Product 1" class="img-fluid rounded">
                                            </div>
                                            <div class="col-8 col-md-6">
                                                <h3 class="card-title text-truncate"><?= $producto->getNombre() ?></h3>
                                                <p class="text-muted"><?= $producto->getDescripcionCorta() ?></p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <p class="h3 fw-bold"><?= $producto->formatearPrecio() ?></p>
                                                <p class="text-muted fw-bold"><?= $cantidad ?>u</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                        <div class="col-lg-5">
                            <div class="card rounded-3" style="background-color: #ffc10726;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h2 class="mb-0">Detalles de tarjeta</h2>
                                    </div>

                                    <p class="small mb-2">Aceptamos</p>
                                    <i class="fa fa-cc-mastercard fa-2x me-2"></i>
                                    <i class="fa fa-cc-visa fa-2x me-2"></i>

                                    <form class="mt-4" action="actions/procesar_compra_acc.php" method="POST">
                                        <div>
                                            <label class="form-label" for="nombre">Nombre y Apellido</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" siez="17" placeholder="NOMBRE APELLIDO" value="<?= $nombre ?>" required/>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <label class="form-label" for="tarjeta">Numero</label>
                                            <input type="text" id="tarjeta" name="tarjeta" class="form-control" siez="17" placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" value="<?= $tarjeta ?>" required/>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="form-outline form-white">
                                                    <label class="form-label" for="vencimiento">Vencimiento</label>
                                                    <input type="text" id="vencimiento" name="vencimiento" class="form-control" placeholder="MM/YYYY" size="7" minlength="7" maxlength="7" value="<?= $vencimiento ?>" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-outline form-white">
                                                    <label class="form-label" for="cvv">Cvv</label>
                                                    <input type="password" id="cvv" name="cvv" class="form-control" placeholder="***" size="1" minlength="3" maxlength="3" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between py-3">
                                            <span>Subtutal</span>
                                            <span>$<?= Carrito::precioFinal() - Carrito::getEnvio() ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between py-3">
                                            <span>Envio</span>
                                            <span>$<?= Carrito::getEnvio() ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-4">
                                            <strong>Total</strong>
                                            <strong>$<?= Carrito::precioFinal() ?></strong>
                                        </div>
                                        <button class="btn btn-primary w-100">Comprar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tarjetaInput = document.getElementById('tarjeta');
        const vencimientoInput = document.getElementById('vencimiento');

        // se buguea si queres remplazar en el medio
        tarjetaInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); 
            value = value.match(/.{1,4}/g)?.join(' ') || '';
            e.target.value = value;
        });

        vencimientoInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.slice(0,2) + '/' + value.slice(2,6);
            }
            e.target.value = value;
        });
    });
</script>