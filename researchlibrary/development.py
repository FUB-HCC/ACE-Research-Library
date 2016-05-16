# -*- encoding: utf-8 -*-
from __future__ import (absolute_import, division,
                        print_function, unicode_literals)
from .settings import *  # NOQA

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = True

# INSTALLED_APPS += ('django_extensions',)
# INSTALLED_APPS += ('debug_toolbar', 'debug_panel')
# MIDDLEWARE_CLASSES += ('debug_panel.middleware.DebugPanelMiddleware',)

RESULTS_CACHE_SIZE = 100

CACHE_MIDDLEWARE_SECONDS = 60 * 60 * 24 * 30  # one month

CACHES = {
    'default': {
        'BACKEND': 'django.core.cache.backends.dummy.DummyCache',
    },
    # This cache backend will be used by django-debug-panel
    'debug-panel': {
        'BACKEND': 'django.core.cache.backends.filebased.FileBasedCache',
        'LOCATION': '/tmp/debug-panel-cache',
        'OPTIONS': {
            'MAX_ENTRIES': 2000
        }
    }
}


try:
    from .settings_override import *  # NOQA
except ImportError:
    pass
