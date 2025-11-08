<?php $__layout = "layouts.app"; ?>

<?php $__currentSection = "content"; ob_start(); ?>
    <h1><?php echo htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
    
    <?php if(count($users) > 0): ?>
        <ul>
            <?php foreach($users as $user): ?>
                <li><?php echo htmlspecialchars($user->name ?? '', ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($user->email ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
    
    <?php if(isset($__auth) && $__auth): ?>
        <p>Welcome back, <?php echo htmlspecialchars(auth()->user()->name ?? '', ENT_QUOTES, 'UTF-8'); ?>!</p>
    <?php endif; ?>
    
    <?php if(!isset($__auth) || !$__auth): ?>
        <a href="/login">Login</a>
    <?php endif; ?>
<?php $__sections[$__currentSection] = ob_get_clean(); ?>