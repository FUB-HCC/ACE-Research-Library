import json
from django.views.generic.list import ListView
from django.utils import timezone
from django.http import HttpResponse
from django.shortcuts import render
from ..version import __version__

from models import Author, Resource


def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals',}
    return HttpResponse(json.dumps(status, default=str),
                        content_type='application/json')


class AuthorListView(ListView):
	template_name = 'api/author_list.html'
	model = Author

class ResourceListView(ListView):
	template_name = 'api/resource_list.html'
	model = Resource
