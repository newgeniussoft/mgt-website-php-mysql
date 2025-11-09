@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('database/view-table?table=' . urlencode($tableName)) }}"><?= htmlspecialchars($tableName) ?></a></li>
                <li class="breadcrumb-item active">Edit Column</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Edit Column</h1>
        <p class="text-muted">Table: <?= htmlspecialchars($tableName) ?> → Column: <?= htmlspecialchars($columnName) ?></p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Column Definition</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('database/update-column') }}" id="editColumnForm">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">
                        <input type="hidden" name="old_column_name" value="<?= htmlspecialchars($columnName) ?>">

                        <!-- Column Name -->
                        <div class="mb-3">
                            <label class="form-label">
                                Column Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="column_name" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($columnName) ?>"
                                   pattern="[a-zA-Z_][a-zA-Z0-9_]*"
                                   required>
                            <small class="form-text text-muted">
                                Use lowercase letters, numbers, and underscores. Must start with a letter or underscore.
                            </small>
                        </div>

                        <!-- Data Type -->
                        <div class="mb-3">
                            <label class="form-label">
                                Data Type <span class="text-danger">*</span>
                            </label>
                            <select name="data_type" class="form-select" id="dataType" required>
                                <option value="">-- Select Data Type --</option>
                                <?php foreach ($dataTypes as $category => $types): ?>
                                    <optgroup label="<?= htmlspecialchars($category) ?>">
                                        <?php foreach ($types as $type => $description): ?>
                                            <option value="<?= htmlspecialchars($type) ?>" 
                                                    <?= $baseType === $type ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($description) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Length/Values -->
                        <div class="mb-3" id="lengthField">
                            <label class="form-label">
                                Length/Values
                            </label>
                            <input type="text" 
                                   name="length" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($length) ?>"
                                   placeholder="e.g., 255 for VARCHAR, 10,2 for DECIMAL">
                            <small class="form-text text-muted" id="lengthHelp">
                                For VARCHAR/CHAR: max length (e.g., 255)<br>
                                For DECIMAL: precision,scale (e.g., 10,2)<br>
                                For ENUM/SET: comma-separated values in quotes (e.g., 'active','inactive')
                            </small>
                        </div>

                        <!-- Nullable -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="nullable" 
                                       value="1" 
                                       class="form-check-input" 
                                       id="nullable"
                                       <?= $columnDetails['Null'] === 'YES' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nullable">
                                    Allow NULL values
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                If unchecked, this column will be NOT NULL and require a value.
                            </small>
                        </div>

                        <!-- Default Value -->
                        <div class="mb-3">
                            <label class="form-label">
                                Default Value
                            </label>
                            <input type="text" 
                                   name="default_value" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($columnDetails['Default'] ?? '') ?>"
                                   placeholder="Leave empty for no default"
                                   id="defaultValue">
                            <small class="form-text text-muted">
                                Special values: NULL, CURRENT_TIMESTAMP<br>
                                For strings, enter the value without quotes (quotes will be added automatically)<br>
                                Current default: <?= $columnDetails['Default'] !== null ? htmlspecialchars($columnDetails['Default']) : '<em>NULL</em>' ?>
                            </small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong>
                            Modifying a column can affect existing data. Make sure you understand the implications before proceeding.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Column
                            </button>
                            <a href="{{ admin_url('database/view-table?table=' . urlencode($tableName)) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Column Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Current Column Info</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <th>Field:</th>
                            <td><code><?= htmlspecialchars($columnDetails['Field']) ?></code></td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><code><?= htmlspecialchars($columnDetails['Type']) ?></code></td>
                        </tr>
                        <tr>
                            <th>Null:</th>
                            <td>
                                <?= $columnDetails['Null'] === 'YES' ? 
                                    '<span class="badge bg-success">YES</span>' : 
                                    '<span class="badge bg-danger">NO</span>' ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Key:</th>
                            <td>
                                <?php if ($columnDetails['Key'] === 'PRI'): ?>
                                    <span class="badge bg-primary">PRIMARY</span>
                                <?php elseif ($columnDetails['Key'] === 'UNI'): ?>
                                    <span class="badge bg-info">UNIQUE</span>
                                <?php elseif ($columnDetails['Key'] === 'MUL'): ?>
                                    <span class="badge bg-secondary">INDEX</span>
                                <?php else: ?>
                                    <span class="text-muted">None</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Default:</th>
                            <td><?= $columnDetails['Default'] !== null ? htmlspecialchars($columnDetails['Default']) : '<em class="text-muted">NULL</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Extra:</th>
                            <td><?= htmlspecialchars($columnDetails['Extra']) ?: '<em class="text-muted">None</em>' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Important Notes</h6>
                </div>
                <div class="card-body">
                    <h6>Data Loss Warning:</h6>
                    <ul class="small mb-3">
                        <li>Changing data type may truncate data</li>
                        <li>Reducing length may lose data</li>
                        <li>Changing to NOT NULL may fail if NULL values exist</li>
                        <li>Always backup before modifying</li>
                    </ul>

                    <h6>Safe Changes:</h6>
                    <ul class="small mb-3">
                        <li>Renaming column (same type)</li>
                        <li>Increasing VARCHAR length</li>
                        <li>Changing NULL to NOT NULL (if no NULLs exist)</li>
                        <li>Adding/changing default value</li>
                    </ul>

                    <h6>Cannot Change:</h6>
                    <ul class="small mb-0">
                        <li><strong>Primary Keys:</strong> Drop and recreate instead</li>
                        <li><strong>AUTO_INCREMENT:</strong> Use SQL query</li>
                        <li><strong>Foreign Keys:</strong> Drop constraint first</li>
                    </ul>
                </div>
            </div>

            <!-- All Columns -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">All Columns</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($structure as $column): ?>
                                    <tr <?= $column['Field'] === $columnName ? 'class="table-primary"' : '' ?>>
                                        <td>
                                            <strong><?= htmlspecialchars($column['Field']) ?></strong>
                                            <?php if ($column['Key'] === 'PRI'): ?>
                                                <i class="fas fa-key text-warning ms-1" title="Primary Key"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><code class="small"><?= htmlspecialchars($column['Type']) ?></code></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update length field help text based on selected data type
