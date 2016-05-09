# ACE Research Library

## Development Install

It should be sufficient to just run:

    sudo aptitude install python3-dev libpq-dev g++
    python3.4 bootstrap.py -c development.cfg
    bin/buildout -c development.cfg

To setup the database:

    sudo aptitude install postgresql postgresql-contrib
    sudo -u postgres psql -f db_create.sql
    bin/django migrate

## Staging/Production Deployment

Forthcoming.

## Starting the Server

    bin/circusd circus.conf --daemon

## Tests

    bin/django test researchlibrary

To populate the database with a set of testing-data:

    sudo -u postgres psql -d rlibdb -f db_populate.sql

If the database is setup correctly, a list of resources should be visible in the django admin interface (/admin) aswell as the /list and /authors views.

## Documentation

To build:

    cd docs/ && make html

To view, open `/docs/html/index.html` in a browser.
