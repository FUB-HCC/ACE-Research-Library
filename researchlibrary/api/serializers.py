import datetime
from .models import Resource
from rest_framework import serializers


class ResourceSerializer(serializers.HyperlinkedModelSerializer):
    authors = serializers.StringRelatedField(many=True)
    editors = serializers.StringRelatedField(many=True)

    class Meta:
        model = Resource
        fields = ('authors', 'editors',  'title', 'subtitle', 'abstract', 'publisher', 'journal',
                  'published', 'accessed', 'volume', 'number', 'pages', 'series', 'edition', 'url')


class SearchSerializer(serializers.Serializer):

    id = serializers.CharField()
    published = serializers.DateField()
    text = serializers.CharField()

    def __init__(self, instance=None, data=serializers.empty, **kwargs):
        for entry in instance:
            if isinstance(entry.published, datetime.datetime):
                entry.published = entry.published.date()
        return super().__init__(instance=instance, data=data, **kwargs)
