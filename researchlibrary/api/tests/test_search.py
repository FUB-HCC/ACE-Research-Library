import datetime
import os
from django.test import TestCase
from django.conf import settings
from ..models import Person, Resource


settings.HAYSTACK_CONNECTIONS['default']['PATH'] = \
    os.path.join(settings.BASE_DIR, '..', 'test_whoosh_index')


class SearchTests(TestCase):
    """
    Basic tests for the list endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """
    endpoint_url = '/api/v1/search/'

    @classmethod
    def setUpTestData(cls):
        author = Person.objects.create(name='Mock Author')
        Resource.objects.bulk_create([
            Resource(title='Mock Turtle', published=datetime.date.today()),
            Resource(title='Mock Chicken', published=datetime.date.today()),
            Resource(title='Mock Cow', published=datetime.date.today()),
            Resource(title='Mock Pig', published=datetime.date.today()),
            Resource(title='Mock Piglet', published=datetime.date.today()),
            Resource(title='Mock Turkey', published=datetime.date.today()),
        ])
        author.resources_authored.add(*Resource.objects.all())

    def test_content_type(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)

    def test_mock_count(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertIsInstance(response.json()['count'], int)
        self.assertTrue(response.json()['count'])  # Greater than zero

    def test_pig_count(self):
        response = self.client.get(self.endpoint_url + '?q=pig')
        self.assertIsInstance(response.json()['count'], int)
        self.assertEqual(response.json()['count'], 1)

    def test_results(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertIsInstance(response.json()['results'], list)

    def test_published(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertTrue(response.json()['results'][0]['published'])

    def test_text(self):
        response = self.client.get(self.endpoint_url + '?q=mock')
        self.assertIsInstance(response.json()['results'][0]['text'], str)
