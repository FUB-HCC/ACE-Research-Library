import json
from django.views.generic.list import ListView
from django.http import HttpResponse, JsonResponse
from ..version import __version__
from .models import Resource, Author
from rest_framework import viewsets, response, pagination
from researchlibrary.api.serializers import ResourceSerializer


class ResourceViewSet(viewsets.ModelViewSet):
    """
    API endpoint that allows resources to be viewed or edited.
    """
    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer

class ResourcePagination(pagination.PageNumberPagination):
    page_size = 10
    page_size_query_param = 'len'
    pax_page_size = 1000
    def get_paginated_response(self, data):
        return Response({
            'links': {
                'next': self.get_next_link(),
                'previous': self.get_previous_link()
            },
            'count': self.page.paginator.count,
            'results': data
        })

def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return JsonResponse( status )


def list(request):
    page = int(request.GET.get('page', 1))
    mlen = int(request.GET.get('len', 5))

    all_entries = Resource.objects.all()[(page-1)*mlen:(page)*mlen]
    results = []

    for res in all_entries:
        results.append(
            {
                #Alternative: 'authors': serializers.serialize('python', res.authors.all()),
                'authors': [{'name': author.name} for author in res.authors.all()],
		'editors': [{'name': editor.name} for editor in res.editors.all()],
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
        'status': 200,
        'count': Resource.objects.count(),
        'next': "https://api.example.org/api/list/?page={}".format(page+1),
        'previous': "https://api.example.org/api/list/?page={}".format(page-1),
        'results': results
    }
    return JsonResponse( ls )

