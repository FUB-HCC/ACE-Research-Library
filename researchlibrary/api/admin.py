import requests
from django.contrib import admin
from django.contrib.postgres.fields import ArrayField
from django.core.urlresolvers import reverse
from django.utils.html import format_html
from django.conf.urls import url
from django_select2.forms import ModelSelect2TagWidget
from .models import Category, Resource


@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    pass


@admin.register(Resource)
class ResourceAdmin(admin.ModelAdmin):
    change_list_template = 'api/change_list.html'
    list_display = ['augmented_title', 'concatenated_authors', 'published', 'resource_type']
    search_fields = ['title', 'authors', 'editors', 'url', 'categories', 'keywords', 'publisher',
                     'subtitle', 'abstract', 'review', 'journal', 'series', 'edition', 'sourcetype']
    list_filter = ['resource_type', 'categories', 'sourcetype']
    date_hierarchy = 'published'
    filter_horizontal = ['categories']
    fieldsets = (
        ('Main Fields', {
            'fields': ('title', 'subtitle', 'authors', 'editors', 'published', 'accessed', 'url',
                       'resource_type')
        }),
        ('Auxilliary Fields', {
            'classes': ('collapse',),
            'fields': ('categories', 'keywords', 'abstract', 'review'),
        }),
        ('Optional Fields', {
            'classes': ('collapse',),
            'fields': ('publisher', 'journal', 'volume', 'number', 'startpage',
                       'endpage', 'series', 'edition', 'sourcetype'),
        }),
        ('Content Fields', {
            'classes': ('collapse',),
            'fields': ('resource_file', 'fulltext'),
        }),
    )
    formfield_overrides = {
        ArrayField: {'widget': ModelSelect2TagWidget},
    }

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fulltext = None

    def augmented_title(self, obj):
        return format_html(
            '<a href="{external_url}" target=_blank title="View resource (external)">[↗]</a>'
            '&nbsp;&nbsp;<a href="{edit_url}">{title}</a>',
            edit_url=reverse('admin:api_resource_change', args=[obj.id]),
            external_url=obj.url,
            title=obj.title)
    augmented_title.short_description = 'Title'

    def concatenated_authors(self, obj):
        return ', '.join(obj.authors)
    concatenated_authors.short_description = 'Authors'

    def get_urls(self):
        urls = super(ResourceAdmin, self).get_urls()
        new_urls = [
            url(r'^add_url/$', self.add_url, name='api_resource_add_url'),
            url(r'^add_file/$', self.add_file, name='api_resource_add_file'),
        ]
        return new_urls + urls

    def get_changeform_initial_data(self, request):
        initial = super(ResourceAdmin, self).get_changeform_initial_data(request)
        initial['fulltext'] = self.fulltext
        return initial

    def add_url(self, request, form_url='', extra_context=None):
        if request.method == 'POST':
            url = request.POST['url']
            response = requests.get(url, timeout=10)
            self.fulltext = response.text
            # Manipulating the request
            request.method = 'GET'
            request.GET = request.POST
            return self.add_view(request, form_url, extra_context=extra_context)
        return URLResourceAdmin(model=self.model, admin_site=self.admin_site) \
            .add_view(request, form_url, extra_context=extra_context)

    def add_file(self, request, object_id, form_url='', extra_context=None):
        return FileResourceAdmin(model=self.model, admin_site=self.admin_site) \
            .add_view(request, form_url, extra_context=extra_context)


class URLResourceAdmin(admin.ModelAdmin):
    add_form_template = 'api/change_form.html'
    fieldsets = [(None, {'fields': ['url']})]


class FileResourceAdmin(admin.ModelAdmin):
    add_form_template = 'api/change_form.html'
    fieldsets = [(None, {'fields': ['resource_file']})]
