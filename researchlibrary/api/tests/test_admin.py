import datetime
import os
from django.contrib.auth.models import User
from django.test import TestCase
from django.conf import settings
from ..models import Person, Resource, Keyword


settings.HAYSTACK_CONNECTIONS['default']['PATH'] = \
    os.path.join(settings.BASE_DIR, '..', 'test_whoosh_index')


class SearchTests(TestCase):
    """
    Basic tests for the list endpoint.

    # TODO: Extend according to https://trello.com/c/fAUsohvO/2-interface-prototype
    """
    endpoint_url = '/admin/'

    @classmethod
    def setUpTestData(cls):
        cls.user = User.objects.create_user(
            username='test', password='test', is_superuser=True, is_staff=True)
        cls.author = Person.objects.create(name='Mock Author')
        Resource.objects.bulk_create([
            Resource(title='Mock Turtle', published=datetime.date.today()),
            Resource(title='Mock Chicken', published=datetime.date.today()),
            Resource(title='Mock Cow', published=datetime.date.today()),
            Resource(title='Mock Pig', published=datetime.date.today()),
            Resource(title='Mock Piglet', published=datetime.date.today()),
            Resource(title='Mock Turkey', published=datetime.date.today()),
        ])
        cls.author.resources_authored.add(*Resource.objects.all())

    def setUp(self):
        self.client.force_login(self.user)

    def test_admin_home(self):
        response = self.client.get(self.endpoint_url)
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Resources')

    def test_resource_list(self):
        response = self.client.get(self.endpoint_url + 'api/resource/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select resource to change')

    def test_person_list(self):
        response = self.client.get(self.endpoint_url + 'api/person/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_keyword_list(self):
        response = self.client.get(self.endpoint_url + 'api/keyword/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select keyword to change')

    def test_category_list(self):
        response = self.client.get(self.endpoint_url + 'api/category/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select category to change')

    def test_resource_search(self):
        response = self.client.get(self.endpoint_url + 'api/resource/?q=test')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select resource to change')

    def test_person_search(self):
        response = self.client.get(self.endpoint_url + 'api/person/?q=test')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_keyword_search(self):
        response = self.client.get(self.endpoint_url + 'api/keyword/?q=test')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select keyword to change')

    def test_category_search(self):
        response = self.client.get(self.endpoint_url + 'api/category/?q=test')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select category to change')

    def test_person_usage_count_as_author_filter_low(self):
        response = self.client.get(self.endpoint_url + 'api/person/?usage_count_as_author=0')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_person_usage_count_as_editor_filter_low(self):
        response = self.client.get(self.endpoint_url + 'api/person/?usage_count_as_editor=0')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_person_usage_count_as_author_filter_high(self):
        response = self.client.get(self.endpoint_url + 'api/person/?usage_count_as_author=10')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_person_usage_count_as_editor_filter_high(self):
        response = self.client.get(self.endpoint_url + 'api/person/?usage_count_as_editor=10')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select person to change')

    def test_keyword_usage_count_filter_low(self):
        response = self.client.get(self.endpoint_url + 'api/keyword/?usage_count=0')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select keyword to change')

    def test_keyword_usage_count_filter_high(self):
        response = self.client.get(self.endpoint_url + 'api/keyword/?usage_count=10')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select keyword to change')

    def test_category_usage_count_filter_low(self):
        response = self.client.get(self.endpoint_url + 'api/category/?usage_count=0')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select category to change')

    def test_category_usage_count_filter_high(self):
        response = self.client.get(self.endpoint_url + 'api/category/?usage_count=0')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Select category to change')

    def test_resource_add(self):
        response = self.client.get(self.endpoint_url + 'api/resource/add/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Add resource')

    def test_person_add(self):
        response = self.client.get(self.endpoint_url + 'api/person/add/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Add person')

    def test_keyword_add(self):
        response = self.client.get(self.endpoint_url + 'api/keyword/add/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Add keyword')

    def test_category_add(self):
        response = self.client.get(self.endpoint_url + 'api/category/add/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Add category')

    def test_resource_add_url(self):
        response = self.client.get(self.endpoint_url + 'api/resource/add_url/')
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'Add resource')

    def test_resource_add_url_first_post(self):
        response = self.client.post(
            self.endpoint_url + 'api/resource/add_url/',
            {'url': 'http://claviger.net/attribution-moloch.html'})
        self.assertEqual(response.status_code, 200, response)
        self.assertContains(response, 'The Attribution Moloch')
        self.assertContains(response, 'http://claviger.net/attribution-moloch.html')
        self.assertContains(response, 'action="/admin/api/resource/add/"')
        self.assertContains(response, 'Many thanks to Lukas Gloor, Melanie Joy, Sara Nowak')

    def test_resource_add_url_second_post(self):
        response = self.client.post(
            self.endpoint_url + 'api/resource/add/',
            {'title': 'The Attribution Moloch',
             'authors': [self.author.pk],
             'published': '2016-05-23',
             'url': 'http://claviger.net/attribution-moloch.html'})
        self.assertEqual(response.status_code, 302, response)
        self.assertEqual(response.url, '/admin/api/resource/', response)

    def test_create_keyword_hack(self):
        response = self.client.post(
            self.endpoint_url + 'api/resource/create_keyword/',
            {'name': 'attribution'})
        self.assertEqual(response.status_code, 200, response)
        self.assertTrue(response.json(), response)
        self.assertEqual(response.json()['text'], 'attribution', response)
        self.assertTrue(Keyword.objects.get(pk=response.json()['id']), response)

    def test_create_person_hack(self):
        response = self.client.post(
            self.endpoint_url + 'api/resource/create_person/',
            {'name': 'Mock Author 2'})
        self.assertEqual(response.status_code, 200, response)
        self.assertTrue(response.json(), response)
        self.assertEqual(response.json()['text'], 'Mock Author 2', response)
        self.assertTrue(Person.objects.get(pk=response.json()['id']), response)
