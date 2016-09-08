import datetime
import os
from django.test import TestCase
from django.conf import settings
from django.core.management import call_command
from ..models import Person, Resource


settings.HAYSTACK_CONNECTIONS['default']['PATH'] = \
    os.path.join(settings.BASE_DIR, '..', 'test_whoosh_index')


class SuggestTests(TestCase):
    """
    Basic tests for the suggest endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """
    endpoint_url = '/api/v1/suggest/'
    maxDiff = None

    @classmethod
    def setUpTestData(cls):
        author = Person.objects.create(name='Mock Author')
        Resource.objects.create(title='Mock Turtle', published=datetime.date.today())
        author.resources_authored.add(*Resource.objects.all())

    @classmethod
    def tearDownClass(cls):
        super().tearDownClass()
        call_command('clear_index', interactive=False)

    def test_content_type(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertEqual(response.status_code, 200)

    def test_results(self):
        response = self.client.get(self.endpoint_url + '?q=mo')
        self.assertIsInstance(response.json()['results'], list)

    def test_autocomplete(self):
        response = self.client.get(self.endpoint_url + '?q=mo')
        self.assertEqual(response.json()['count'], 2)

    def test_field(self):
        response = self.client.get(self.endpoint_url + '?q=turt')
        self.assertEqual(response.json()['results'][0]['field'], 'title')
        response = self.client.get(self.endpoint_url + '?q=auth')
        self.assertEqual(response.json()['results'][0]['field'], 'author')
