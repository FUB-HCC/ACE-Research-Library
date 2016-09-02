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
from django.views.generic import RedirectView
from . import settings

admin.site.site_header = 'Research Library Administration'


urlpatterns = [
    url(r'^$', RedirectView.as_view(pattern_name='admin:index')),
    url(r'^api/', include('researchlibrary.api.urls')),
    url(r'^admin/', admin.site.urls),
]


urlpatterns += [
    url(r'^{url}(?P<path>.*)'.format(url=settings.STATIC_URL[1:]),
        serve, {'document_root': settings.STATIC_ROOT})]
