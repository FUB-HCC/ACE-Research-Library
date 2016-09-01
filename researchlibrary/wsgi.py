"""
WSGI config for researchlibrary project.

It exposes the WSGI callable as a module-level variable named ``application``.

For more information on this file, see
https://docs.djangoproject.com/en/1.9/howto/deployment/wsgi/
"""

import os
import sys
import gunicorn.app.base
import daemonocle
from gunicorn.six import iteritems
from django.conf import settings
from django.core.wsgi import get_wsgi_application

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "researchlibrary.settings")

application = get_wsgi_application()


class StandaloneApplication(gunicorn.app.base.BaseApplication):

    def __init__(self, app, options=None):
        self.options = options or {}
        self.application = app
        super(StandaloneApplication, self).__init__()

    def load_config(self):
        config = dict([(key, value) for key, value in iteritems(self.options)
                       if key in self.cfg.settings and value is not None])
        for key, value in iteritems(config):
            self.cfg.set(key.lower(), value)

    def load(self):
        return self.application


def run():
    daemon = daemonocle.Daemon(
        worker=StandaloneApplication(application, settings.GUNICORN_SETTINGS).run,
        workdir='.',
        pidfile='daemonocle.pid')
    daemon.do_action(sys.argv[1])
