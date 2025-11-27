<div class="position-absolute" style="top: 2rem; right: 2rem;">
    <?php if(isset($error)){ ?>
        <div class="alert alert-danger visible" id="error" style="transition: opacity 0.5s ease, visibility 0.5s ease;">
            <p class="fw-bolder">Error:</p>
            <ul>
                <?php foreach(explode('|', $error) as $e){ ?>
                    <li><?= $e ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>