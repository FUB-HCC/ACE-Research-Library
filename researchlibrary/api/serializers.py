from .models import *
from rest_framework import serializers

class ResourceSerializer(serializers.HyperlinkedModelSerializer):
    authors = serializers.StringRelatedField(many=True)
    editors = serializers.StringRelatedField(many=True)

    class Meta:
        model = Resource
        fields = ('authors', 'editors',  'title', 'subtitle', 'abstract', 'publisher', 'journal', 'date', 'volume', 'number', 'pages', 'series', 'edition', 'url')

