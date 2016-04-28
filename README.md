# ACE Research Library

## Development Install

It should be sufficient to just run:

    sudo aptitude install python3-dev libpq-dev g++
    python3.4 bootstrap.py -c development.cfg
    bin/buildout -c development.cfg

## Staging/Production Deployment

Forthcoming.

## Starting the Server

    bin/circusd circus.conf --daemon

## Tests

    bin/django test researchlibrary

## Documentation

To build:

    cd docs/ && make html

To view, open `/docs/html/index.html` in a browser.
