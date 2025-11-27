<?php
    $categorias = Categorias::getTodas();
?>
<section class="my-5 container">
    <h2>Administración de Categorias</h2>
    <a class="btn btn-primary mb-4" href="?section=add_categoria">Crear Categoria</a>

    <table class="table table-responsive table-striped table-light">
        <thead class="table-warning">
            <tr>
                <th colspan=3>Categorias Activas</th>
            </tr>
            <tr>
                <th class="w-25">Categoria</th>
                <th>Descripcion</th>
                <th style="width: 128px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categorias as $categoria){ ?>
                <?php if($categoria->getActiva()){ ?>
                    <tr>
                        <th><?= $categoria->getNombre() ?></th>
                        <td><?= $categoria->getDescripcion() ?></td>
                        <td>
                            <a class="btn btn-primary w-100 my-1" href="?section=editar_categoria&id=<?= $categoria->getId()?>">Editar</a>
                            <a class="btn btn-danger w-100 my-1" href="actions/categoria/desactivar_categoria_acc.php?id=<?= $categoria->getId()?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <!-- Categorias Inactivos -->
    <table class="table table-responsive table-striped table-danger">
        <thead class="table-warning">
            <tr>
                <th colspan=3>Categorias Inactivos</th>
            </tr>
            <tr>
                <th class="w-25">Categoria</th>
                <th>Descripcion</th>
                <th style="width: 128px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categorias as $categoria){ ?>
                <?php if(!$categoria->getActiva()){ ?>
                    <tr>
                        <th><?= $categoria->getNombre() ?></th>
                        <td><?= $categoria->getDescripcion() ?></td>
                        <td>
                            <a class="btn btn-primary w-100 my-1" href="?section=editar_categoria&id=<?= $categoria->getId()?>">Editar</a>
                            <a class="btn btn-success w-100 my-1" href="actions/categoria/activar_categoria_acc.php?id=<?= $categoria->getId()?>">Restaurar</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</section>