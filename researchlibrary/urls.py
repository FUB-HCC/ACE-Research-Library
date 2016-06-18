"""researchlibrary URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/1.9/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  url(r'^$', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  url(r'^$', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.conf.urls import url, include
    2. Add a URL to urlpatterns:  url(r'^blog/', include('blog.urls'))
"""
from django.conf.urls import url, include
from django.contrib import admin
from django.views.static import serve
from .api import views as api_views
from rest_framework import routers
from . import settings

admin.site.site_header = 'Research Library Administration'

router = routers.DefaultRouter()
router.register(r'list', api_views.ResourceViewSet, base_name='list')
router.register(r'search', api_views.SearchViewSet, base_name='search')


urlpatterns = [
    url(r'^api/', include(router.urls)),
    url(r'^$', api_views.status, name='status_view'),
    url(r'^admin/', admin.site.urls),
    url(r'^api/suggest/$', api_views.autosuggest),
    url(r'^tmp_search/$', api_views.tmp_search),
]


urlpatterns += [
    url(r'^{url}(?P<path>.*)'.format(url=settings.STATIC_URL[1:]),
        serve, {'document_root': settings.STATIC_ROOT})]
