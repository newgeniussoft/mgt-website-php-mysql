@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ admin_url('database') }}">Database</a></li>
                <li class="breadcrumb-item active">SQL Query</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">SQL Query</h1>
        <p class="text-muted">Execute custom SQL queries</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Query Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">SQL Editor</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('database/sql-query') }}">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">SQL Query</label>
                            <textarea name="query" 
                                      id="sqlQuery" 
                                      class="form-control font-monospace" 
                                      rows="10" 
                                      placeholder="Enter your SQL query here..."
                                      required><?= htmlspecialchars($query) ?></textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                Be careful with UPDATE, DELETE, and DROP queries!
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-play"></i> Execute Query
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="clearQuery()">
                                <i class="fas fa-eraser"></i> Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Query Result -->
            <?php if ($result !== null): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Query Result</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($result['success']): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Query executed successfully!
                                <?php if (isset($result['rows_affected'])): ?>
                                    <br><strong>Rows affected:</strong> <?= $result['rows_affected'] ?>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($result['data']) && !empty($result['data'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <?php foreach (array_keys($result['data'][0]) as $column): ?>
                                                    <th><?= htmlspecialchars($column) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result['data'] as $row): ?>
                                                <tr>
                                                    <?php foreach ($row as $value): ?>
                                                        <td>
                                                            <?php 
                                                            if ($value === null) {
                                                                echo '<em class="text-muted">NULL</em>';
                                                            } elseif (strlen($value) > 100) {
                                                                echo htmlspecialchars(substr($value, 0, 100)) . '...';
                                                            } else {
                                                                echo htmlspecialchars($value);
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-muted small mt-2">
                                    <i class="fas fa-info-circle"></i>
                                    Showing <?= count($result['data']) ?> rows
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <strong>Query Error:</strong><br>
                                <code><?= htmlspecialchars($result['error']) ?></code>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <!-- Quick Reference -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Quick Reference</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Common Queries</h6>
                    
                    <div class="mb-3">
                        <strong>Select All:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>SELECT * FROM table_name;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('SELECT * FROM table_name;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Select with Condition:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>SELECT * FROM table_name WHERE column = 'value';</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('SELECT * FROM table_name WHERE column = \'value\';')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Count Rows:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>SELECT COUNT(*) FROM table_name;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('SELECT COUNT(*) FROM table_name;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Update:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>UPDATE table_name SET column = 'value' WHERE id = 1;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('UPDATE table_name SET column = \'value\' WHERE id = 1;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Delete:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>DELETE FROM table_name WHERE id = 1;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('DELETE FROM table_name WHERE id = 1;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Show Tables:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>SHOW TABLES;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('SHOW TABLES;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>

                    <div class="mb-3">
                        <strong>Describe Table:</strong>
                        <pre class="bg-light p-2 rounded small mb-1"><code>DESCRIBE table_name;</code></pre>
                        <button class="btn btn-sm btn-outline-primary" onclick="insertQuery('DESCRIBE table_name;')">
                            <i class="fas fa-copy"></i> Use
                        </button>
                    </div>
                </div>
            </div>

            <!-- Safety Warning -->
            <div class="card border-0 shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0 text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Safety Warning
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Always backup your database before running destructive queries</li>
                        <li>Use WHERE clauses carefully with UPDATE and DELETE</li>
                        <li>Test queries on a development database first</li>
                        <li>DROP and TRUNCATE operations cannot be undone</li>
                        <li>Be careful with JOIN operations on large tables</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function insertQuery(query) {
    document.getElementById('sqlQuery').value = query;
}

function clearQuery() {
    if (confirm('Clear the SQL query?')) {
        document.getElementById('sqlQuery').value = '';
    }
}
</script>
@endsection
