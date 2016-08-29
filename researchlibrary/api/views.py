from django.http import JsonResponse
from haystack.inputs import Clean, Raw
from rest_framework import viewsets
from ..version import __version__
from .models import Resource, Category, Keyword
from .serializers import ResourceSerializer, SearchSerializer
from haystack.query import SearchQuerySet
from itertools import chain


def status(request):
    """Returns a health check–like response
    modelled after Elasticsearch."""
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return JsonResponse(status)


class ResourceViewSet(viewsets.ModelViewSet):
    """
    A component of the Rest Framework description of
    the Acerl API. This is only used by the /list
    endpoint.
    """

    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer


class SearchViewSet(viewsets.GenericViewSet):
    """
    The component of the Rest Framework description of
    the Acerl API that is responsible for handling search
    requests.
    """

    def list(self, request, *args, **kwargs):
        query = request.GET.get('q', '')
        listamount = 10
        catfilters = request.GET.getlist('catfilter')
        kywfilters = request.GET.getlist('kywfilter')
        rstfilters = request.GET.getlist('rstfilter')
        pubfilters = request.GET.getlist('pubfilter')
        sorting = request.GET.get('sort', '')

        if query:
            sqs = SearchQuerySet() \
                .filter(content__contains=Raw(Clean(query))) \
                .models(Resource).highlight()
            sqs = self.applyFilters(sqs, catfilters, kywfilters, pubfilters, rstfilters)
            response_catlist = self.getCommonValueList(sqs, 'categories', listamount)
            response_kywlist = self.getCommonValueList(sqs, 'keywords', listamount)
            response_rstlist = self.getCommonValueList(sqs, 'resource_type', listamount)
            response_publist = self.getCommonValueList(sqs, 'published', listamount)
        else:
            #SearchQuerySet.models(Resource).all() is far slower than this
            sqs = Resource.objects.all()
            sqs = self.applyFilters(sqs, catfilters, kywfilters, pubfilters, rstfilters)
            response_catlist = [
                category.name for category
                in Category.objects.all().order_by('name')[:listamount]]
            response_kywlist = [
                keyword.name for keyword
                in Keyword.objects.all().order_by('name')[:listamount]]
            response_rstlist = self.getCommonValueList(sqs, 'resource_type', listamount)
            response_publist = self.getCommonValueList(sqs, 'published', listamount)

        sqs = self.applySorting(sqs, sorting)
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
            ret = []
            if field == 'published':
                ret = [d.year for d in val_sqs]
            elif field == 'categories' or field == 'keywords':
                ret = chain.from_iterable(val_sqs)
            elif field == 'resource_type':
                ret = map(str, val_sqs[:amount])
            ret = list(set(ret)) #Remove duplicates
        return sorted(ret)[:amount]

    def applyFilters(self, queryset, catfilters, kywfilters, pubfilters, rstfilters):
        pubfilters = list(map(int, pubfilters))
        if catfilters:
            queryset = queryset.filter(categories__in=catfilters)
        if kywfilters:
            queryset = queryset.filter(keywords__in=kywfilters)
        if rstfilters:
            queryset = queryset.filter(resource_type__in=rstfilters)
        if pubfilters:
            queryset = queryset.filter(published__year__in=pubfilters)
        return queryset

    def applySorting(self, queryset, sorting):
        return {
            'relevance' : queryset.order_by(),
            'date' : queryset.order_by('published'),
            'pubtype' : queryset.order_by('resource_type'),
        }.get(sorting, queryset.order_by())



class SuggestViewSet(viewsets.GenericViewSet):

    def list(self, request, *args, **kwargs):
        search_text = (request.GET.get('q', '')).strip()
        results = SearchQuerySet().models(Resource)

        if search_text:
            sq1 = [{'value': result.title, 'field': 'title'}
                for result in SearchQuerySet().autocomplete(title_auto=search_text)]
            sq2 = [{'value': result.name, 'field': 'author'}
                for result in SearchQuerySet().autocomplete(name_auto=search_text)]
            sq3 = [{'value': result.subtitle, 'field': 'subtitle'}
                for result in SearchQuerySet().autocomplete(subtitle_auto=search_text)]
            sq4 = [{'value': result.keyword, 'field': 'keyword'}
                for result in SearchQuerySet().autocomplete(keyword_auto=search_text)]
            results = (sq1 + sq2 + sq3 + sq4)[:10] #Limit to 10 suggestions
        else:
            results = []

        return JsonResponse({'results' : results})
