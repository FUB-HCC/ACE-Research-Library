import re
from django.http import JsonResponse
from haystack.forms import SearchForm
from haystack.inputs import Clean, Raw
from rest_framework import viewsets
from ..version import __version__
from .models import Resource, Person, Category, Keyword
from .serializers import ResourceSerializer, SearchSerializer
from django.shortcuts import render_to_response
from django.core.context_processors import csrf
from haystack.query import SearchQuerySet
from collections import defaultdict
from itertools import chain


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

    def list(self, request, *args, **kwargs):
        q = request.GET.get('q', '')
        listamount = 10

        if q:
            sqs = SearchQuerySet().filter(content__contains=Raw(Clean(q))).models(Resource).highlight()
            response_catlist = self.getCommonValueList(sqs, 'categories', listamount)
            response_kywlist = self.getCommonValueList(sqs, 'keywords', listamount)
            response_rstlist = self.getCommonValueList(sqs, 'resource_type', listamount)
            response_publist = self.getCommonValueList(sqs, 'published', listamount)
        else:
            sqs = Resource.objects.all() #SearchQuerySet.models(Resource).all() is far slower than this
            response_catlist = [c.name for c in Category.objects.all()[:listamount]]
            response_kywlist = [k.name for k in Keyword.objects.all()[:listamount]]
            response_rstlist = self.getCommonValueList(sqs, 'resource_type', listamount)
            response_publist = self.getCommonValueList(sqs, 'published', listamount)

        page = self.paginate_queryset(sqs)
        serializer = SearchSerializer(page, many=True, context={'request': request})
        ret = self.get_paginated_response(serializer.data)

        ret.data['categories_list'] = response_catlist
        ret.data['keywords_list'] = response_kywlist
        ret.data['resource_type_list'] = response_rstlist
        ret.data['published_list'] = response_publist
        return ret

    def getCommonValueList(self, queryset, field, amount):
        if field not in ['categories', 'keywords', 'resource_type', 'published']:
            return []
        else:
            val_sqs = queryset.values_list(field, flat=True)
            if field=='published':
                years = []
                for d in val_sqs:
                    if d.year not in years: years.append(d.year)
                    if len(years)>=amount: break
                return sorted(years)
            elif field=='categories' or field=='keywords':
                return list(chain.from_iterable(val_sqs))[:amount]
            elif field=='resource_type':
                return list(map(str, val_sqs[:amount]))
        return []


class SuggestViewSet(viewsets.GenericViewSet):

    def list(self, request, *args, **kwargs):
        search_text = (request.GET.get('q', '')).strip()
        results = SearchQuerySet().models(Resource)
        
        if search_text:
            sq1 = [{"value":result.title, "field":"title"}
                for result in SearchQuerySet().autocomplete(title_auto=search_text)]
            sq2 = [{"value":result.name, "field":"author"}
                for result in SearchQuerySet().autocomplete(name_auto=search_text)]
            sq3 = [{"value":result.subtitle, "field":"subtitle"}
                for result in SearchQuerySet().autocomplete(subtitle_auto=search_text)]
            sq4 = [{"value":result.keyword, "field":"keyword"}
                for result in SearchQuerySet().autocomplete(keyword_auto=search_text)]
            results = (sq1 + sq2 + sq3 + sq4)[:10] #Limit to 10 suggestions
        else:
            results = []
        
        return JsonResponse({"results" : results})


def tmp_search(request):
    args = {}
    args.update(csrf(request))
    return render_to_response('tmp_search.html', args)

