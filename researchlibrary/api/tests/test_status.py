from django.test import TestCase
from ...version import __version__


class StatusTests(TestCase):
    endpoint_url = '/api/'

    def test_version(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response.json()['version'], __version__)

    def test_content_type(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response['content-type'], 'application/json')

    def test_status(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response.json()['status'], 200)
        self.assertEqual(response.status_code, 200)
