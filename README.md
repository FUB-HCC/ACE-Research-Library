[![Build Status](https://travis-ci.org/FUB-HCC/ACE-Research-Library.svg?branch=master)](https://travis-ci.org/FUB-HCC/ACE-Research-Library)
[![Coverage Status](https://coveralls.io/repos/github/FUB-HCC/ACE-Research-Library/badge.svg?branch=master)](https://coveralls.io/github/FUB-HCC/ACE-Research-Library?branch=master)

# ACE Research Library

## Development Install

It should be sufficient to just run:

    sudo aptitude install python3-dev libpq-dev g++ libxml2-dev libxslt1-dev
    python3.4 bootstrap.py -c development.cfg
    bin/buildout -c development.cfg

To collect the default static files(.css, .js, ...)

    bin/django collectstatic

To setup the database:

    sudo aptitude install postgresql postgresql-contrib
    sudo -u postgres psql -f db_create.sql
    bin/django migrate

Please install [pylama, pyflakes, and pep8](https://pylama.readthedocs.io/) on your computer to make sure your code is well readable:

    pylama -l pep8,pyflakes researchlibrary

## Staging/Production Deployment

Forthcoming.

## Starting the Server

This project uses [daemonocle](https://pypi.python.org/pypi/daemonocle). Start the server with:

    bin/server start

## Tests

    bin/django test researchlibrary

## Documentation

To build:

    cd docs/ && make html

To view, open `/docs/html/index.html` in a browser.
