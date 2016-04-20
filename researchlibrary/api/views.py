import json
from django.http import HttpResponse
from django.shortcuts import render
from ..version import __version__


def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals',}
    return HttpResponse(json.dumps(status, default=str),
                        content_type='application/json')
