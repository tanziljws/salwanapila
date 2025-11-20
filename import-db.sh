#!/bin/bash

# Script untuk import SQL file ke Railway database
# Usage: ./import-db.sh [sql-file]

set -e

# Database credentials
DB_HOST="interchange.proxy.rlwy.net"
DB_PORT="21355"
DB_USER="root"
DB_PASS="dRnoMwEJjgKoaKOIeOdLFljYriEByjTs"
DB_NAME="railway"

# Get SQL file from argument or use default
SQL_FILE="${1:-galeri-sekolahsal (1) (1).sql}"

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "❌ Error: File SQL tidak ditemukan: $SQL_FILE"
    echo ""
    echo "Usage: ./import-db.sh [sql-file]"
    echo "Example: ./import-db.sh galeri-sekolahsal.sql"
    exit 1
fi

echo "📥 Importing SQL file: $SQL_FILE"
echo "📊 Database: $DB_NAME"
echo "🔗 Host: $DB_HOST:$DB_PORT"
echo ""

# Check if mysql client is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ Error: MySQL client tidak ditemukan!"
    echo ""
    echo "Install MySQL client:"
    echo "  macOS: brew install mysql-client"
    echo "  Linux: sudo apt-get install mysql-client"
    echo "  Windows: Download dari mysql.com"
    exit 1
fi

# Test connection first
echo "🔍 Testing database connection..."
if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP -e "SELECT 1;" "$DB_NAME" &> /dev/null; then
    echo "✅ Database connection successful!"
else
    echo "❌ Error: Cannot connect to database!"
    echo "Please check:"
    echo "  - Database service is running in Railway"
    echo "  - Host, port, username, and password are correct"
    exit 1
fi

echo ""
echo "📥 Importing SQL file..."
echo "⚠️  This will overwrite existing data in the database!"
read -p "Continue? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Import cancelled."
    exit 1
fi

# Import SQL file
if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP "$DB_NAME" < "$SQL_FILE"; then
    echo ""
    echo "✅ SQL file imported successfully!"
    echo ""
    echo "📊 Verifying import..."
    
    # Check if tables exist
    TABLE_COUNT=$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP -sN -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME';" "$DB_NAME" 2>/dev/null || echo "0")
    
    if [ "$TABLE_COUNT" -gt 0 ]; then
        echo "✅ Found $TABLE_COUNT tables in database"
        
        # Check admin count
        ADMIN_COUNT=$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP -sN -e "SELECT COUNT(*) FROM admins;" "$DB_NAME" 2>/dev/null || echo "0")
        echo "✅ Found $ADMIN_COUNT admin(s) in database"
        
        # Check users count
        USER_COUNT=$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP -sN -e "SELECT COUNT(*) FROM users;" "$DB_NAME" 2>/dev/null || echo "0")
        echo "✅ Found $USER_COUNT user(s) in database"
    else
        echo "⚠️  Warning: No tables found. Import may have failed."
    fi
    
    echo ""
    echo "🎉 Import completed!"
    echo ""
    echo "Next steps:"
    echo "1. Set environment variables in Railway App service"
    echo "2. Restart App service"
    echo "3. Test connection: https://your-app.railway.app/test-db"
else
    echo ""
    echo "❌ Error: Failed to import SQL file!"
    echo "Please check the error message above."
    exit 1
fi

