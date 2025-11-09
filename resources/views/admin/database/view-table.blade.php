@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($tableName) ?></li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Table: <?= htmlspecialchars($tableName) ?></h1>
            <p class="text-muted mb-0"><?= number_format($totalRows) ?> total rows</p>
        </div>
        <div>
            <a href="{{ admin_url('database/add-row?table=' . urlencode($tableName)) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Row
            </a>
            <a href="{{ admin_url('database/export-table?table=' . urlencode($tableName)) }}" class="btn btn-info">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ admin_url('database/view-table') }}" class="row g-3">
                <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">
                
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search in all columns..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Order By</label>
                    <select name="order_by" class="form-select">
                        <option value="">-- Select Column --</option>
                        <?php foreach ($structure as $column): ?>
                            <option value="<?= htmlspecialchars($column['Field']) ?>" 
                                    <?= $orderBy === $column['Field'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($column['Field']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Direction</label>
                    <select name="order_dir" class="form-select">
                        <option value="ASC" <?= $orderDir === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                        <option value="DESC" <?= $orderDir === 'DESC' ? 'selected' : '' ?>>Descending</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Per Page</label>
                    <select name="per_page" class="form-select">
                        <option value="25" <?= $perPage === 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $perPage === 100 ? 'selected' : '' ?>>100</option>
                        <option value="200" <?= $perPage === 200 ? 'selected' : '' ?>>200</option>
                    </select>
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Structure Info -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white">
            <h6 class="mb-0">Table Structure</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Null</th>
                            <th>Key</th>
                            <th>Default</th>
                            <th>Extra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($structure as $column): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($column['Field']) ?></strong></td>
                                <td><code><?= htmlspecialchars($column['Type']) ?></code></td>
                                <td><?= $column['Null'] === 'YES' ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td>
                                <td>
                                    <?php if ($column['Key'] === 'PRI'): ?>
                                        <span class="badge bg-primary">PRIMARY</span>
                                    <?php elseif ($column['Key'] === 'UNI'): ?>
                                        <span class="badge bg-info">UNIQUE</span>
                                    <?php elseif ($column['Key'] === 'MUL'): ?>
                                        <span class="badge bg-secondary">INDEX</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $column['Default'] !== null ? htmlspecialchars($column['Default']) : '<em class="text-muted">NULL</em>' ?></td>
                                <td><?= htmlspecialchars($column['Extra']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0">Table Data</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <?php foreach ($structure as $column): ?>
                                <th class="text-nowrap">
                                    <?= htmlspecialchars($column['Field']) ?>
                                    <?php if (in_array($column['Field'], $primaryKeys)): ?>
                                        <i class="fas fa-key text-warning ms-1" title="Primary Key"></i>
                                    <?php endif; ?>
                                </th>
                            <?php endforeach; ?>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="<?= count($structure) + 1 ?>" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <?= !empty($search) ? 'No results found' : 'No data in this table' ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['data'] as $row): ?>
                                <?php if (!is_array($row) || empty($row)) continue; ?>
                                <tr>
                                    <?php foreach ($structure as $column): ?>
                                        <td class="text-nowrap" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                            <?php 
                                            $fieldName = $column['Field'];
                                            $value = isset($row[$fieldName]) ? $row[$fieldName] : null;
                                        
                                            if ($value === null || $value === '') {
                                                echo '<em class="text-muted">NULL</em>';
                                            } elseif (is_string($value) && strlen($value) > 100) {
                                                echo htmlspecialchars(substr($value, 0, 100)) . '...';
                                            } else {
                                                echo htmlspecialchars((string)$value);
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-end text-nowrap">
                                        <?php if (!empty($primaryKeys)): ?>
                                            <?php $primaryKey = $primaryKeys[0]; ?>
                                            <?php $primaryValue = isset($row[$primaryKey]) ? $row[$primaryKey] : ''; ?>
                                            <a href="{{ admin_url('database/edit-row?table=' . urlencode($tableName) . '&key=' . urlencode($primaryKey) . '&value=' . urlencode($primaryValue)) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteRow('<?= htmlspecialchars($primaryKey) ?>', '<?= htmlspecialchars($primaryValue) ?>')" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white">
                <nav>
                    <ul class="pagination pagination-sm mb-0 justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?table=<?= urlencode($tableName) ?>&page=<?= $currentPage - 1 ?>&per_page=<?= $perPage ?><?= $orderBy ? '&order_by=' . urlencode($orderBy) : '' ?><?= $orderDir ? '&order_dir=' . urlencode($orderDir) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?table=<?= urlencode($tableName) ?>&page=<?= $i ?>&per_page=<?= $perPage ?><?= $orderBy ? '&order_by=' . urlencode($orderBy) : '' ?><?= $orderDir ? '&order_dir=' . urlencode($orderDir) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?table=<?= urlencode($tableName) ?>&page=<?= $currentPage + 1 ?>&per_page=<?= $perPage ?><?= $orderBy ? '&order_by=' . urlencode($orderBy) : '' ?><?= $orderDir ? '&order_dir=' . urlencode($orderDir) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="text-center mt-2 text-muted small">
                    Page <?= $currentPage ?> of <?= $totalPages ?> (<?= number_format($totalRows) ?> total rows)
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Hidden Form for Delete -->
<form id="deleteForm" method="POST" action="{{ admin_url('database/delete-row') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">
    <input type="hidden" name="primary_key" id="deletePrimaryKey">
    <input type="hidden" name="value" id="deleteValue">
</form>

<script>
function deleteRow(primaryKey, value) {
    if (confirm('Are you sure you want to delete this row?\n\nThis action cannot be undone!')) {
        document.getElementById('deletePrimaryKey').value = primaryKey;
        document.getElementById('deleteValue').value = value;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
