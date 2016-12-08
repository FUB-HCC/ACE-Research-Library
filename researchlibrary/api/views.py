"""Acerl API views.

The Acerl API is self-documenting. Call the API base URL in a
web browser for an overview of the available endpoints.
"""
import datetime
from collections import defaultdict
from itertools import chain
from operator import itemgetter

from haystack.inputs import Raw
from haystack.query import SearchQuerySet
from rest_framework import viewsets
from utilofies.stdlib import lgroupby

from .models import Resource
from .serializers import (ResourceSerializer, SearchSerializer,
                          SuggestSerializer)


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
        queryset = self._filtered_queryset(request)
        page = self.paginate_queryset(queryset)
        serializer = SearchSerializer(page, many=True, context={'request': request})
        response = self.get_paginated_response(serializer.data)
        sets = self._get_attribute_sets(queryset)
        response.data.update(sets)
        return response

    def _filtered_queryset(self, request):
        query = request.GET.get('q', 'magick')  # Getting all resources unfiltered takes about 3 s;
                                                # getting all resources filtered by a word that’s
                                                # contained in every one of them less than 0.1 s.
        category_filters = request.GET.getlist('category')
        keyword_filters = request.GET.getlist('keyword')
        resource_type_filters = request.GET.getlist('type')
        min_year_filter = request.GET.get('minyear', 1000)
        max_year_filter = request.GET.get('maxyear', datetime.MAXYEAR)
        sorting = request.GET.get('sort', '')
        queryset = self.queryset \
            .filter(content__contains=Raw(query)) \
            .models(Resource).highlight()
        if category_filters:
            queryset = queryset.filter(categories__in=category_filters)
        if keyword_filters:
            queryset = queryset.filter(keywords__in=keyword_filters)
        if resource_type_filters:
            queryset = queryset.filter(resource_type__in=resource_type_filters)
        queryset = queryset.filter(published__year__range=[min_year_filter, max_year_filter])
        if sorting.strip('-') in queryset[0]._additional_fields:
            queryset = queryset.order_by(sorting)
        return queryset

    def _get_attribute_sets(self, queryset):
        lists = defaultdict(list)
        for hit in queryset:
            # Linearize all attributes including all duplicates
            lists['categories_list'].extend(hit.categories)
            lists['keywords_list'].extend(hit.keywords)
            lists['resource_type_list'].append(hit.resource_type)
            lists['published_list'].append(hit.published.year)
        for key in lists.keys():
            # Sort attributes by their frequency
            lists[key].sort()  # Necessary for groupby and lgroupby
            lists[key] = [(key_, len(group)) for key_, group in lgroupby(lists[key])]
            lists[key].sort(key=itemgetter(1), reverse=True)  # Sort by frequency
            lists[key] = list(zip(*lists[key]))[0]
        lists['keywords_list'] = lists['keywords_list'][:50]  # Limit length
        return lists


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
