import json
from django.views.generic.list import ListView
from django.http import HttpResponse
from ..version import __version__
from .models import Author, Resource


def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return HttpResponse(json.dumps(status, default=str),
                        content_type='application/json')



def list(request):
    page = int(request.GET.get('page', 1))
    mlen = 3

    all_entries = Resource.objects.all()[(page-1)*mlen:(page)*mlen]
    results = []

    for res in all_entries:
        results.append(
            {
                'authors': res.authors.all(),
                'editors': res.editors.all(),
                'title': res.title,
                'subtitle': res.subtitle,
                'abstract': res.abstract,
                'publisher': res.publisher,
                'journal': res.journal,
                'date': str(res.date),
                'volume': str(res.volume),
                'pages': res.pages(),
                'series': res.series,
                'edition': res.edition,
                'url': str(res.url),
            }
        )

    ls = {
        'count': Resource.objects.count(),
        'next': "https://api.example.org/api/list?page={}".format(page+1),
        'previous': "https://api.example.org/api/list?page={}".format(page-1),
        'results': results
    }
    return HttpResponse(json.dumps(ls, default=str),
                        content_type='application/json')
