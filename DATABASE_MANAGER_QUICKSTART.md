# Database Manager - Quick Start Guide

## 🚀 Getting Started

The Database Manager is now integrated into your admin panel and ready to use! No installation required.

## Access the Database Manager

1. **Login to Admin Panel**
   - Go to `http://localhost/admin/login` (or your admin URL)
   - Login with your admin credentials

2. **Navigate to Database Manager**
   - Look for **"Database Manager"** in the left sidebar under the "System" section
   - Click on it to access the database dashboard

## Quick Actions

### View All Tables
- The main dashboard shows all database tables
- Each table displays:
  - Table name
  - Number of rows
  - Size in MB
  - Storage engine (InnoDB, MyISAM, etc.)

### Browse Table Data
1. Click the **eye icon** (👁️) next to any table
2. You'll see all rows with pagination
3. Use the search box to find specific data
4. Sort by clicking on column headers

### Add New Data
1. Navigate to the table
2. Click **"Add Row"** button
3. Fill in the form (auto-increment fields are generated automatically)
4. Click **"Add Row"** to save

### Edit Existing Data
1. In table view, click the **edit icon** (✏️) next to any row
2. Modify the values
3. Click **"Update Row"** to save changes

### Delete Data
1. Click the **trash icon** (🗑️) next to any row
2. Confirm the deletion
3. The row will be permanently removed

### Run SQL Queries
1. Click **"SQL Query"** button from the dashboard
2. Type your SQL query
3. Click **"Execute Query"**
4. Results appear below the editor

**Example queries:**
```sql
-- View all users
SELECT * FROM users;

-- Find a specific page
SELECT * FROM pages WHERE slug = 'home';

-- Count media files
SELECT COUNT(*) FROM media WHERE type = 'image';
```

### Export a Table
1. Click the **download icon** (⬇️) next to the table
2. A `.sql` file will be downloaded with the table structure and data

## Common Use Cases

### 1. Check User Data
```
Dashboard → Click eye icon on 'users' table → Browse user records
```

### 2. Update a Setting
```
Dashboard → Click eye icon on 'settings' table → 
Click edit icon on the setting → Change value → Update Row
```

### 3. View Media Files
```
Dashboard → Click eye icon on 'media' table → 
Use search to find specific files
```

### 4. Clean Up Old Data
```
Dashboard → Click eye icon on table → 
Click trash icon on unwanted rows → Confirm deletion
```

### 5. Backup a Table
```
Dashboard → Click download icon on table → 
Save the .sql file to your computer
```

## Safety Tips

⚠️ **Important Warnings:**

1. **Always backup before major changes**
   - Export tables before truncating or dropping
   - Test queries on development first

2. **Be careful with DELETE and UPDATE**
   - Always use WHERE clauses
   - Double-check which rows will be affected

3. **Destructive operations are permanent**
   - Truncate removes all data
   - Drop deletes the entire table
   - These cannot be undone!

4. **Primary keys cannot be edited**
   - Auto-increment fields are read-only
   - This prevents data corruption

## Features at a Glance

✅ **View all database tables**
✅ **Browse, search, and sort data**
✅ **Add, edit, and delete rows**
✅ **Execute custom SQL queries**
✅ **Export tables as SQL files**
✅ **View table structure and columns**
✅ **Smart form fields based on data types**
✅ **Pagination for large datasets**
✅ **CSRF protection and authentication**
✅ **Confirmation dialogs for destructive actions**

## Keyboard Tips

- Use **Tab** to navigate between form fields
- **Enter** submits forms
- **Esc** can close some dialogs (browser dependent)

## Need Help?

- 📖 See the full documentation in `DATABASE_MANAGER.md`
- 🔍 Check table structure before editing
- 💾 Always backup important data
- ⚠️ Test queries on development first

## What's Next?

Now that you have the Database Manager set up, you can:

1. **Explore your database structure**
   - See what tables exist
   - Understand the relationships
   - Check data types and constraints

2. **Manage your data**
   - Update settings
   - Clean up old records
   - Add test data

3. **Run maintenance queries**
   - Optimize tables
   - Check for orphaned records
   - Generate reports

4. **Export backups**
   - Regular table exports
   - Before major updates
   - For migration purposes

---

**Happy Database Managing! 🎉**

For advanced features and detailed documentation, see `DATABASE_MANAGER.md`