document.getElementById('dataType').addEventListener('change', function() {
    const dataType = this.value.toUpperCase();
    const lengthField = document.getElementById('lengthField');
    const lengthHelp = document.getElementById('lengthHelp');
    const lengthInput = document.querySelector('input[name="length"]');
    
    // Show/hide length field based on data type
    const noLengthTypes = ['TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT', 'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB', 'DATE', 'TIME', 'DATETIME', 'TIMESTAMP', 'YEAR', 'JSON', 'BOOLEAN'];
    
    if (noLengthTypes.includes(dataType)) {
        lengthField.style.display = 'none';
        lengthInput.removeAttribute('required');
    } else {
        lengthField.style.display = 'block';
        
        // Update help text based on type
        if (dataType === 'VARCHAR' || dataType === 'CHAR') {
            lengthHelp.innerHTML = 'Maximum length (e.g., 255). Required for VARCHAR/CHAR.';
            lengthInput.setAttribute('required', 'required');
            lengthInput.placeholder = 'e.g., 255';
        } else if (dataType === 'DECIMAL' || dataType === 'FLOAT' || dataType === 'DOUBLE') {
            lengthHelp.innerHTML = 'Precision and scale (e.g., 10,2 for currency).';
            lengthInput.removeAttribute('required');
            lengthInput.placeholder = 'e.g., 10,2';
        } else if (dataType === 'ENUM' || dataType === 'SET') {
            lengthHelp.innerHTML = 'Comma-separated values in quotes (e.g., \'active\',\'inactive\',\'pending\').';
            lengthInput.setAttribute('required', 'required');
            lengthInput.placeholder = 'e.g., \'active\',\'inactive\'';
        } else if (dataType.includes('INT')) {
            lengthHelp.innerHTML = 'Display width (optional, e.g., 11 for INT).';
            lengthInput.removeAttribute('required');
            lengthInput.placeholder = 'e.g., 11';
        } else {
            lengthHelp.innerHTML = 'Length or size specification (if applicable).';
            lengthInput.removeAttribute('required');
        }
    }
});

// Trigger on page load
document.getElementById('dataType').dispatchEvent(new Event('change'));

// Form validation
document.getElementById('editColumnForm').addEventListener('submit', function(e) {
    const columnName = document.querySelector('input[name="column_name"]').value;
    const dataType = document.querySelector('select[name="data_type"]').value;
    
    if (!columnName || !dataType) {
        e.preventDefault();
        alert('Please fill in all required fields (Column Name and Data Type).');
        return false;
    }
    
    // Validate column name format
    const namePattern = /^[a-zA-Z_][a-zA-Z0-9_]*$/;
    if (!namePattern.test(columnName)) {
        e.preventDefault();
        alert('Invalid column name. Use only letters, numbers, and underscores. Must start with a letter or underscore.');
        return false;
    }
    
    // Confirm action
    if (!confirm('Are you sure you want to modify this column?\n\nThis action may affect existing data and cannot be easily undone!\n\nColumn: ' + columnName + '\nType: ' + dataType)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
