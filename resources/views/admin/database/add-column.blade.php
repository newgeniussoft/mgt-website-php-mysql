@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('database/view-table?table=' . urlencode($tableName)) }}"><?= htmlspecialchars($tableName) ?></a></li>
                <li class="breadcrumb-item active">Add Column</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Add Column</h1>
        <p class="text-muted">Table: <?= htmlspecialchars($tableName) ?></p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Column Definition</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('database/insert-column') }}" id="addColumnForm">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">

                        <!-- Column Name -->
                        <div class="mb-3">
                            <label class="form-label">
                                Column Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="column_name" 
                                   class="form-control" 
                                   placeholder="e.g., user_email, created_at"
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
                                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($description) ?></option>
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
                                       id="nullable">
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
                                   placeholder="Leave empty for no default"
                                   id="defaultValue">
                            <small class="form-text text-muted">
                                Special values: NULL, CURRENT_TIMESTAMP<br>
                                For strings, enter the value without quotes (quotes will be added automatically)
                            </small>
                        </div>

                        <!-- Position -->
                        <div class="mb-3">
                            <label class="form-label">
                                Position
                            </label>
                            <select name="after" class="form-select">
                                <option value="">At the end of table</option>
                                <?php foreach ($structure as $column): ?>
                                    <option value="<?= htmlspecialchars($column['Field']) ?>">
                                        After <?= htmlspecialchars($column['Field']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                Choose where to place the new column in the table structure.
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Column
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
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h6>
                </div>
                <div class="card-body">
                    <h6>Common Data Types:</h6>
                    <ul class="small mb-3">
                        <li><strong>INT</strong> - For IDs and numbers</li>
                        <li><strong>VARCHAR(255)</strong> - For short text</li>
                        <li><strong>TEXT</strong> - For long text</li>
                        <li><strong>DATETIME</strong> - For dates/times</li>
                        <li><strong>DECIMAL(10,2)</strong> - For prices</li>
                        <li><strong>BOOLEAN</strong> - For true/false</li>
                    </ul>

                    <h6>Naming Conventions:</h6>
                    <ul class="small mb-3">
                        <li>Use lowercase with underscores</li>
                        <li>Be descriptive: <code>user_email</code> not <code>ue</code></li>
                        <li>Use singular form: <code>user_name</code></li>
                        <li>Dates: <code>created_at</code>, <code>updated_at</code></li>
                    </ul>

                    <h6>Default Values:</h6>
                    <ul class="small mb-0">
                        <li><strong>NULL</strong> - For nullable fields</li>
                        <li><strong>CURRENT_TIMESTAMP</strong> - For timestamps</li>
                        <li><strong>0</strong> - For numeric fields</li>
                        <li><strong>Empty string</strong> - For text fields</li>
                    </ul>
                </div>
            </div>

            <!-- Current Structure -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Current Structure</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Null</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($structure as $column): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($column['Field']) ?></strong>
                                            <?php if ($column['Key'] === 'PRI'): ?>
                                                <i class="fas fa-key text-warning ms-1" title="Primary Key"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><code class="small"><?= htmlspecialchars($column['Type']) ?></code></td>
                                        <td>
                                            <?= $column['Null'] === 'YES' ? 
                                                '<span class="badge bg-success">YES</span>' : 
                                                '<span class="badge bg-danger">NO</span>' ?>
                                        </td>
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

// Form validation
document.getElementById('addColumnForm').addEventListener('submit', function(e) {
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
    if (!confirm('Are you sure you want to add this column to the table?\n\nColumn: ' + columnName + '\nType: ' + dataType)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
