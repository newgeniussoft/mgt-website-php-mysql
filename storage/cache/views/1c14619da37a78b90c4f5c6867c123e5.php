<?php $__layout = "layouts.client"; ?>

<?php $__currentSection = "content"; ob_start(); ?>
    <h1 class="text-center"><?php echo htmlspecialchars($message ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
<?php $__sections[$__currentSection] = ob_get_clean(); ?>