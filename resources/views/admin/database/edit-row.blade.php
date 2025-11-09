@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('database/view-table?table=' . urlencode($tableName)) }}"><?= htmlspecialchars($tableName) ?></a></li>
                <li class="breadcrumb-item active">Edit Row</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Edit Row</h1>
        <p class="text-muted">Table: <?= htmlspecialchars($tableName) ?></p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Row Data</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('database/update-row') }}">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">
                        <input type="hidden" name="primary_key" value="<?= htmlspecialchars($primaryKey) ?>">
                        <input type="hidden" name="primary_value" value="<?= htmlspecialchars($primaryValue) ?>">

                        <?php foreach ($structure as $column): ?>
                            <?php
                            $fieldName = $column['Field'];
                            $fieldType = $column['Type'];
                            $isNullable = $column['Null'] === 'YES';
                            $isPrimary = $fieldName === $primaryKey;
                            $isAutoIncrement = strpos($column['Extra'], 'auto_increment') !== false;
                            $value = $row[$fieldName] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label">
                                    <strong><?= htmlspecialchars($fieldName) ?></strong>
                                    <small class="text-muted">(<?= htmlspecialchars($fieldType) ?>)</small>
                                    <?php if ($isPrimary): ?>
                                        <span class="badge bg-primary ms-1">PRIMARY KEY</span>
                                    <?php endif; ?>
                                    <?php if ($isAutoIncrement): ?>
                                        <span class="badge bg-info ms-1">AUTO INCREMENT</span>
                                    <?php endif; ?>
                                    <?php if ($isNullable): ?>
                                        <span class="badge bg-secondary ms-1">NULLABLE</span>
                                    <?php endif; ?>
                                </label>

                                <?php if ($isPrimary || $isAutoIncrement): ?>
                                    <!-- Read-only for primary keys and auto-increment -->
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="<?= htmlspecialchars($value) ?>" 
                                           readonly>
                                    <input type="hidden" 
                                           name="<?= htmlspecialchars($fieldName) ?>" 
                                           value="<?= htmlspecialchars($value) ?>">
                                <?php elseif (stripos($fieldType, 'text') !== false || stripos($fieldType, 'blob') !== false): ?>
                                    <!-- Textarea for TEXT and BLOB types -->
                                    <textarea name="<?= htmlspecialchars($fieldName) ?>" 
                                              class="form-control" 
                                              rows="5"
                                              <?= !$isNullable ? 'required' : '' ?>><?= htmlspecialchars($value) ?></textarea>
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
                                                    <?= $value === $enumValue ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($enumValue) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif (stripos($fieldType, 'date') !== false || stripos($fieldType, 'time') !== false): ?>
                                    <!-- Date/Time inputs -->
                                    <?php
                                    $inputType = 'text';
                                    if (stripos($fieldType, 'datetime') !== false) {
                                        $inputType = 'datetime-local';
                                        $value = str_replace(' ', 'T', $value);
                                    } elseif (stripos($fieldType, 'date') !== false) {
                                        $inputType = 'date';
                                    } elseif (stripos($fieldType, 'time') !== false) {
                                        $inputType = 'time';
                                    }
                                    ?>
                                    <input type="<?= $inputType ?>" 
                                           name="<?= htmlspecialchars($fieldName) ?>" 
                                           class="form-control" 
                                           value="<?= htmlspecialchars($value) ?>"
                                           <?= !$isNullable ? 'required' : '' ?>>
                                <?php elseif (stripos($fieldType, 'int') !== false || stripos($fieldType, 'decimal') !== false || stripos($fieldType, 'float') !== false || stripos($fieldType, 'double') !== false): ?>
                                    <!-- Number input for numeric types -->
                                    <input type="number" 
                                           name="<?= htmlspecialchars($fieldName) ?>" 
                                           class="form-control" 
                                           value="<?= htmlspecialchars($value) ?>"
                                           step="any"
                                           <?= !$isNullable ? 'required' : '' ?>>
                                <?php else: ?>
                                    <!-- Default text input -->
                                    <input type="text" 
                                           name="<?= htmlspecialchars($fieldName) ?>" 
                                           class="form-control" 
                                           value="<?= htmlspecialchars($value) ?>"
                                           <?= !$isNullable ? 'required' : '' ?>>
                                <?php endif; ?>

                                <?php if ($isNullable): ?>
                                    <small class="form-text text-muted">Leave empty for NULL value</small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Row
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tips:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Primary keys and auto-increment fields cannot be edited</li>
                            <li>Leave nullable fields empty to set them to NULL</li>
                            <li>Date/time fields use your browser's date picker</li>
                            <li>ENUM fields show available options in a dropdown</li>
                        </ul>
                    </div>

                    <h6 class="mt-3">Table Structure</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($structure as $column): ?>
                                    <tr>
                                        <td class="small"><?= htmlspecialchars($column['Field']) ?></td>
                                        <td class="small"><code><?= htmlspecialchars($column['Type']) ?></code></td>
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
