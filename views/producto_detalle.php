<?php
    $catalogo = new Catalogo('datos/productos.json');

    $id = $_GET['id'] ?? null;
    $producto = $catalogo->getById($id);

    if (!$producto) {
        echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
        return;
    }

    // Algunas variables útiles
    $nombre = $producto->getNombre();
    $sku = $producto->getId();
    $descripcion = $producto->getDescripcion();
    $precio = $producto->formatearPrecio();
    $imagen = $producto->getImagen();
    $imagenes = $producto->getImagenes() ?? [];
    $data = $producto->getData() ?? [];
?>
<main class="container my-5">
    <div class="row">
        <!-- Galería de imágenes -->
        <div class="col-12 col-md-6">
            <div class="product-main-image">
                <img id="mainImage" class="img-thumbnail" src="<?= $imagen ?>" alt="Imagen principal del producto">
            </div>
            <div class="d-flex flex-wrap galeria-productos py-1">
                <img class="img-thumbnail active small-image" src="<?= $imagen ?>" alt="Imagen principal del producto" onclick="changeImage(this)">
                <?php foreach ($imagenes as $img): ?>
                    <img src="<?= $img ?>" alt="<?= $nombre ?>" class="img-thumbnail small-image" onclick="changeImage(this)">
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Detalles del producto -->
        <div class="col-12 col-md-6">
            <h2><?= $nombre ?></h2>
            <!-- estaba como sku en el trabajo del semetre pasado -->
            <p class="text-muted">SKU: <?= $sku ?></p> 
            <p><?= $descripcion ?></p>
            <p class="fw-bold h2 text-success"><?= $precio ?></p>

            <h3>Facilidades de pago:</h3>
            <ul>
                <li>Pago a meses sin intereses (6, 12 y 18 meses).</li>
                <li>Transferencia bancaria.</li>
                <li>Pago con tarjeta de crédito y débito.</li>
            </ul>
            <!-- No hace nada -->
            <button class="btn btn-warning w-100">
                Comprar ahora
            </button>
        </div>
    </div>

    <?php if (!empty($data)): ?>
        <section class="mt-5">
            <h3>Datos Técnicos</h3>
            <ul class="list-group">
                <?php foreach ($data as $c => $valor): ?>
                    <?php if ($c != 'imagenes'): ?>
                        <li class="list-group-item">
                            <span class="fw-bold"><?= $c ?>:</span> <?= $valor ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
    <script>
        function changeImage(element) {
            document.getElementById('mainImage').src = element.src;
            document.querySelectorAll('.galeria-productos .img-thumbnail').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
        }
    </script>
</main>