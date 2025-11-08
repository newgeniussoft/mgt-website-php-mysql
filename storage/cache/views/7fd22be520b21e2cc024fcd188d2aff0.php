<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset('css/styles.css') ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($title ?? 'My App' ?? '', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <?php echo $this->render("components.header", array_merge(get_defined_vars(), [])); ?>
    
    <main>
        <?php echo $__sections["content"] ?? ""; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 My App</p>
    </footer>
</body>
</html>