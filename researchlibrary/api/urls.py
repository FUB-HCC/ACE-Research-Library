from django.conf.urls import url, include
from . import views
from rest_framework import routers


router = routers.DefaultRouter()
router.register(r'list', views.ResourceViewSet, base_name='list')
router.register(r'search', views.SearchViewSet, base_name='search')
router.register(r'suggest', views.SuggestViewSet, base_name='suggest')


urlpatterns = [
    url(r'^v1/', include(router.urls)),
    url(r'^$', views.status, name='status_view'),
]
