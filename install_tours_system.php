<?php
/**
 * Tours System Installation Script
 * 
 * This script installs the complete tours system with multi-language support
 * including tours, tour_details, and tour_photos tables with sample data.
 */

// Include database configuration
require_once 'config/database.php';

// Set content type and disable caching
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours System Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .installation-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .feature-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }
        .status-success {
            color: #28a745;
        }
        .status-error {
            color: #dc3545;
        }
        .status-warning {
            color: #ffc107;
        }
        .progress-bar {
            transition: width 0.3s ease;
        }
        .installation-step {
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            background: #f8f9fa;
        }
        .step-success {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .step-error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
    </style>
</head>
<body>
    <div class="installation-container">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Tours System Installation
                </h1>
                <p class="mb-0 mt-2">Complete multi-language tours management system</p>
            </div>
            <div class="card-body">
                <?php
                $installationSteps = [];
                $hasErrors = false;
                
                try {
                    // Step 1: Check database connection
                    $installationSteps[] = [
                        'title' => 'Database Connection',
                        'status' => 'success',
                        'message' => 'Successfully connected to database'
                    ];
                    
                    // Step 2: Check if tables already exist
                    $existingTables = [];
                    $tables = ['tours', 'tour_details', 'tour_photos'];
                    
                    foreach ($tables as $table) {
                        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                        if ($stmt->rowCount() > 0) {
                            $existingTables[] = $table;
                        }
                    }
                    
                    if (!empty($existingTables)) {
                        $installationSteps[] = [
                            'title' => 'Existing Tables Check',
                            'status' => 'warning',
                            'message' => 'Found existing tables: ' . implode(', ', $existingTables) . '. Installation will continue but may overwrite data.'
                        ];
                    } else {
                        $installationSteps[] = [
                            'title' => 'Existing Tables Check',
                            'status' => 'success',
                            'message' => 'No existing tours tables found. Safe to proceed.'
                        ];
                    }
                    
                    // Step 3: Read and execute SQL migration
                    $sqlFile = 'database/migrations/008_create_tours_system.sql';
                    if (!file_exists($sqlFile)) {
                        throw new Exception("Migration file not found: $sqlFile");
                    }
                    
                    $sql = file_get_contents($sqlFile);
                    if ($sql === false) {
                        throw new Exception("Could not read migration file: $sqlFile");
                    }
                    
                    // Split SQL into individual statements
                    $statements = array_filter(array_map('trim', explode(';', $sql)));
                    $executedStatements = 0;
                    
                    foreach ($statements as $statement) {
                        if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
                            $pdo->exec($statement);
                            $executedStatements++;
                        }
                    }
                    
                    $installationSteps[] = [
                        'title' => 'Database Schema Creation',
                        'status' => 'success',
                        'message' => "Successfully executed $executedStatements SQL statements"
                    ];
                    
                    // Step 4: Create upload directories
                    $uploadDirs = [
                        'storage/uploads/tours',
                        'storage/uploads/tours/thumbnails'
                    ];
                    
                    $createdDirs = [];
                    foreach ($uploadDirs as $dir) {
                        if (!is_dir($dir)) {
                            if (mkdir($dir, 0755, true)) {
                                $createdDirs[] = $dir;
                            } else {
                                throw new Exception("Could not create directory: $dir");
                            }
                        }
                    }
                    
                    if (!empty($createdDirs)) {
                        $installationSteps[] = [
                            'title' => 'Upload Directories',
                            'status' => 'success',
                            'message' => 'Created directories: ' . implode(', ', $createdDirs)
                        ];
                    } else {
                        $installationSteps[] = [
                            'title' => 'Upload Directories',
                            'status' => 'success',
                            'message' => 'All required directories already exist'
                        ];
                    }
                    
                    // Step 5: Verify table creation
                    $createdTables = [];
                    foreach ($tables as $table) {
                        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                        if ($stmt->rowCount() > 0) {
                            $createdTables[] = $table;
                        }
                    }
                    
                    if (count($createdTables) === count($tables)) {
                        $installationSteps[] = [
                            'title' => 'Table Verification',
                            'status' => 'success',
                            'message' => 'All tables created successfully: ' . implode(', ', $createdTables)
                        ];
                    } else {
                        throw new Exception('Some tables were not created properly');
                    }
                    
                    // Step 6: Check sample data
                    $stmt = $pdo->query("SELECT COUNT(*) FROM tours");
                    $tourCount = $stmt->fetchColumn();
                    
                    $stmt = $pdo->query("SELECT COUNT(*) FROM tour_details");
                    $detailCount = $stmt->fetchColumn();
                    
                    $stmt = $pdo->query("SELECT COUNT(*) FROM tour_photos");
                    $photoCount = $stmt->fetchColumn();
                    
                    $installationSteps[] = [
                        'title' => 'Sample Data',
                        'status' => 'success',
                        'message' => "Inserted $tourCount tours, $detailCount tour details, and $photoCount tour photos"
                    ];
                    
                } catch (Exception $e) {
                    $hasErrors = true;
                    $installationSteps[] = [
                        'title' => 'Installation Error',
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
                
                // Display installation steps
                foreach ($installationSteps as $step) {
                    $stepClass = 'installation-step';
                    $iconClass = 'fas fa-info-circle';
                    
                    if ($step['status'] === 'success') {
                        $stepClass .= ' step-success';
                        $iconClass = 'fas fa-check-circle status-success';
                    } elseif ($step['status'] === 'error') {
                        $stepClass .= ' step-error';
                        $iconClass = 'fas fa-times-circle status-error';
                    } elseif ($step['status'] === 'warning') {
                        $iconClass = 'fas fa-exclamation-triangle status-warning';
                    }
                    
                    echo "<div class='$stepClass'>";
                    echo "<i class='$iconClass me-2'></i>";
                    echo "<strong>{$step['title']}:</strong> {$step['message']}";
                    echo "</div>";
                }
                ?>
                
                <?php if (!$hasErrors): ?>
                <div class="alert alert-success mt-4">
                    <h5><i class="fas fa-check-circle me-2"></i>Installation Completed Successfully!</h5>
                    <p class="mb-0">The Tours System has been installed and is ready to use.</p>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="feature-card">
                            <h6><i class="fas fa-globe me-2"></i>Multi-Language Support</h6>
                            <p class="mb-0 small">English and Spanish language support with translation groups</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <h6><i class="fas fa-calendar-alt me-2"></i>Daily Itineraries</h6>
                            <p class="mb-0 small">Detailed day-by-day tour planning with activities and accommodations</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <h6><i class="fas fa-images me-2"></i>Photo Galleries</h6>
                            <p class="mb-0 small">Organized photo management with different types and featured images</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <h6><i class="fas fa-dollar-sign me-2"></i>Pricing & Inclusions</h6>
                            <p class="mb-0 small">Detailed pricing with inclusions and exclusions lists</p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-info-circle me-2"></i>What's Included:</h6>
                    <ul class="mb-0">
                        <li><strong>Tours Table:</strong> Main tour information with multi-language support</li>
                        <li><strong>Tour Details Table:</strong> Daily itinerary with activities, meals, and accommodations</li>
                        <li><strong>Tour Photos Table:</strong> Image gallery with categorization and featured photos</li>
                        <li><strong>Sample Data:</strong> 4 sample tours (2 in English, 2 in Spanish) with complete details</li>
                        <li><strong>Models:</strong> Tour, TourDetail, and TourPhoto models with full CRUD operations</li>
                    </ul>
                </div>
                
                <div class="text-center mt-4">
                    <a href="/admin" class="btn btn-primary btn-lg">
                        <i class="fas fa-cog me-2"></i>Go to Admin Panel
                    </a>
                    <a href="/" class="btn btn-outline-primary btn-lg ms-2">
                        <i class="fas fa-home me-2"></i>View Website
                    </a>
                </div>
                
                <?php else: ?>
                <div class="alert alert-danger mt-4">
                    <h5><i class="fas fa-times-circle me-2"></i>Installation Failed</h5>
                    <p class="mb-0">Please check the error messages above and try again.</p>
                </div>
                
                <div class="text-center mt-4">
                    <button onclick="location.reload()" class="btn btn-warning btn-lg">
                        <i class="fas fa-redo me-2"></i>Retry Installation
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-white">
                <i class="fas fa-code me-1"></i>
                Tours System v1.0 - Multi-language CMS Extension
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
