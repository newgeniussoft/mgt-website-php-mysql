<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= $title ?? 'My App' ?></title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>
    
    <main class="container">
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>