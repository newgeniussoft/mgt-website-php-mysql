<?php

namespace App\Http\Controllers;

use App\Models\Database;
use App\View\View;

class DatabaseController
{
    /**
     * Show database tables list
     */
    public function index()
    {
        $tables = Database::getAllTables();
        $stats = Database::getDatabaseStats();
        
        return View::make('admin.database.index', [
            'tables' => $tables,
            'stats' => $stats,
            'title' => 'Database Management'
        ]);
    }
    
    /**
     * Show table structure and data
     */
    public function viewTable()
    {
        $tableName = $_GET['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
        $orderBy = $_GET['order_by'] ?? null;
        $orderDir = $_GET['order_dir'] ?? 'ASC';
        $search = $_GET['search'] ?? '';
        
        $structure = Database::getTableStructure($tableName);
        $primaryKeys = Database::getPrimaryKey($tableName);
        
        if (!empty($search)) {
            $data = Database::searchTable($tableName, $search, $page, $perPage);
        } else {
            $data = Database::getTableData($tableName, $page, $perPage, $orderBy, $orderDir);
        }
        
        $totalRows = Database::getTableRowCount($tableName);
        $totalPages = ceil($totalRows / $perPage);

        $dataParsed = [];
        foreach($data as $row){
            if(is_array($row)){
                $item  = [];
                foreach($row as $key => $value){
                    $item[$key] = $value;
                }
                $dataParsed[] = $item;
            }
        }

       
        return View::make('admin.database.view-table', [
            'tableName' => $tableName,
            'structure' => $structure,
            'data' => $dataParsed,
            'primaryKeys' => $primaryKeys,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows,
            'perPage' => $perPage,
            'orderBy' => $orderBy,
            'orderDir' => $orderDir,
            'search' => $search,
            'title' => "Table: {$tableName}"
        ]);
    }
    
    /**
     * Show edit row form
     */
    public function editRow()
    {
        $tableName = $_GET['table'] ?? '';
        $primaryKey = $_GET['key'] ?? '';
        $value = $_GET['value'] ?? '';
        
        if (empty($tableName) || empty($primaryKey) || empty($value)) {
            $_SESSION['error'] = 'Invalid parameters';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        $structure = Database::getTableStructure($tableName);
        $row = Database::getRow($tableName, $primaryKey, $value);
        
        if (!$row) {
            $_SESSION['error'] = 'Row not found';
            header("Location: ".admin_url('database/view-table?table={$tableName}'));
            exit;
        }
        
        return View::make('admin.database.edit-row', [
            'tableName' => $tableName,
            'structure' => $structure,
            'row' => $row,
            'primaryKey' => $primaryKey,
            'primaryValue' => $value,
            'title' => "Edit Row - {$tableName}"
        ]);
    }
    
    /**
     * Update row
     */
    public function updateRow()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: '.admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        $primaryKey = $_POST['primary_key'] ?? '';
        $primaryValue = $_POST['primary_value'] ?? '';
        
        if (empty($tableName) || empty($primaryKey) || empty($primaryValue)) {
            $_SESSION['error'] = 'Invalid parameters';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        // Get structure to know which fields to update
        $structure = Database::getTableStructure($tableName);
        $data = [];
        
        foreach ($structure as $column) {
            $fieldName = $column['Field'];
            if (isset($_POST[$fieldName])) {
                // Handle NULL values
                if ($_POST[$fieldName] === '' && strpos($column['Null'], 'YES') !== false) {
                    $data[$fieldName] = null;
                } else {
                    $data[$fieldName] = $_POST[$fieldName];
                }
            }
        }
        
        $result = Database::updateRow($tableName, $primaryKey, $primaryValue, $data);
        
        if ($result) {
            $_SESSION['success'] = 'Row updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update row';
        }
        header("Location: ".admin_url('database/view-table?table={$tableName}'));
        exit;
    }
    
    /**
     * Show add row form
     */
    public function addRow()
    {
        $tableName = $_GET['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        $structure = Database::getTableStructure($tableName);
        
        return View::make('admin.database.add-row', [
            'tableName' => $tableName,
            'structure' => $structure,
            'title' => "Add Row - {$tableName}"
        ]);
    }
    
    /**
     * Insert new row
     */
    public function insertRow()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: '.admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: '.admin_url('database'));
            exit;
        }
        
        // Get structure to know which fields to insert
        $structure = Database::getTableStructure($tableName);
        $data = [];
        
        foreach ($structure as $column) {
            $fieldName = $column['Field'];
            
            // Skip auto-increment fields
            if (strpos($column['Extra'], 'auto_increment') !== false) {
                continue;
            }
            
            if (isset($_POST[$fieldName])) {
                // Handle NULL values
                if ($_POST[$fieldName] === '' && strpos($column['Null'], 'YES') !== false) {
                    $data[$fieldName] = null;
                } else {
                    $data[$fieldName] = $_POST[$fieldName];
                }
            }
        }
        
        $result = Database::insertRow($tableName, $data);
        
        if ($result) {
            $_SESSION['success'] = 'Row added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add row';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
    
    /**
     * Delete row
     */
    public function deleteRow()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        $primaryKey = $_POST['primary_key'] ?? '';
        $value = $_POST['value'] ?? '';
        
        if (empty($tableName) || empty($primaryKey) || empty($value)) {
            $_SESSION['error'] = 'Invalid parameters';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $result = Database::deleteRow($tableName, $primaryKey, $value);
        
        if ($result) {
            $_SESSION['success'] = 'Row deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete row';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
    
    /**
     * SQL Query interface
     */
    public function sqlQuery()
    {
        $result = null;
        $query = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = 'Invalid CSRF token';
            } else {
                $query = $_POST['query'] ?? '';
                
                if (!empty($query)) {
                    $result = Database::executeQuery($query);
                }
            }
        }
        
        return view('admin/database/sql-query', [
            'result' => $result,
            'query' => $query,
            'title' => 'SQL Query'
        ]);
    }
    
    /**
     * Export table
     */
    public function exportTable()
    {
        $tableName = $_GET['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $sql = Database::exportTableSQL($tableName);
        
        if ($sql) {
            header('Content-Type: application/sql');
            header("Content-Disposition: attachment; filename=\"{$tableName}_" . date('Y-m-d_H-i-s') . ".sql\"");
            echo $sql;
            exit;
        } else {
            $_SESSION['error'] = 'Failed to export table';
            header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
            exit;
        }
    }
    
    /**
     * Truncate table
     */
    public function truncateTable()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $result = Database::truncateTable($tableName);
        
        if ($result) {
            $_SESSION['success'] = "Table '{$tableName}' truncated successfully";
        } else {
            $_SESSION['error'] = 'Failed to truncate table';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
    
    /**
     * Drop table
     */
    public function dropTable()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $result = Database::dropTable($tableName);
        
        if ($result) {
            $_SESSION['success'] = "Table '{$tableName}' dropped successfully";
            header('Location: ' . admin_url('database'));
        } else {
            $_SESSION['error'] = 'Failed to drop table';
            header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        }
        exit;
    }
    
    /**
     * Show add column form
     */
    public function addColumn()
    {
        $tableName = $_GET['table'] ?? '';
        
        if (empty($tableName)) {
            $_SESSION['error'] = 'Table name is required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $structure = Database::getTableStructure($tableName);
        $dataTypes = Database::getDataTypes();
        
        return View::make('admin.database.add-column', [
            'tableName' => $tableName,
            'structure' => $structure,
            'dataTypes' => $dataTypes,
            'title' => "Add Column - {$tableName}"
        ]);
    }
    
    /**
     * Insert new column
     */
    public function insertColumn()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        $columnName = $_POST['column_name'] ?? '';
        $dataType = $_POST['data_type'] ?? '';
        $length = $_POST['length'] ?? null;
        $nullable = isset($_POST['nullable']) && $_POST['nullable'] === '1';
        $defaultValue = $_POST['default_value'] ?? null;
        $after = $_POST['after'] ?? null;
        
        if (empty($tableName) || empty($columnName) || empty($dataType)) {
            $_SESSION['error'] = 'Table name, column name, and data type are required';
            header('Location: ' . admin_url('database/add-column?table=' . urlencode($tableName)));
            exit;
        }
        
        $result = Database::addColumn($tableName, $columnName, $dataType, $length, $nullable, $defaultValue, $after);
        
        if ($result) {
            $_SESSION['success'] = "Column '{$columnName}' added successfully";
        } else {
            $_SESSION['error'] = 'Failed to add column';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
    
    /**
     * Show edit column form
     */
    public function editColumn()
    {
        $tableName = $_GET['table'] ?? '';
        $columnName = $_GET['column'] ?? '';
        
        if (empty($tableName) || empty($columnName)) {
            $_SESSION['error'] = 'Table name and column name are required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $structure = Database::getTableStructure($tableName);
        $columnDetails = Database::getColumnDetails($tableName, $columnName);
        $dataTypes = Database::getDataTypes();
        
        if (!$columnDetails) {
            $_SESSION['error'] = 'Column not found';
            header('Location: ' . admin_url('database/view-table?table=' . urlencode($tableName)));
            exit;
        }
        
        // Parse column type to extract base type and length
        $type = $columnDetails['Type'];
        $baseType = $type;
        $length = '';
        
        if (preg_match('/^(\w+)\((.+)\)$/', $type, $matches)) {
            $baseType = strtoupper($matches[1]);
            $length = $matches[2];
        } else {
            $baseType = strtoupper($type);
        }
        
        return View::make('admin.database.edit-column', [
            'tableName' => $tableName,
            'structure' => $structure,
            'columnDetails' => $columnDetails,
            'columnName' => $columnName,
            'baseType' => $baseType,
            'length' => $length,
            'dataTypes' => $dataTypes,
            'title' => "Edit Column - {$tableName}.{$columnName}"
        ]);
    }
    
    /**
     * Update column
     */
    public function updateColumn()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        $oldColumnName = $_POST['old_column_name'] ?? '';
        $newColumnName = $_POST['column_name'] ?? '';
        $dataType = $_POST['data_type'] ?? '';
        $length = $_POST['length'] ?? null;
        $nullable = isset($_POST['nullable']) && $_POST['nullable'] === '1';
        $defaultValue = $_POST['default_value'] ?? null;
        
        if (empty($tableName) || empty($oldColumnName) || empty($newColumnName) || empty($dataType)) {
            $_SESSION['error'] = 'All required fields must be filled';
            header('Location: ' . admin_url('database/edit-column?table=' . urlencode($tableName) . '&column=' . urlencode($oldColumnName)));
            exit;
        }
        
        $result = Database::modifyColumn($tableName, $oldColumnName, $newColumnName, $dataType, $length, $nullable, $defaultValue);
        
        if ($result) {
            $_SESSION['success'] = "Column '{$oldColumnName}' updated successfully";
        } else {
            $_SESSION['error'] = 'Failed to update column';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
    
    /**
     * Delete column
     */
    public function deleteColumn()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $tableName = $_POST['table'] ?? '';
        $columnName = $_POST['column'] ?? '';
        
        if (empty($tableName) || empty($columnName)) {
            $_SESSION['error'] = 'Table name and column name are required';
            header('Location: ' . admin_url('database'));
            exit;
        }
        
        $result = Database::dropColumn($tableName, $columnName);
        
        if ($result) {
            $_SESSION['success'] = "Column '{$columnName}' deleted successfully";
        } else {
            $_SESSION['error'] = 'Failed to delete column';
        }
        
        header("Location: " . admin_url('database/view-table?table=' . urlencode($tableName)));
        exit;
    }
}
