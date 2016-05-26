import datetime
import os
from django.test import TestCase, Client
from django.conf import settings
from django.core.management import call_command
from ..models import Author, Resource


settings.HAYSTACK_CONNECTIONS['default']['PATH'] = \
    os.path.join(settings.BASE_DIR, '..', 'test_whoosh_index')


class SearchTests(TestCase):
    """
    Basic tests for the list endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """

    @classmethod
    def setUpTestData(cls):
        cls.client = Client()
        author = Author(name='Mock Author')
        author.save()
        Resource.objects.bulk_create([
            Resource(title='Mock Turtle', date=datetime.date.today()),
            Resource(title='Mock Chicken', date=datetime.date.today()),
            Resource(title='Mock Cow', date=datetime.date.today()),
            Resource(title='Mock Pig', date=datetime.date.today()),
            Resource(title='Mock Piglet', date=datetime.date.today()),
            Resource(title='Mock Turkey', date=datetime.date.today()),
        ])
        author.resource_set.add(*Resource.objects.all())
        call_command('rebuild_index', interactive=False)


    def test_content_type(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)

    def test_mock_count(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertIsInstance(response.json()['count'], int)
        self.assertTrue(response.json()['count'])  # Greater than zero

    def test_pig_count(self):
        response = self.client.get('/api/search/?q=pig')
        self.assertIsInstance(response.json()['count'], int)
        self.assertEqual(response.json()['count'], 1)

    def test_results(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertIsInstance(response.json()['results'], list)

    def test_date(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertTrue(response.json()['results'][0]['date'])

    def test_text(self):
        response = self.client.get('/api/search/?q=mock')
        self.assertIsInstance(response.json()['results'][0]['text'], str)
