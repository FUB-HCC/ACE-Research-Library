"""Fulltext import job.

The fulltext import job can be run periodically to
obtain the full text of resources that have only
title or abstract listed. The field `fulltext_url`
is used if present to scrape the text off a website.
This text is never publically displayed and only serves
the purpose of augmenting the search index.
"""

import logging
import requests
from django.core import management
from ...models import Resource
from ...admin import Gist


logger = logging.getLogger(__name__)


class Command(management.base.BaseCommand):
    help = 'Tries to extract content from websites in the database'

    def handle(self, *args, **options):
        for resource in Resource.objects.filter(fulltext__in=(None, '')):
            gist = None
            if resource.fulltext_url:
                logger.info('Trying fulltext at %s', resource.fulltext_url)
                response = requests.get(resource.fulltext_url, streaming=True, timeout=10)
                if response.headers.get('content-type', '').startswith('text/'):
                    gist = Gist(response.text)
            if not gist:
                logger.info('Trying %s', resource.url)
                response = requests.get(resource.url, timeout=10)
                gist = Gist(response.text)
            resource.fulltext = gist.text
            resource.save()
