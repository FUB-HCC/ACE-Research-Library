"""Acerl API views.

The Acerl API is self-documenting. Call the API base URL in a
web browser for an overview of the available endpoints.
"""

from haystack.inputs import Clean, Raw
from rest_framework import viewsets
from .models import Resource, Category, Keyword
from .serializers import ResourceSerializer, SearchSerializer, SuggestSerializer
from haystack.query import SearchQuerySet
from itertools import chain
import datetime


class ResourceViewSet(viewsets.ReadOnlyModelViewSet):
    """
    The view of the /list endpoint of the API. For the API documentation
    call the endpoint in a browser.
    """

    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer


class SearchViewSet(viewsets.GenericViewSet):
    """
    The view of the /search endpoint of the API. For the API documentation
    call the endpoint in a browser.
    """

    queryset = SearchQuerySet()

    def list(self, request, *args, **kwargs):
        """
        Return a paginated list of search hits filtered according to
        user-selected criteria.
        """
        query = request.GET.get('q', '')
        listamount = 10
        catfilters = request.GET.getlist('catfilter')
        kywfilters = request.GET.getlist('kywfilter')
        rstfilters = request.GET.getlist('rstfilter')
        minyearfilter = request.GET.get('minyear', 1000)
        maxyearfilter = request.GET.get('maxyear', datetime.MAXYEAR)
        sorting = request.GET.get('sort', '')
        if query:
            sqs = self.queryset \
                .filter(content__contains=Raw(Clean(query))) \
                .models(Resource).highlight()
            sqs = self.apply_filters(sqs, catfilters, kywfilters, rstfilters, minyearfilter, maxyearfilter)
            response_catlist = self.get_common_value_list(sqs, 'categories', listamount)
            response_kywlist = self.get_common_value_list(sqs, 'keywords', listamount)
            response_rstlist = self.get_common_value_list(sqs, 'resource_type', listamount)
            response_publist = self.get_common_value_list(sqs, 'published', listamount)
        else:
            # SearchQuerySet.models(Resource).all() is far slower than this
            sqs = Resource.objects.all()
            sqs = self.apply_filters(sqs, catfilters, kywfilters, rstfilters,
                                     minyearfilter, maxyearfilter)
            response_catlist = [
                category.name for category
                in Category.objects.all().order_by('name')[:listamount]]
            response_kywlist = [
                keyword.name for keyword
                in Keyword.objects.all().order_by('name')[:listamount]]
            response_rstlist = self.get_common_value_list(sqs, 'resource_type', listamount)
            response_publist = self.get_common_value_list(sqs, 'published', listamount)
        sqs = self.apply_sorting(sqs, sorting)
        page = self.paginate_queryset(sqs)
        serializer = SearchSerializer(page, many=True, context={'request': request})
        ret = self.get_paginated_response(serializer.data)
        ret.data['categories_list'] = response_catlist
        ret.data['keywords_list'] = response_kywlist
        ret.data['resource_type_list'] = response_rstlist
        ret.data['published_list'] = response_publist
        return ret

    def get_common_value_list(self, queryset, field, amount):
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
            ret = list(set(ret)) # Remove duplicates
        return sorted(ret)[:amount]

    def apply_filters(self, queryset, catfilters, kywfilters, rstfilters,
                      minyearfilter, maxyearfilter):
        if catfilters:
            queryset = queryset.filter(categories__in=catfilters)
        if kywfilters:
            queryset = queryset.filter(keywords__in=kywfilters)
        if rstfilters:
            queryset = queryset.filter(resource_type__in=rstfilters)
        queryset = queryset.filter(published__year__range=[minyearfilter, maxyearfilter])
        return queryset

    def apply_sorting(self, queryset, sorting):
        return {
            'relevance' : queryset.order_by(),
            'date' : queryset.order_by('published'),
            'pubtype' : queryset.order_by('resource_type'),
        }.get(sorting, queryset.order_by())


class SuggestViewSet(viewsets.GenericViewSet):
    """
    The view of the /suggest endpoint of the API. For the API documentation
    call the endpoint in a browser.
    """

    queryset = SearchQuerySet()

    def list(self, request, *args, **kwargs):
        """
        Return a list of type-ahead suggestions based on what the user has
        already typed and four search fields, title, subtitle, author name,
        and keywords.
        """
        search_text = (request.GET.get('q', '')).strip()
        if search_text:
            sq1 = [{'value': result.title, 'field': 'title'} for result
                   in SearchQuerySet().autocomplete(title_auto=search_text)]
            sq2 = [{'value': result.name, 'field': 'author'} for result
                   in SearchQuerySet().autocomplete(name_auto=search_text)]
            sq3 = [{'value': result.subtitle, 'field': 'subtitle'} for result
                   in SearchQuerySet().autocomplete(subtitle_auto=search_text)]
            sq4 = [{'value': result.keyword, 'field': 'keyword'} for result
                   in SearchQuerySet().autocomplete(keyword_auto=search_text)]
            results = sq1 + sq2 + sq3 + sq4
        else:
            results = []
        page = self.paginate_queryset(results)
        serializer = SuggestSerializer(page, many=True, context={'request': request})
        return self.get_paginated_response(serializer.data)
