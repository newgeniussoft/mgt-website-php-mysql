<?php
/**
 * Template Items System Installation Script
 * 
 * This script installs the template items management system which allows
 * creating dynamic templates for displaying items from different models.
 * 
 * Features:
 * - Dynamic variable management
 * - Custom HTML, CSS, and JavaScript per template
 * - Model-specific templates
 * - Variable extraction from HTML
 * - Default template designation
 * 
 * Usage: Access this file via browser (http://yoursite.com/install_template_items.php)
 */

require_once __DIR__ . '/bootstrap/app.php';

// Set headers
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Template Items System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .install-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .install-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .install-body {
            padding: 40px;
        }
        .step {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .step.success {
            background: #d4edda;
            border-left-color: #28a745;
        }
        .step.error {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .step.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        .step-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .feature-card {
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
        }
        .feature-icon {
            font-size: 36px;
            color: #667eea;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <h1><i class="fas fa-code"></i> Template Items System</h1>
                <p class="mb-0">Installation & Setup</p>
            </div>
            
            <div class="install-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>About Template Items:</strong> This system allows you to create reusable templates for displaying items from different models (media, posts, pages, tours, etc.) with dynamic variables, custom styling, and JavaScript.
                </div>

                <?php
                $db = null;
                $errors = [];
                $success = [];
                $warnings = [];

                // Database connection
                try {
                    $config = require __DIR__ . '/config/database.php';
                    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
                    $db = new PDO($dsn, $config['username'], $config['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                    $success[] = "Database connection established successfully";
                } catch (PDOException $e) {
                    $errors[] = "Database connection failed: " . $e->getMessage();
                }

                if ($db && empty($errors)) {
                    // Check if table already exists
                    $tableExists = false;
                    try {
                        $stmt = $db->query("SHOW TABLES LIKE 'template_items'");
                        $tableExists = $stmt->rowCount() > 0;
                        
                        if ($tableExists) {
                            $warnings[] = "Table 'template_items' already exists. Installation will be skipped to prevent data loss.";
                        }
                    } catch (PDOException $e) {
                        $errors[] = "Error checking table existence: " . $e->getMessage();
                    }

                    // Run migration if table doesn't exist
                    if (!$tableExists && empty($errors)) {
                        try {
                            $migrationFile = __DIR__ . '/database/migrations/007_create_template_items_table.sql';
                            
                            if (!file_exists($migrationFile)) {
                                $errors[] = "Migration file not found: {$migrationFile}";
                            } else {
                                $sql = file_get_contents($migrationFile);
                                
                                // Split by semicolons and execute each statement
                                $statements = array_filter(
                                    array_map('trim', explode(';', $sql)),
                                    function($stmt) {
                                        return !empty($stmt) && !preg_match('/^--/', $stmt);
                                    }
                                );
                                
                                foreach ($statements as $statement) {
                                    if (!empty(trim($statement))) {
                                        $db->exec($statement);
                                    }
                                }
                                
                                $success[] = "Table 'template_items' created successfully with 4 default templates";
                            }
                        } catch (PDOException $e) {
                            $errors[] = "Error creating table: " . $e->getMessage();
                        }
                    }

                    // Create upload directory
                    $uploadDir = __DIR__ . '/storage/uploads/template-items';
                    if (!is_dir($uploadDir)) {
                        if (mkdir($uploadDir, 0755, true)) {
                            $success[] = "Upload directory created: /storage/uploads/template-items/";
                        } else {
                            $warnings[] = "Could not create upload directory. Please create it manually: {$uploadDir}";
                        }
                    } else {
                        $success[] = "Upload directory already exists";
                    }

                    // Check if TemplateItem model exists
                    $modelFile = __DIR__ . '/app/Models/TemplateItem.php';
                    if (file_exists($modelFile)) {
                        $success[] = "TemplateItem model found";
                    } else {
                        $warnings[] = "TemplateItem model not found at: {$modelFile}";
                    }

                    // Check if controller exists
                    $controllerFile = __DIR__ . '/app/Http/Controllers/Admin/TemplateItemController.php';
                    if (file_exists($controllerFile)) {
                        $success[] = "TemplateItemController found";
                    } else {
                        $warnings[] = "TemplateItemController not found at: {$controllerFile}";
                    }

                    // Check if views exist
                    $viewsDir = __DIR__ . '/resources/views/admin/template-items';
                    if (is_dir($viewsDir)) {
                        $viewFiles = ['index.blade.php', 'create.blade.php', 'edit.blade.php'];
                        $missingViews = [];
                        
                        foreach ($viewFiles as $viewFile) {
                            if (!file_exists($viewsDir . '/' . $viewFile)) {
                                $missingViews[] = $viewFile;
                            }
                        }
                        
                        if (empty($missingViews)) {
                            $success[] = "All view files found";
                        } else {
                            $warnings[] = "Missing view files: " . implode(', ', $missingViews);
                        }
                    } else {
                        $warnings[] = "Views directory not found: {$viewsDir}";
                    }
                }

                // Display results
                foreach ($errors as $error) {
                    echo '<div class="step error">';
                    echo '<i class="fas fa-times-circle step-icon"></i>';
                    echo '<strong>Error:</strong> ' . htmlspecialchars($error);
                    echo '</div>';
                }

                foreach ($warnings as $warning) {
                    echo '<div class="step warning">';
                    echo '<i class="fas fa-exclamation-triangle step-icon"></i>';
                    echo '<strong>Warning:</strong> ' . htmlspecialchars($warning);
                    echo '</div>';
                }

                foreach ($success as $msg) {
                    echo '<div class="step success">';
                    echo '<i class="fas fa-check-circle step-icon"></i>';
                    echo htmlspecialchars($msg);
                    echo '</div>';
                }

                if (empty($errors)) {
                    echo '<div class="alert alert-success mt-4">';
                    echo '<h4><i class="fas fa-check-circle"></i> Installation Complete!</h4>';
                    echo '<p class="mb-0">The Template Items system has been installed successfully.</p>';
                    echo '</div>';
                }
                ?>

                <div class="mt-5">
                    <h4><i class="fas fa-star"></i> Features</h4>
                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-magic"></i></div>
                            <h6>Dynamic Variables</h6>
                            <small>Auto-extract variables from templates</small>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-code"></i></div>
                            <h6>Custom HTML/CSS/JS</h6>
                            <small>Full control over appearance</small>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-cube"></i></div>
                            <h6>Model-Specific</h6>
                            <small>Templates for different models</small>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-star"></i></div>
                            <h6>Default Templates</h6>
                            <small>Set default per model</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5><i class="fas fa-rocket"></i> Getting Started</h5>
                    <ol>
                        <li>Access the admin panel: <code>/admin/template-items</code></li>
                        <li>Review the 4 default templates (Media, Blog Post, Tour, Page)</li>
                        <li>Create new templates or edit existing ones</li>
                        <li>Use templates in your sections with: <code>&lt;items name="model" template="slug" /&gt;</code></li>
                    </ol>
                </div>

                <div class="mt-4">
                    <h5><i class="fas fa-book"></i> Template Syntax</h5>
                    <pre><code>&lt;div class="item"&gt;
  &lt;h3&gt;{{ $item.title }}&lt;/h3&gt;
  &lt;p&gt;{{ $item.description }}&lt;/p&gt;
  &lt;img src="{{ $item.image }}" alt="{{ $item.title }}"&gt;
&lt;/div&gt;</code></pre>
                </div>

                <div class="mt-5 text-center">
                    <?php if (empty($errors)): ?>
                        <a href="/admin/template-items" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right"></i> Go to Template Items
                        </a>
                    <?php else: ?>
                        <button onclick="location.reload()" class="btn btn-warning btn-lg">
                            <i class="fas fa-redo"></i> Retry Installation
                        </button>
                    <?php endif; ?>
                    
                    <a href="/admin/dashboard" class="btn btn-secondary btn-lg">
                        <i class="fas fa-home"></i> Admin Dashboard
                    </a>
                </div>

                <div class="alert alert-warning mt-4">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Security Note:</strong> Please delete this installation file after successful installation for security reasons.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
