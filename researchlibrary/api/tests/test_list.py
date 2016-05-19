import datetime
from django.test import TestCase, Client
from ..models import Author, Resource


class ListTests(TestCase):
    """
    Basic tests for the list endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """

    @classmethod
    def setUpTestData(cls):
        cls.client = Client()
        author = Author(name='Mock Author')
        author.save()
        resource = Resource(title='Mock Title', date=datetime.date.today())
        resource.save()
        resource.authors = [author]
        resource.save()

    def test_content_type(self):
        response = self.client.get('/api/list/')
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get('/api/list/')
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)

    def test_count(self):
        response = self.client.get('/api/list/')
        self.assertIsInstance(response.json()['count'], int)
        self.assertTrue(response.json()['count'])  # Greater than zero

    def test_results(self):
        response = self.client.get('/api/list/')
        self.assertIsInstance(response.json()['results'], list)

    def test_authors(self):
        response = self.client.get('/api/list/')
        self.assertIsInstance(response.json()['results'][0]['authors'], list)

    def test_title(self):
        response = self.client.get('/api/list/')
        self.assertIsInstance(response.json()['results'][0]['title'], str)
