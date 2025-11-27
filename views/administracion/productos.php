<?php
    $productos = Producto::getTodos();
?>
<section class="my-5 container">
    <h2>Administración de Productos</h2>
    <table class="table table-responsive table-striped table-light">
        <thead class="table-warning">
            <tr>
                <th colspan=6>Productos Activos</th>
            </tr>
            <tr>
                <th></th>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productos as $producto){ ?>
                <?php if($producto->getActivo()){ ?>
                    <tr>
                        <td>
                            <img id="mainImage" class="img-thumbnail" src="<?= $imagen ?>" alt="Imagen principal del producto">
                        </td>
                        <th><?= $producto->getNombre() ?></th>
                        <td><?= $producto->getDescripcionCorta() ?></td>
                        <td><?= $producto->formatearPrecio() ?></td>
                        <td><?= $producto->getStock()?->getStock() ?></td>
                        <td>
                            <a class="btn btn-primary w-100 my-1" href="?section=editar_producto&id=<?= $producto->getId()?>">Editar</a>
                            <a class="btn btn-danger w-100 my-1" href="actions/producto/desactivar_producto_acc.php?id=<?= $producto->getId()?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <!-- Productos Inactivos -->
    <table class="table table-responsive table-striped table-danger">
        <thead class="table-warning">
            <tr>
                <th colspan=6>Productos Inactivos</th>
            </tr>
            <tr>
                <th></th>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productos as $producto){ ?>
                <?php if(!$producto->getActivo()){ ?>
                    <tr>
                        <td>
                            <img id="mainImage" class="img-thumbnail" src="<?= $imagen ?>" alt="Imagen principal del producto">
                        </td>
                        <th><?= $producto->getNombre() ?></th>
                        <td><?= $producto->getDescripcionCorta() ?></td>
                        <td><?= $producto->formatearPrecio() ?></td>
                        <td><?= $producto->getStock()?->getStock() ?></td>
                        <td>
                            <a class="btn btn-primary w-100 my-1" href="?section=editar_producto&id=<?= $producto->getId()?>">Editar</a>
                            <a class="btn btn-success w-100 my-1" href="actions/producto/activar_producto_acc.php?id=<?= $producto->getId()?>">Restaurar</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</section>