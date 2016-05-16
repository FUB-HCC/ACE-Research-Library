from django.test import TestCase, Client
from ...version import __version__


class StatusTests(TestCase):

    @classmethod
    def setUpTestData(cls):
        cls.client = Client()

    def test_version(self):
        response = self.client.get('/')
        self.assertEqual(response.json()['version'], __version__)

    def test_content_type(self):
        response = self.client.get('/')
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get('/')
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)
