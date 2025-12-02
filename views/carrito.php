<?php
    $productos = Carrito::listar();
?>

<section class="container my-5">
    <h2 class="mb-5">Mi Carrito</h2>
    <?php if(empty($productos)){ ?>
        <div class="my-5">
            <p class="h3">Sin productos!</p>
            <p class="text-muted">Parece que el carrito esta vacio.</p>
            <div class="text-start mb-4">
                <a href="?section=catalogo" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Continuar Comprando
                </a>
            </div>
        </div>
    <?php }else{ ?>
        <div class="row">
            <div class="col-lg-8">
                <?php 
                    foreach ($productos as $item) { 
                        $producto = $item['producto'];
                ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row cart-item mb-3">
                                <div class="col-md-3">
                                    <img src="<?= $producto->getImagen() ?>" alt="Product 1" class="img-fluid rounded">
                                </div>
                                <div class="col-md-5">
                                    <h3 class="card-title text-truncate"><?= $producto->getNombre() ?></h3>
                                    <p class="text-muted"><?= $producto->getDescripcionCorta() ?></p>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex gap-1">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-sm cantidad-menos" type="button">-</button>
                                            <input 
                                                style="max-width:100px" 
                                                type="text" 
                                                class="form-control form-control-sm text-center cantidad-input" 
                                                value="<?= $item['cantidad'] ?>" 
                                                data-id="<?= $producto->getId() ?>"
                                            >
                                            <button class="btn btn-outline-secondary btn-sm cantidad-mas" type="button">+</button>
                                        </div>
                                        <form action="actions/carrito/eliminar_item_acc.php" method="GET">
                                            <input type="hidden" name="id" id="id" value="<?= $producto->getId() ?>">
                                            <button class="ms-2 btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="fw-bold"><?= $producto->formatearPrecio() ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="d-flex gap-2 text-start mb-4">
                    <a href="?section=catalogo" class="btn btn-outline-primary me-2">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Continuar Comprando
                    </a>
                    <form action="actions/carrito/limpiar_carrito_acc.php" method="get">
                        <button class="btn btn-outline-primary">
                            <i class="fa-solid fa-trash me-2"></i>
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card cart-summary">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Resumen del pedido</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <div>
                                <?php foreach ($productos as $producto) { ?>
                                    <span class="d-block text-end"><?= $producto['producto']?->formatearPrecio() ?></span>
                                    <?php if($producto['cantidad'] >= 2) { ?>
                                        <div class="text-end text-muted">x <?= $producto['cantidad'] ?></div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
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
                        <a href="?section=checkout" class="btn btn-primary w-100">Seguir al Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</section>
<script>
    document.querySelectorAll('.cart-item').forEach(item => {

        const input = item.querySelector('.cantidad-input');
        const btnAumentar = item.querySelector('.cantidad-mas');
        const btnDisminuir = item.querySelector('.cantidad-menos');
        const id = input.dataset.id;

        btnAumentar.addEventListener('click', () => {
            let valor = parseInt(input.value) || 1;
            valor++;
            actualizar(id, valor);
        });

        btnDisminuir.addEventListener('click', () => {
            let valor = parseInt(input.value) || 1;
            if(valor > 1){
                valor--;
                actualizar(id, valor);
            }
        });

        function actualizar(id, cantidad){
            window.location.href = `actions/carrito/actualizar_cantidad_acc.php?id=${id}&cantidad=${cantidad}`;
        }
    });
</script>