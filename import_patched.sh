#!/bin/bash

CONTAINER_NAME="db_patched"
DB_USER="root"
DB_PASS="root_password"
DB_NAME="patched_db"
SQL_FILE="patched_website.sql"
# Create database if it doesn't exist
docker exec -i $CONTAINER_NAME mysql -u $DB_USER -p$DB_PASS -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# Import the SQL file
docker exec -i $CONTAINER_NAME mysql -u $DB_USER -p$DB_PASS $DB_NAME < $SQL_FILE

echo "Database imported successfully."
