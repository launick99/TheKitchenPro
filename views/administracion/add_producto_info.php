<?php
$producto = Producto::getProductById($_GET['id']);
$datosTenicos = $producto->getDatosTecnicos() ?? [new DatosTecnicos];
$imagenesExistentes = $producto->getImagenes();
?>

<section class="container my-5">
    <h2>Agregar Información Adicional</h2>
    <form action="actions/producto/crear_producto_datos_acc.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">

        <!-- DATOS TECNICOS -->
        <fieldset class="mb-4">
            <legend>Datos Técnicos</legend>

            <div id="datos-tecnicos-container">
                <?php foreach ($datosTenicos as $c => $datoTecnico){ ?>
                    <div class="row mb-2 dato-tecnico">
                        <div class="col-5">
                            <input type="text" name="datos_nombre[]" class="form-control" placeholder="Altura" value="<?= $datoTecnico->getClave() ?>">
                        </div>
                        <div class="col-6">
                            <input type="text" name="datos_valor[]" class="form-control"placeholder="18 metros" value="<?= $datoTecnico->getValor() ?>">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-danger eliminar-dato w-100">Eliminar</button>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <button type="button" id="agregar-dato" class="btn btn-secondary btn-sm mt-2">
                Agregar otro dato
            </button>
        </fieldset>


        <!-- IMAGENES -->
        <fieldset class="mb-4">
            <legend>Imágenes</legend>
            <div id="imagenes-container" class="d-flex flex-wrap gap-3">
                <?php foreach ($imagenesExistentes as $img){ ?>
                    <div class="imagen-cuadro position-relative imagen-existente" data-id="<?= $img->getId() ?>">

                        <img src="<?= $img->getUrl() ?>" class="w-100 h-100 rounded" style="object-fit:cover;">

                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 eliminar-imagen">
                            x
                        </button>
                        <input type="hidden" name="imagenes_existentes[]" value="<?= $img->getId() ?>">
                    </div>
                <?php } ?>
                <div id="agregar-imagen" class="imagen-cuadro d-flex justify-content-center align-items-center border border-secondary rounded"
                     style="cursor:pointer; height:128px; width:128px;">
                    <span class="fs-2">+</span>
                </div>
            </div>
            <div id="imagenes-eliminadas"></div>
        </fieldset>
        <button type="submit" class="btn btn-warning">Guardar Información Adicional</button>
    </form>
</section>

<script>
/* ----- DATOS TECNICOS ----- */

document.getElementById('agregar-dato').addEventListener('click', () => {
    const div = document.createElement('div');
    div.classList.add('row', 'mb-2', 'dato-tecnico');
    div.innerHTML = `
        <div class="col-5">
            <input type="text" name="datos_nombre[]" class="form-control" placeholder="Altura">
        </div>
        <div class="col-6">
            <input type="text" name="datos_valor[]" class="form-control" placeholder="18 metros">
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-danger eliminar-dato w-100">X</button>
        </div>
    `;
    document.getElementById('datos-tecnicos-container').appendChild(div);
});

document.addEventListener('click', e => {
    if (e.target.classList.contains('eliminar-dato')) {
        e.target.closest('.dato-tecnico').remove();
    }
});


/* ----- IMAGENES ----- */
const contenedorImagenes = document.getElementById('imagenes-container');
const botonAgregar = document.getElementById('agregar-imagen');
const eliminadasContainer = document.getElementById('imagenes-eliminadas');

botonAgregar.addEventListener('click', () => {
    const input = document.createElement('input');
    input.type = "file";
    input.accept = ".webp";
    input.classList.add("d-none");

    input.addEventListener('change', () => {
        if (input.files.length === 0) return;

        const file = input.files[0];
        const reader = new FileReader();
        const cuadro = document.createElement('div');

        cuadro.classList.add('imagen-cuadro', 'position-relative', 'imagen-nueva');

        const btn = document.createElement('button');
        btn.type = "button";
        btn.className = "btn btn-danger btn-sm position-absolute top-0 end-0 eliminar-imagen";
        btn.innerHTML = "x";

        btn.addEventListener('click', () => cuadro.remove());

        const img = document.createElement('img');
        img.className = "rounded";
        img.style.objectFit = "cover";
        img.style.height = "128px";
        img.style.width = "128px";

        reader.onload = e => img.src = e.target.result;
        reader.readAsDataURL(file);

        cuadro.appendChild(img);
        cuadro.appendChild(btn);
        cuadro.appendChild(input);

        contenedorImagenes.insertBefore(cuadro, botonAgregar);
    });

    input.click();
});

document.addEventListener('click', e => {
    if (e.target.classList.contains('eliminar-imagen')) {
        const cuadro = e.target.closest('.imagen-cuadro');

        if (cuadro.classList.contains('imagen-existente')) {
            const id = cuadro.dataset.id;

            const hidden = document.createElement('input');
            hidden.type = "hidden";
            hidden.name = "imagenes_eliminar[]";
            hidden.value = id;

            eliminadasContainer.appendChild(hidden);
        }

        cuadro.remove();
    }
});
</script>
