from django.http import JsonResponse
from haystack.forms import SearchForm
from rest_framework import viewsets
from ..version import __version__
from .models import Resource, Person
from .serializers import ResourceSerializer, SearchSerializer

from django.shortcuts import render_to_response
from django.core.context_processors import csrf
from haystack.query import SearchQuerySet


def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return JsonResponse(status)


class ResourceViewSet(viewsets.ModelViewSet):

    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer


class SearchViewSet(viewsets.GenericViewSet):

    def list(self, request):
        form = SearchForm(request.GET)
        page = self.paginate_queryset(form.search())
        serializer = SearchSerializer(page, many=True)
        return self.get_paginated_response(serializer.data)


def tmp_search(request):
    args = {}
    args.update(csrf(request))
    return render_to_response('tmp_search.html', args)


def autosuggest(request):
    if request.method == "GET":
        search_text = request.GET['q']
    else:
        search_text = ''
    
    results = SearchQuerySet().models(Resource)
    
    if search_text:
        std = lambda x: x
        spec = lambda x: 'title:'+x
        sq1 = [m(result.title) for result in SearchQuerySet().autocomplete(title_auto=search_text) for m in (std, spec)]
        spec = lambda x: 'auth:'+x
        sq2 = [m(result.name) for result in SearchQuerySet().autocomplete(name_auto=search_text) for m in (std, spec)]
        sq3 = [result.subtitle for result in SearchQuerySet().autocomplete(subtitle_auto=search_text)]
        results = (sq1 + sq2 + sq3)[:10] #Limit to 10 suggestions
    else:
        results = []

    results = [{
        'result': result,
        'link': '/api/search?q=' + result
    } for result in results]

    return JsonResponse({'results': results})
    
