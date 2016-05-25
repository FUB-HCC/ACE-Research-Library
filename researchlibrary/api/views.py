from django.http import JsonResponse
from haystack.forms import SearchForm
from rest_framework import viewsets
from rest_framework.response import Response
from ..version import __version__
from .models import Resource
from .serializers import ResourceSerializer, SearchSerializer


def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return JsonResponse(status)


class ResourceViewSet(viewsets.ModelViewSet):

    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer


class SearchViewSet(viewsets.ViewSet):

    def list(self, request):
        form = SearchForm(request.GET)
        if form.is_valid():
            serializer = SearchSerializer(form.search(), many=True)
            return Response(serializer.data)
