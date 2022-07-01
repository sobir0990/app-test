#!/bin/bash
set -e
$POSTGRES <<EOSQL
su -p postgres psql
CREATE DATABASE test_database_db
CREATE USER test_user_db WITH password 'test_password_db'
GRANT ALL ON DATABASE test_database_db TO test_user_db
EOSQL