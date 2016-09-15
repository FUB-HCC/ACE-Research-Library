"""Acerl API serializers.

The Acerl API returns JSON data. The serializers define rules
for the conversion of query sets and search results to JSON.
"""

import datetime
from .models import Resource
from rest_framework import serializers


class ResourceSerializer(serializers.HyperlinkedModelSerializer):
    """
    Serializer for the Resource model.
    """

    authors = serializers.StringRelatedField(many=True)
    editors = serializers.StringRelatedField(many=True)

    class Meta:
        model = Resource
        fields = ('authors', 'editors',  'title', 'subtitle', 'abstract', 'publisher', 'journal',
                  'published', 'accessed', 'volume', 'number', 'pages', 'series', 'edition', 'url')


class SearchSerializer(serializers.HyperlinkedModelSerializer):
    """
    Serializer for search results.
    """

    authors = serializers.StringRelatedField(many=True)
    editors = serializers.StringRelatedField(many=True)
    categories = serializers.StringRelatedField(many=True)
    excerpt = serializers.SerializerMethodField('fetch_excerpt')

    def __init__(self, instance=None, data=serializers.empty, **kwargs):
        for entry in instance:
            if isinstance(entry.published, datetime.datetime):
                entry.published = entry.published.date()
        return super().__init__(instance=instance, data=data, **kwargs)

    def fetch_excerpt(self, obj):
        try:
            return obj.highlighted['text'][0]
        except (TypeError, AttributeError):
            return ""

    class Meta:
        model = Resource
        fields = ('authors', 'editors',  'title', 'subtitle', 'abstract', 'publisher', 'journal',
                  'published', 'volume', 'number', 'pages', 'series', 'edition', 'url',
                  'resource_type', 'categories', 'excerpt', 'review')


class SuggestSerializer(serializers.Serializer):
    """
    Serializer for suggestions.
    """

    field = serializers.CharField()
    value = serializers.CharField()
