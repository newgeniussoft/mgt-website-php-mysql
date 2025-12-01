@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('database/view-table?table=' . urlencode($tableName)) }}"><?= htmlspecialchars($tableName) ?></a></li>
                <li class="breadcrumb-item active">Add Row</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Add New Row</h1>
        <p class="text-muted">Table: <?= htmlspecialchars($tableName) ?></p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">New Row Data</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('database/insert-row') }}">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">

                        <?php foreach ($structure as $column): ?>
                            <?php
                            $fieldName = $column['Field'];
                            $fieldType = $column['Type'];
                            $isNullable = $column['Null'] === 'YES';
                            $isAutoIncrement = strpos($column['Extra'], 'auto_increment') !== false;
                            $defaultValue = $column['Default'];
                            ?>

                            <?php if ($isAutoIncrement): ?>
                                <!-- Skip auto-increment fields -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <strong><?= htmlspecialchars($fieldName) ?></strong>
                                        <small class="text-muted">(<?= htmlspecialchars($fieldType) ?>)</small>
                                        <span class="badge bg-info ms-1">AUTO INCREMENT</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="Auto-generated" 
                                           readonly>
                                    <small class="form-text text-muted">This field will be automatically generated</small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <strong><?= htmlspecialchars($fieldName) ?></strong>
                                        <small class="text-muted">(<?= htmlspecialchars($fieldType) ?>)</small>
                                        <?php if ($isNullable): ?>
                                            <span class="badge bg-secondary ms-1">NULLABLE</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger ms-1">REQUIRED</span>
                                        <?php endif; ?>
                                    </label>

                                    <?php if (stripos($fieldType, 'text') !== false || stripos($fieldType, 'blob') !== false): ?>
                                        <!-- Textarea for TEXT and BLOB types -->
                                        <textarea name="<?= htmlspecialchars($fieldName) ?>" 
                                                  class="form-control" 
                                                  rows="5"
                                                  <?= !$isNullable ? 'required' : '' ?>><?= htmlspecialchars($defaultValue ?? '') ?></textarea>
                                    <?php elseif (stripos($fieldType, 'enum') !== false): ?>
                                        <!-- Select for ENUM types -->
                                        <?php
                                        preg_match("/^enum\(\'(.*)\'\)$/", $fieldType, $matches);
                                        $enumValues = explode("','", $matches[1] ?? '');
                                        ?>
                                        <select name="<?= htmlspecialchars($fieldName) ?>" 
                                                class="form-select"
                                                <?= !$isNullable ? 'required' : '' ?>>
                                            <?php if ($isNullable): ?>
                                                <option value="">-- NULL --</option>
                                            <?php endif; ?>
                                            <?php foreach ($enumValues as $enumValue): ?>
                                                <option value="<?= htmlspecialchars($enumValue) ?>" 
                                                        <?= $defaultValue === $enumValue ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($enumValue) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php elseif (stripos($fieldType, 'date') !== false || stripos($fieldType, 'time') !== false): ?>
                                        <!-- Date/Time inputs -->
                                        <?php
                                        $inputType = 'text';
                                        $defaultVal = $defaultValue;
                                        
                                        if (stripos($fieldType, 'datetime') !== false) {
                                            $inputType = 'datetime-local';
                                            if ($defaultValue === 'CURRENT_TIMESTAMP' || $defaultValue === null) {
                                                $defaultVal = date('Y-m-d\TH:i');
                                            }
                                        } elseif (stripos($fieldType, 'date') !== false) {
                                            $inputType = 'date';
                                            if ($defaultValue === null) {
                                                $defaultVal = date('Y-m-d');
                                            }
                                        } elseif (stripos($fieldType, 'time') !== false) {
                                            $inputType = 'time';
                                            if ($defaultValue === null) {
                                                $defaultVal = date('H:i');
                                            }
                                        }
                                        ?>
                                        <input type="<?= $inputType ?>" 
                                               name="<?= htmlspecialchars($fieldName) ?>" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($defaultVal ?? '') ?>"
                                               <?= !$isNullable ? 'required' : '' ?>>
                                    <?php elseif (stripos($fieldType, 'int') !== false || stripos($fieldType, 'decimal') !== false || stripos($fieldType, 'float') !== false || stripos($fieldType, 'double') !== false): ?>
                                        <!-- Number input for numeric types -->
                                        <input type="number" 
                                               name="<?= htmlspecialchars($fieldName) ?>" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($defaultValue ?? '0') ?>"
                                               step="any"
                                               <?= !$isNullable ? 'required' : '' ?>>
                                    <?php else: ?>
                                        <!-- Default text input -->
                                        <input type="text" 
                                               name="<?= htmlspecialchars($fieldName) ?>" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($defaultValue ?? '') ?>"
                                               <?= !$isNullable ? 'required' : '' ?>>
                                    <?php endif; ?>

                                    <?php if ($isNullable): ?>
                                        <small class="form-text text-muted">Leave empty for NULL value</small>
                                    <?php elseif ($defaultValue !== null): ?>
                                        <small class="form-text text-muted">Default: <?= htmlspecialchars($defaultValue) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Row
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Field Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tips:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Auto-increment fields are generated automatically</li>
                            <li>Required fields must have a value</li>
                            <li>Leave nullable fields empty to set them to NULL</li>
                            <li>Default values are pre-filled when available</li>
                        </ul>
                    </div>

                    <h6 class="mt-3">Table Structure</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Null</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($structure as $column): ?>
                                    <tr>
                                        <td class="small"><?= htmlspecialchars($column['Field']) ?></td>
                                        <td class="small"><code><?= htmlspecialchars($column['Type']) ?></code></td>
                                        <td class="small">
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
@endsection
