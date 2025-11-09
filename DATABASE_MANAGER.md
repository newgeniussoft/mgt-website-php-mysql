# Database Manager - phpMyAdmin Alternative

A comprehensive database management system built into the admin panel, providing full control over your database tables, columns, and data - similar to phpMyAdmin but integrated directly into your CMS.

## Features

### 📊 Database Overview
- **Dashboard Statistics**: View total tables, rows, and database size
- **Table Listing**: See all database tables with row counts, sizes, and storage engines
- **Quick Actions**: Access common operations from the main dashboard

### 🔍 Table Management
- **View Table Structure**: See all columns, data types, keys, and constraints
- **Browse Data**: Paginated data viewing with customizable rows per page
- **Search**: Search across all columns in a table
- **Sort**: Order data by any column (ascending/descending)
- **Filter**: Advanced filtering options

### ✏️ Data Manipulation
- **Add Rows**: Insert new records with intelligent form fields based on column types
- **Edit Rows**: Update existing records with type-aware input fields
- **Delete Rows**: Remove records with confirmation dialogs
- **Bulk Operations**: Perform actions on multiple rows

### 🛠️ Advanced Features
- **SQL Query Editor**: Execute custom SQL queries with syntax highlighting
- **Export Tables**: Download table structure and data as SQL files
- **Truncate Tables**: Empty tables while keeping structure
- **Drop Tables**: Delete tables completely (with double confirmation)
- **Primary Key Detection**: Automatic identification and handling of primary keys
- **Auto-increment Fields**: Smart handling of auto-generated fields

### 🎨 Smart Form Fields
The system automatically generates appropriate input fields based on column types:
- **TEXT/BLOB**: Textarea with multiple rows
- **ENUM**: Dropdown select with available options
- **DATE/DATETIME/TIME**: Date/time pickers
- **INT/DECIMAL/FLOAT**: Number inputs with step controls
- **VARCHAR/CHAR**: Text inputs
- **NULL Support**: Option to set NULL values for nullable fields

## File Structure

```
app/
├── Models/
│   └── Database.php                    # Database operations model
└── Http/
    └── Controllers/
        └── Admin/
            └── DatabaseController.php  # Database management controller

resources/
└── views/
    └── admin/
        └── database/
            ├── index.blade.php         # Database dashboard
            ├── view-table.blade.php    # Table data viewer
            ├── edit-row.blade.php      # Edit row form
            ├── add-row.blade.php       # Add row form
            └── sql-query.blade.php     # SQL query interface

routes/
└── web.php                             # Database routes
```

## Routes

All routes are protected by authentication middleware and prefixed with `/admin/database`:

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/database` | Database dashboard |
| GET | `/database/view-table` | View table data |
| GET | `/database/edit-row` | Edit row form |
| POST | `/database/update-row` | Update row |
| GET | `/database/add-row` | Add row form |
| POST | `/database/insert-row` | Insert new row |
| POST | `/database/delete-row` | Delete row |
| GET | `/database/sql-query` | SQL query interface |
| POST | `/database/sql-query` | Execute SQL query |
| GET | `/database/export-table` | Export table as SQL |
| POST | `/database/truncate-table` | Truncate table |
| POST | `/database/drop-table` | Drop table |

## Usage Guide

### Accessing Database Manager

1. Log in to the admin panel
2. Click on **"Database Manager"** in the sidebar under "System"
3. You'll see the database dashboard with statistics and table list

### Viewing Table Data

1. From the database dashboard, click the **eye icon** next to any table
2. Use the search box to find specific data
3. Sort by clicking column headers
4. Change rows per page using the dropdown
5. Navigate through pages using pagination controls

### Adding a New Row

1. Navigate to the table you want to add data to
2. Click the **"Add Row"** button
3. Fill in the form fields:
   - Auto-increment fields are automatically generated
   - Required fields are marked with a red badge
   - Nullable fields can be left empty for NULL values
4. Click **"Add Row"** to insert the data

### Editing a Row

1. In the table view, click the **edit icon** next to the row
2. Modify the field values:
   - Primary keys and auto-increment fields are read-only
   - Date/time fields use browser date pickers
   - ENUM fields show dropdown options
3. Click **"Update Row"** to save changes

### Deleting a Row

1. In the table view, click the **trash icon** next to the row
2. Confirm the deletion in the popup dialog
3. The row will be permanently deleted

### Running SQL Queries

1. Click **"SQL Query"** button from the database dashboard
2. Enter your SQL query in the editor
3. Use the **Quick Reference** panel for common query templates
4. Click **"Execute Query"** to run the query
5. Results will be displayed below the editor

**Example Queries:**
```sql
-- Select all users
SELECT * FROM users;

-- Find specific record
SELECT * FROM pages WHERE slug = 'home';

-- Update data
UPDATE settings SET value = 'New Value' WHERE key = 'site_name';

-- Count records
SELECT COUNT(*) FROM media WHERE type = 'image';

