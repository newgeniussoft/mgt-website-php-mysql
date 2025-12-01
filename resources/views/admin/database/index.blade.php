@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Database Management</h1>
            <p class="text-muted">Manage your database tables and data</p>
        </div>
        <a href="{{ admin_url('database/sql-query') }}" class="btn btn-primary">
            <i class="fas fa-code"></i> SQL Query
        </a>
    </div>

    <!-- Database Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="fas fa-table fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Tables</h6>
                            <h3 class="mb-0"><?= $stats['table_count'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Rows</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_rows'] ?? 0) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="fas fa-hdd fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Database Size</h6>
                            <h3 class="mb-0"><?= $stats['total_size_mb'] ?? 0 ?> MB</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Database Tables</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Table Name</th>
                            <th>Rows</th>
                            <th>Size (MB)</th>
                            <th>Engine</th>
                            <th class="text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tables)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-database fa-3x mb-3 d-block"></i>
                                    No tables found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tables as $table): ?>
                                <tr>
                                    <td class="px-4">
                                        <i class="fas fa-table text-primary me-2"></i>
                                        <strong><?= htmlspecialchars($table['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= number_format($table['rows']) ?></span>
                                    </td>
                                    <td><?= number_format($table['size'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= htmlspecialchars($table['engine']) ?></span>
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ admin_url('database/view-table?table=') }}<?= urlencode($table['name']) ?>" 
                                               class="btn btn-outline-primary" 
                                               title="View Data">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ admin_url('database/add-row?table=') }}<?= urlencode($table['name']) ?>" 
                                               class="btn btn-outline-success" 
                                               title="Add Row">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="{{ admin_url('database/export-table?table=') }}<?= urlencode($table['name']) ?>" 
                                               class="btn btn-outline-info" 
                                               title="Export">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-warning" 
                                                    onclick="truncateTable('<?= htmlspecialchars($table['name']) ?>')"
                                                    title="Truncate">
                                                <i class="fas fa-eraser"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    onclick="dropTable('<?= htmlspecialchars($table['name']) ?>')"
                                                    title="Drop">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms for Actions -->
<form id="truncateForm" method="POST" action="{{ admin_url('database/truncate-table') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="table" id="truncateTableName">
</form>

<form id="dropForm" method="POST" action="{{ admin_url('database/drop-table') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="table" id="dropTableName">
</form>

<script>
function truncateTable(tableName) {
    if (confirm(`Are you sure you want to TRUNCATE table "${tableName}"?\n\nThis will delete all rows but keep the table structure.\n\nThis action cannot be undone!`)) {
        document.getElementById('truncateTableName').value = tableName;
        document.getElementById('truncateForm').submit();
    }
}

function dropTable(tableName) {
    if (confirm(`Are you sure you want to DROP table "${tableName}"?\n\nThis will permanently delete the table and all its data.\n\nThis action cannot be undone!`)) {
        if (confirm(`FINAL WARNING: You are about to permanently delete table "${tableName}".\n\nAre you absolutely sure?`)) {
            document.getElementById('dropTableName').value = tableName;
            document.getElementById('dropForm').submit();
        }
    }
}
</script>
@endsection
