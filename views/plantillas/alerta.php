<div class="position-absolute" style="top: 2rem; right: 2rem;">
    <?= Alerta::getAlertas() ?>
</div>
<script>
    window.addEventListener('DOMContentLoaded', () => {
         document.querySelectorAll('.alert-dismissible').forEach(element => {
            setTimeout(() => {
                element.classList.remove('show');
            }, 4000);
            setTimeout(() => {
                element.remove();
            }, 4500);
        });
    })
</script>