-- Join tables
SELECT p.*, u.name as author 
FROM pages p 
LEFT JOIN users u ON p.user_id = u.id;
```

### Exporting a Table

1. From the database dashboard, click the **download icon** next to the table
2. The table structure and data will be downloaded as a `.sql` file
3. The file includes:
   - CREATE TABLE statement
   - INSERT statements for all rows

### Truncating a Table

1. Click the **eraser icon** next to the table
2. Confirm the action (this deletes all rows but keeps the table structure)
3. The table will be emptied

### Dropping a Table

1. Click the **trash icon** next to the table
2. Confirm the action twice (this is irreversible)
3. The table and all its data will be permanently deleted

## Security Features

### CSRF Protection
All form submissions are protected with CSRF tokens to prevent cross-site request forgery attacks.

### Authentication Required
All database management routes require admin authentication. Unauthorized users cannot access the database manager.

### Confirmation Dialogs
Destructive operations (delete, truncate, drop) require user confirmation to prevent accidental data loss.

### SQL Injection Prevention
All database queries use prepared statements with parameter binding to prevent SQL injection attacks.

### Input Validation
Form inputs are validated based on column types and constraints before database operations.

## Database Model Methods

The `Database` model provides comprehensive methods for database operations:

### Table Information
- `getAllTables()` - Get all tables with metadata
- `getTableStructure($tableName)` - Get column information
- `getTableRowCount($tableName)` - Count rows in table
- `getTableSize($tableName)` - Get table size in MB
- `getTableEngine($tableName)` - Get storage engine
- `getPrimaryKey($tableName)` - Get primary key columns

### Data Operations
- `getTableData($tableName, $page, $perPage, $orderBy, $orderDir)` - Get paginated data
- `getRow($tableName, $primaryKey, $value)` - Get single row
- `insertRow($tableName, $data)` - Insert new row
- `updateRow($tableName, $primaryKey, $primaryValue, $data)` - Update row
- `deleteRow($tableName, $primaryKey, $value)` - Delete row
- `searchTable($tableName, $searchTerm, $page, $perPage)` - Search in table

### Advanced Operations
- `executeQuery($sql)` - Execute custom SQL
- `exportTableSQL($tableName)` - Export table as SQL
- `truncateTable($tableName)` - Empty table
- `dropTable($tableName)` - Delete table
- `getDatabaseStats()` - Get database statistics

## Best Practices

### 1. Backup Before Changes
Always backup your database before making significant changes, especially when:
- Running UPDATE or DELETE queries without WHERE clauses
- Truncating or dropping tables
- Modifying table structures

### 2. Use WHERE Clauses
When running UPDATE or DELETE queries, always include WHERE clauses to avoid affecting all rows:
```sql
-- Good
UPDATE users SET status = 'active' WHERE id = 5;

-- Bad (affects all rows!)
UPDATE users SET status = 'active';
```

### 3. Test on Development First
Test complex queries on a development database before running them on production.

### 4. Be Careful with Foreign Keys
When deleting rows, be aware of foreign key constraints that might prevent deletion or cascade to related tables.

### 5. Monitor Database Size
Regularly check the database size statistics to ensure optimal performance and plan for scaling.

### 6. Use Transactions for Complex Operations
For operations involving multiple tables, consider using transactions in the SQL Query interface:
```sql
START TRANSACTION;
UPDATE table1 SET column = 'value' WHERE id = 1;
UPDATE table2 SET column = 'value' WHERE id = 1;
COMMIT;
```

## Troubleshooting

### Cannot Edit Row
**Problem**: Edit button is disabled or missing
**Solution**: The table must have a primary key defined. Tables without primary keys cannot be edited through the interface.

### Query Execution Failed
**Problem**: SQL query returns an error
**Solution**: 
- Check SQL syntax
- Verify table and column names
- Ensure you have proper permissions
- Check for foreign key constraints

### Large Tables Load Slowly
**Problem**: Tables with millions of rows are slow to load
**Solution**:
- Use the search function to filter results
- Reduce rows per page
- Use SQL queries with LIMIT clauses
- Add indexes to frequently searched columns

### Export Fails for Large Tables
**Problem**: Export times out or fails for large tables
**Solution**:
- Export in smaller chunks using SQL queries with LIMIT and OFFSET
- Use command-line tools for very large exports
- Increase PHP memory limit and execution time

## Keyboard Shortcuts

When using the SQL Query interface:
- **Ctrl/Cmd + Enter**: Execute query (future enhancement)
- **Ctrl/Cmd + K**: Clear query

## Comparison with phpMyAdmin

| Feature | Database Manager | phpMyAdmin |
|---------|-----------------|------------|
| Integrated in CMS | ✅ Yes | ❌ Separate application |
| Table browsing | ✅ Yes | ✅ Yes |
| Edit data | ✅ Yes | ✅ Yes |
| SQL queries | ✅ Yes | ✅ Yes |
| Export tables | ✅ SQL format | ✅ Multiple formats |
| Import data | ⚠️ Via SQL queries | ✅ Dedicated import |
| User management | ❌ No | ✅ Yes |
| Database creation | ⚠️ Via SQL queries | ✅ Yes |
| Visual query builder | ❌ No | ✅ Yes |
| Responsive design | ✅ Yes | ⚠️ Limited |
| Authentication | ✅ CMS auth | ✅ Separate auth |

## Future Enhancements

Potential features for future versions:
- [ ] Import data from CSV/SQL files
- [ ] Visual query builder
- [ ] Table structure editor
- [ ] Index management
- [ ] Database backup/restore
- [ ] Query history
- [ ] Saved queries/favorites
- [ ] Multi-table operations
- [ ] Data visualization charts
- [ ] Export to multiple formats (CSV, JSON, XML)
- [ ] Keyboard shortcuts
- [ ] Dark mode
- [ ] Query execution plans
- [ ] Database optimization tools

## Support

For issues or questions:
1. Check this documentation
2. Review the code comments in `Database.php` and `DatabaseController.php`
3. Check PHP error logs in `storage/logs/`
4. Verify database connection settings in `.env`

## License

This Database Manager is part of the Madagascar Green Tours CMS and follows the same license as the main application.

---

**⚠️ Important Security Note**: This tool provides direct access to your database. Only grant access to trusted administrators. Always maintain regular backups of your database.
