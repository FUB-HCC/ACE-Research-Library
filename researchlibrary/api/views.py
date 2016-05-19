import json
from django.http import HttpResponse, JsonResponse
from ..version import __version__
from .models import Resource
from rest_framework import viewsets
from researchlibrary.api.serializers import ResourceSerializer


class ResourceViewSet(viewsets.ModelViewSet):
    queryset = Resource.objects.all()
    serializer_class = ResourceSerializer

def status(request):
    status = {
        'status': 200,
        'version': __version__,
        'tagline': 'You know, for animals'}
    return JsonResponse( status )

