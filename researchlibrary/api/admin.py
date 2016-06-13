import re
import requests
from readability.readability import Document
from django.contrib import admin
from django.forms import ModelForm
from django.core.urlresolvers import reverse
from django.utils.html import format_html
from django.conf.urls import url
from django_select2.forms import ModelSelect2TagWidget
from .models import Person, Category, Keyword, Resource


class ModelSelect2TagWidgetBase(ModelSelect2TagWidget):

    value_prefix = 'pk='

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.attrs['data-token-separators'] = r'[","]'

    def value_from_datadict(self, data, files, name):
        values = super().value_from_datadict(data, files, name)
        pks = []
        for value in values:
            if value.startswith(self.value_prefix):
                # A value like “pk=42”
                pks.append(int(value[len(self.value_prefix):]))
            else:
                # The actual name, like “Douglas Adams”
                obj = self.model.objects.create(**{self.field: value})
                pks.append(obj.pk)
        return pks

    def render_option(self, selected_choices, option_value, option_label):
        """
        Mark the option value so that we can recognize it later.
        """
        prefix = lambda item: '{}{}'.format(self.value_prefix, item)
        selected_choices = map(prefix, selected_choices)
        option_value = prefix(option_value)
        return super().render_option(selected_choices, option_value, option_label)


class PersonModelSelect2TagWidget(ModelSelect2TagWidgetBase):
    model = Person
    field = 'name'


class KeywordModelSelect2TagWidget(ModelSelect2TagWidgetBase):
    model = Keyword
    field = 'name'


@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    pass


class ResourceAdminForm(ModelForm):

    class Meta:
        model = Resource
        exclude = []
        widgets = {
            'authors': PersonModelSelect2TagWidget(),
            'editors': PersonModelSelect2TagWidget(),
            'keywords': KeywordModelSelect2TagWidget(),
        }


@admin.register(Resource)
class ResourceAdmin(admin.ModelAdmin):
    form = ResourceAdminForm
    change_list_template = 'api/change_list.html'
    change_form_template = 'api/change_form.html'
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
                       'endpage', 'series', 'edition', 'sourcetype', 'fulltext'),
        })
    )

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fulltext = None
        self.title = None

    def augmented_title(self, obj):
        return format_html(
            '<a href="{external_url}" target=_blank title="View resource (external)">[↗]</a>'
            '&nbsp;&nbsp;<a href="{edit_url}">{title}</a>',
            edit_url=reverse('admin:api_resource_change', args=[obj.id]),
            external_url=obj.url,
            title=obj.title)
    augmented_title.short_description = 'Title'

    def concatenated_authors(self, obj):
        return ', '.join([author.name for author in obj.authors.all()])
    concatenated_authors.short_description = 'Authors'

    def get_urls(self):
        urls = super(ResourceAdmin, self).get_urls()
        new_urls = [
            url(r'^add_url/$', self.add_url, name='api_resource_add_url'),
        ]
        return new_urls + urls

    def get_changeform_initial_data(self, request):
        initial = super(ResourceAdmin, self).get_changeform_initial_data(request)
        initial['fulltext'] = self.fulltext
        initial['title'] = self.title
        return initial

    def add_url(self, request, form_url='', extra_context=None):
        if request.method == 'POST':
            url = request.POST['url']
            response = requests.get(url, timeout=10)
            self.title = Document(response.text).short_title()
            self.fulltext = Document(response.text).summary()
            self.fulltext = re.sub('<br[^>]+>', '\n', self.fulltext)
            self.fulltext = re.sub('</?p[^>]+>', '\n\n', self.fulltext)
            self.fulltext = re.sub('<[^>]+>', '', self.fulltext)
            # Manipulating the request
            request.method = 'GET'
            request.GET = request.POST
            return self.add_view(request, form_url, extra_context=extra_context)
        return URLResourceAdmin(model=self.model, admin_site=self.admin_site) \
            .add_view(request, form_url, extra_context=extra_context)


class URLResourceAdmin(admin.ModelAdmin):
    add_form_template = 'api/add_form.html'
    fieldsets = [(None, {'fields': ['url']})]
