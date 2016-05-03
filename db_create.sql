CREATE DATABASE rlibdb;
CREATE USER rlibuser WITH PASSWORD 'password';
ALTER ROLE rlibuser SET client_encoding TO 'utf8';
ALTER ROLE rlibuser SET default_transaction_isolation TO 'read committed';
ALTER ROLE rlibuser SET timezone TO 'UTC+1';
GRANT ALL PRIVILEGES ON DATABASE rlibdb TO rlibuser;
