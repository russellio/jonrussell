# Database Access for Cursor AI

This document explains how to configure Cursor to access your local SQLite database.

## Option 1: Using the Artisan Command (Recommended - Simplest)

The `db:sql` artisan command is already set up and ready to use. Cursor can execute it directly.

### Usage Examples

When you need to query the database, you can tell Cursor:

```
"Run this SQL query: SELECT * FROM users LIMIT 5"
```

Or be more explicit:
```
"Use the artisan command 'php artisan db:sql' to run: SELECT * FROM companies"
```

### Command Features

- **Safe by default**: Only allows SELECT, WITH, PRAGMA, and EXPLAIN queries
- **Unsafe mode**: Use `--unsafe` flag for UPDATE/DELETE/INSERT operations
- **JSON output**: Use `--json` flag for structured output
- **Limit protection**: Automatically limits SELECT queries to 200 rows (configurable with `--limit`)

### Example Commands

```bash
# Simple query
php artisan db:sql "SELECT * FROM companies LIMIT 10"

# JSON output
php artisan db:sql "SELECT * FROM skills" --json

# With connection name
php artisan db:sql "SELECT * FROM positions" --connection=sqlite

# Unsafe operation (UPDATE/DELETE/INSERT)
php artisan db:sql "UPDATE users SET name='Test'" --unsafe
```

### Database Location

Your SQLite database is located at:
```
database/database.sqlite
```

Or the absolute path:
```
/Users/jonrussell/Projects/_sites/jonrussell/database/database.sqlite
```

---

## Option 2: MCP Server Setup (Advanced - More Integrated)

For a more integrated experience, you can set up an MCP server that Cursor can connect to directly.

### Option 2A: Standalone SQLite MCP Server (Simplest MCP Option)

1. **Install a SQLite MCP Server**

   There are community MCP servers available. One option is to use a Node.js-based SQLite MCP server:

   ```bash
   npm install -g @modelcontextprotocol/server-sqlite
   ```

2. **Configure in Cursor**

   In Cursor, go to:
   - Settings → Features → MCP Servers
   - Add a new MCP server with this configuration:

   ```json
   {
     "mcpServers": {
       "sqlite": {
         "command": "npx",
         "args": [
           "-y",
           "@modelcontextprotocol/server-sqlite",
           "/Users/jonrussell/Projects/_sites/jonrussell/database/database.sqlite"
         ]
       }
     }
   }
   ```

   Or if you have the package installed globally:
   ```json
   {
     "mcpServers": {
       "sqlite": {
         "command": "mcp-server-sqlite",
         "args": [
           "/Users/jonrussell/Projects/_sites/jonrussell/database/database.sqlite"
         ]
       }
     }
   }
   ```

### Option 2B: Laravel MCP Server (If Available)

**Note**: Verify that `laravel/mcp` package exists before proceeding.

1. **Install Laravel MCP Package**

   ```bash
   composer require laravel/mcp
   ```

2. **Publish MCP Routes**

   ```bash
   php artisan vendor:publish --tag=ai-routes
   ```

3. **Create Database MCP Server**

   ```bash
   php artisan make:mcp-server DatabaseServer
   ```

4. **Create SQL Query Tool**

   ```bash
   php artisan make:mcp-tool QueryDatabaseTool
   ```

5. **Implement the Tool**

   Edit `app/Mcp/Tools/QueryDatabaseTool.php` to use your existing `DbSql` command logic.

6. **Register in routes/ai.php**

   ```php
   use App\Mcp\Servers\DatabaseServer;
   use Laravel\Mcp\Facades\Mcp;

   Mcp::web('/mcp/database', DatabaseServer::class);
   ```

7. **Configure Cursor**

   Point Cursor to your Laravel MCP endpoint (requires Laravel server to be running).

---

## Recommendation

**Use Option 1 (Artisan Command)** because:
- ✅ Already set up and working
- ✅ No additional dependencies
- ✅ Works immediately
- ✅ Safe by default
- ✅ Cursor can execute terminal commands directly

The MCP server approach (Option 2) is only beneficial if you want:
- More structured integration
- Ability to browse schema/resources
- Better type hints and autocomplete

For most use cases, the artisan command is sufficient and simpler.

---

## Quick Reference

**Database Path**: `/Users/jonrussell/Projects/_sites/jonrussell/database/database.sqlite`

**Command**: `php artisan db:sql "YOUR_SQL_HERE"`

**Safe Queries**: SELECT, WITH, PRAGMA, EXPLAIN (default)

**Unsafe Queries**: UPDATE, DELETE, INSERT (requires `--unsafe` flag)

