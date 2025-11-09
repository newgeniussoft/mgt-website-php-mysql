<?php

namespace App\Models;

use PDO;
use PDOException;

class Database extends Model
{
    /**
     * Get Connection
     */

    public static function getDB()
    {
        $instance = new static();
        return $instance->getConnection();
    }
    /**
     * Get all tables in the database
     */
    public static function getAllTables()
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $stmt = $db->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $tableInfo = [];
            foreach ($tables as $table) {
                $tableInfo[] = [
                    'name' => $table,
                    'rows' => self::getTableRowCount($table),
                    'size' => self::getTableSize($table),
                    'engine' => self::getTableEngine($table)
                ];
            }
            
            return $tableInfo;
        } catch (PDOException $e) {
            error_log("Error getting tables: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get table structure (columns)
     */
    public static function getTableStructure($tableName)
    {
        try {
            $db = self::getDB();
            $stmt = $db->query("DESCRIBE `{$tableName}`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting table structure: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get table data with pagination
     */
    public static function getTableData($tableName, $page = 1, $perPage = 50, $orderBy = null, $orderDir = 'ASC')
    {
        try {
            $db = self::getDB();
            $offset = ($page - 1) * $perPage;
            
            $orderClause = '';
            if ($orderBy) {
                $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
                $orderClause = "ORDER BY `{$orderBy}` {$orderDir}";
            }
            
            $sql = "SELECT * FROM `{$tableName}` {$orderClause} LIMIT {$perPage} OFFSET {$offset}";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting table data: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total row count for a table
     */
    public static function getTableRowCount($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM `{$tableName}`");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting row count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get table size in MB
     */
    public static function getTableSize($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $dbName = $_ENV['DB_NAME'] ?? 'database';
            
            $sql = "SELECT 
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = :dbname 
                    AND table_name = :tablename";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':dbname' => $dbName,
                ':tablename' => $tableName
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['size_mb'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting table size: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get table engine
     */
    public static function getTableEngine($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $dbName = $_ENV['DB_NAME'] ?? 'database';
            
            $sql = "SELECT engine 
                    FROM information_schema.TABLES 
                    WHERE table_schema = :dbname 
                    AND table_name = :tablename";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':dbname' => $dbName,
                ':tablename' => $tableName
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['engine'] ?? 'Unknown';
        } catch (PDOException $e) {
            error_log("Error getting table engine: " . $e->getMessage());
            return 'Unknown';
        }
    }
    
    /**
     * Get primary key column(s) for a table
     */
    public static function getPrimaryKey($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $sql = "SHOW KEYS FROM `{$tableName}` WHERE Key_name = 'PRIMARY'";
            $stmt = $db->query($sql);
            $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return array_map(function($key) {
                return $key['Column_name'];
            }, $keys);
        } catch (PDOException $e) {
            error_log("Error getting primary key: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get a single row by primary key
     */
    public static function getRow($tableName, $primaryKey, $value)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $sql = "SELECT * FROM `{$tableName}` WHERE `{$primaryKey}` = :value LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([':value' => $value]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting row: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update a row
     */
    public static function updateRow($tableName, $primaryKey, $primaryValue, $data)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            $setParts = [];
            $params = [];
            
            foreach ($data as $column => $value) {
                if ($column !== $primaryKey) {
                    $setParts[] = "`{$column}` = :{$column}";
                    $params[":{$column}"] = $value;
                }
            }
            
            $params[':primary_value'] = $primaryValue;
            
            $sql = "UPDATE `{$tableName}` SET " . implode(', ', $setParts) . " WHERE `{$primaryKey}` = :primary_value";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating row: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Insert a new row
     */
    public static function insertRow($tableName, $data)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            $columns = array_keys($data);
            $placeholders = array_map(function($col) {
                return ":{$col}";
            }, $columns);
            
            $sql = "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) 
                    VALUES (" . implode(', ', $placeholders) . ")";
            
            $params = [];
            foreach ($data as $column => $value) {
                $params[":{$column}"] = $value;
            }
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                return $db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error inserting row: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a row
     */
    public static function deleteRow($tableName, $primaryKey, $value)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $sql = "DELETE FROM `{$tableName}` WHERE `{$primaryKey}` = :value";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':value' => $value]);
        } catch (PDOException $e) {
            error_log("Error deleting row: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Execute custom SQL query
     */
    public static function executeQuery($sql)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            // Check if it's a SELECT query
            if (stripos(trim($sql), 'SELECT') === 0) {
                $stmt = $db->query($sql);
                return [
                    'success' => true,
                    'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                    'rows_affected' => $stmt->rowCount()
                ];
            } else {
                return [
                    'success' => true,
                    'rows_affected' => $stmt->rowCount()
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Search in table
     */
    public static function searchTable($tableName, $searchTerm, $page = 1, $perPage = 50)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $structure = self::getTableStructure($tableName);
            $offset = ($page - 1) * $perPage;
            
            // Build WHERE clause for all columns
            $whereParts = [];
            foreach ($structure as $column) {
                $whereParts[] = "`{$column['Field']}` LIKE :search";
            }
            
            $whereClause = implode(' OR ', $whereParts);
            
            $sql = "SELECT * FROM `{$tableName}` WHERE {$whereClause} LIMIT {$perPage} OFFSET {$offset}";
            $stmt = $db->prepare($sql);
            $stmt->execute([':search' => "%{$searchTerm}%"]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching table: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get database statistics
     */
    public static function getDatabaseStats()
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $dbName = $_ENV['DB_NAME'] ?? 'database';
            
            $sql = "SELECT 
                    COUNT(*) as table_count,
                    SUM(table_rows) as total_rows,
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as total_size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = :dbname";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':dbname' => $dbName]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting database stats: " . $e->getMessage());
            return [
                'table_count' => 0,
                'total_rows' => 0,
                'total_size_mb' => 0
            ];
        }
    }
    
    /**
     * Truncate table
     */
    public static function truncateTable($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $sql = "TRUNCATE TABLE `{$tableName}`";
            $db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error truncating table: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Drop table
     */
    public static function dropTable($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            $sql = "DROP TABLE `{$tableName}`";
            $db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error dropping table: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Export table as SQL
     */
    public static function exportTableSQL($tableName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            // Get CREATE TABLE statement
            $stmt = $db->query("SHOW CREATE TABLE `{$tableName}`");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
            $sql = $createTable['Create Table'] . ";\n\n";
            
            // Get all data
            $stmt = $db->query("SELECT * FROM `{$tableName}`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $values = array_map(function($value) use ($db) {
                        return $value === null ? 'NULL' : $db->quote($value);
                    }, array_values($row));
                    
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
            }
            
            return $sql;
        } catch (PDOException $e) {
            error_log("Error exporting table: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add a new column to a table
     */
    public static function addColumn($tableName, $columnName, $dataType, $length = null, $nullable = false, $defaultValue = null, $after = null)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            // Build column definition
            $columnDef = "`{$columnName}` {$dataType}";
            
            // Add length if provided
            if ($length !== null && $length !== '') {
                $columnDef .= "({$length})";
            }
            
            // Add NULL/NOT NULL
            $columnDef .= $nullable ? ' NULL' : ' NOT NULL';
            
            // Add default value if provided
            if ($defaultValue !== null && $defaultValue !== '') {
                if (strtoupper($defaultValue) === 'NULL') {
                    $columnDef .= ' DEFAULT NULL';
                } elseif (strtoupper($defaultValue) === 'CURRENT_TIMESTAMP') {
                    $columnDef .= ' DEFAULT CURRENT_TIMESTAMP';
                } else {
                    $columnDef .= " DEFAULT " . $db->quote($defaultValue);
                }
            }
            
            // Build ALTER TABLE query
            $sql = "ALTER TABLE `{$tableName}` ADD COLUMN {$columnDef}";
            
            // Add AFTER clause if specified
            if ($after !== null && $after !== '') {
                $sql .= " AFTER `{$after}`";
            }
            
            $db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error adding column: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get available MySQL data types
     */
    public static function getDataTypes()
    {
        return [
            'Numeric' => [
                'TINYINT' => 'TINYINT - Very small integer (-128 to 127)',
                'SMALLINT' => 'SMALLINT - Small integer (-32768 to 32767)',
                'MEDIUMINT' => 'MEDIUMINT - Medium integer (-8388608 to 8388607)',
                'INT' => 'INT - Standard integer (-2147483648 to 2147483647)',
                'BIGINT' => 'BIGINT - Large integer',
                'DECIMAL' => 'DECIMAL - Fixed-point number (exact)',
                'FLOAT' => 'FLOAT - Floating-point number',
                'DOUBLE' => 'DOUBLE - Double-precision floating-point',
            ],
            'String' => [
                'CHAR' => 'CHAR - Fixed-length string (0-255)',
                'VARCHAR' => 'VARCHAR - Variable-length string (0-65535)',
                'TINYTEXT' => 'TINYTEXT - Very small text (255 chars)',
                'TEXT' => 'TEXT - Standard text (65535 chars)',
                'MEDIUMTEXT' => 'MEDIUMTEXT - Medium text (16MB)',
                'LONGTEXT' => 'LONGTEXT - Large text (4GB)',
            ],
            'Binary' => [
                'BINARY' => 'BINARY - Fixed-length binary',
                'VARBINARY' => 'VARBINARY - Variable-length binary',
                'TINYBLOB' => 'TINYBLOB - Very small BLOB',
                'BLOB' => 'BLOB - Standard BLOB',
                'MEDIUMBLOB' => 'MEDIUMBLOB - Medium BLOB',
                'LONGBLOB' => 'LONGBLOB - Large BLOB',
            ],
            'Date/Time' => [
                'DATE' => 'DATE - Date (YYYY-MM-DD)',
                'TIME' => 'TIME - Time (HH:MM:SS)',
                'DATETIME' => 'DATETIME - Date and time',
                'TIMESTAMP' => 'TIMESTAMP - Timestamp',
                'YEAR' => 'YEAR - Year (YYYY)',
            ],
            'Other' => [
                'ENUM' => 'ENUM - Enumeration (list of values)',
                'SET' => 'SET - Set of values',
                'JSON' => 'JSON - JSON data',
                'BOOLEAN' => 'BOOLEAN - Boolean (0 or 1)',
            ],
        ];
    }
    
    /**
     * Modify an existing column
     */
    public static function modifyColumn($tableName, $oldColumnName, $newColumnName, $dataType, $length = null, $nullable = false, $defaultValue = null)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            // Build column definition
            $columnDef = "`{$newColumnName}` {$dataType}";
            
            // Add length if provided
            if ($length !== null && $length !== '') {
                $columnDef .= "({$length})";
            }
            
            // Add NULL/NOT NULL
            $columnDef .= $nullable ? ' NULL' : ' NOT NULL';
            
            // Add default value if provided
            if ($defaultValue !== null && $defaultValue !== '') {
                if (strtoupper($defaultValue) === 'NULL') {
                    $columnDef .= ' DEFAULT NULL';
                } elseif (strtoupper($defaultValue) === 'CURRENT_TIMESTAMP') {
                    $columnDef .= ' DEFAULT CURRENT_TIMESTAMP';
                } else {
                    $columnDef .= " DEFAULT " . $db->quote($defaultValue);
                }
            }
            
            // Build ALTER TABLE query
            $sql = "ALTER TABLE `{$tableName}` CHANGE COLUMN `{$oldColumnName}` {$columnDef}";
            
            $db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error modifying column: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Drop a column from a table
     */
    public static function dropColumn($tableName, $columnName)
    {
        try {
            $instance = new static();
            $db = $instance->getConnection();
            
            $sql = "ALTER TABLE `{$tableName}` DROP COLUMN `{$columnName}`";
            $db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error dropping column: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get column details
     */
    public static function getColumnDetails($tableName, $columnName)
    {
        try {
            $structure = self::getTableStructure($tableName);
            foreach ($structure as $column) {
                if ($column['Field'] === $columnName) {
                    return $column;
                }
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error getting column details: " . $e->getMessage());
            return null;
        }
    }
}
