<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($title ?? 'My App' ?? '', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($app_name ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
    </header>
    
    <main>
        <?php echo $__sections["content"] ?? ""; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 My App</p>
    </footer>
</body>
</html>