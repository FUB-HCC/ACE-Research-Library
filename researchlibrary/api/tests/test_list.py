import datetime
from django.test import TestCase
from django.core.management import call_command
from ..models import Person, Resource


class ListTests(TestCase):
    """
    Basic tests for the list endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """
    endpoint_url = '/api/v1/list/'

    @classmethod
    def setUpTestData(cls):
        author = Person.objects.create(name='Mock Author')
        resource = Resource.objects.create(title='Mock Title', published=datetime.date.today())
        resource.authors.add(author)

    @classmethod
    def tearDownClass(cls):
        super().tearDownClass()
        call_command('clear_index', interactive=False)

    def test_content_type(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)

    def test_count(self):
        response = self.client.get(self.endpoint_url)
        self.assertIsInstance(response.json()['count'], int)
        self.assertTrue(response.json()['count'])  # Greater than zero

    def test_results(self):
        response = self.client.get(self.endpoint_url)
        self.assertIsInstance(response.json()['results'], list)

    def test_authors(self):
        response = self.client.get(self.endpoint_url)
        self.assertIsInstance(response.json()['results'][0]['authors'], list)

    def test_title(self):
        response = self.client.get(self.endpoint_url)
        self.assertIsInstance(response.json()['results'][0]['title'], str)
