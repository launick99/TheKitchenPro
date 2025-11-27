<?php
    $id = $_GET['id'];
    $categoria = Categorias::getCategoriaPorId((int)$id);
    $existe = $categoria->getId();

    if($error){
        $categoria->setNombre($_GET['nombre']);
        $categoria->setDescripcion($_GET['descripcion']);
        $categoria->setActiva($_GET['activa']);
    }
?>

<section class="container my-5">
    <h2>Editar <?= $categoria->getNombre() ?></h2>
    <form action="actions/categoria/actualizar_categoria_acc.php?id=<?= $id ?>" method="post">
        <div class="my-1">
            <label class="form-label" for="nombre">Nombre</label>
            <input class="form-control" type="text" id="nombre" name="nombre" value="<?= $categoria->getNombre() ?>" required>
        </div>
        <div class="my-1">
            <label class="form-label" for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" style="resize: none;"
            ><?= $categoria->getDescripcion() ?></textarea>
        </div>
        <div class="my-1">
            <label class="form-label" for="activa">
                <input type="checkbox" id="activa" name="activa" value="1" checked="<?= $categoria->getActiva() ?>">
                <span>Activa</span>
            </label>
        </div>
        <button class="btn btn-kitchenpro btn btn-warning" type="submit">Guardar</button>
    </form>
</section>