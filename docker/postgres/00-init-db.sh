#!/bin/bash
set -e

LATEST_DUMP=$(ls -1t /opt/backup/postgres/*.sql 2>/dev/null | head -n 1)
if [ -n "$LATEST_DUMP" ]; then
	echo "Restoring postgres dump: $LATEST_DUMP"
	psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -c "DROP SCHEMA public CASCADE; CREATE SCHEMA public;"
	psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -f "$LATEST_DUMP"
else
	psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname postgres -f /opt/init-sql/01_bdd.sql
fi
