<div class="container my-5">
    <div class="text-center my-2">
        <div class="w-50 m-auto">
            <?php echo( file_get_contents('./assets/img/500.svg') ) ?>
        </div>
        <h2>
            500 - Error interno del servidor
        </h2>
        <p class="h3">
            <?= $error->getMessage() ?>
            <br>
            hm.. supongo que no aprobe la materia si sale esto..
        </p>
    </div>
</div>