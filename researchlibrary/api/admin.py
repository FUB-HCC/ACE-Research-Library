import json
import re
import requests
from readability.readability import Document
from django.contrib import admin
from django.forms import ModelForm, Media
from django.core.urlresolvers import reverse
from django.utils.html import format_html
from django.conf import settings
from django.conf.urls import url
from django.utils.safestring import mark_safe
from django_select2.forms import ModelSelect2TagWidget
from .models import Person, Category, Keyword, Resource


class Gist:

    def __init__(self, html):
        self.html = html
        self.document = Document(html)

    @property
    def title(self):
        self.document.short_title()

    @property
    def text(self):
        text = self.document.summary()
        text = re.sub('<br[^>]+>', '\n', text)
        text = re.sub('</?p[^>]+>', '\n\n', text)
        text = re.sub('<[^>]+>', '', text)
        text = re.sub('^[ \t]+$', '', text)
        text = re.sub('\n{3,}', '\n\n', text, flags=re.MULTILINE)
        return text


class ModelSelect2TagWidgetBase(ModelSelect2TagWidget):

    value_prefix = 'pk='

    def value_from_datadict(self, data, files, name):
        """
        Override super()’s method so that we get to mark what is
        a primary key and what is a name.

        Existing values have primary keys, which Select2 posts as expected,
        but when the user enters new data, it posts the strings such
        that they are indistinguishable from the primary keys.
        """
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

    def get_url(self):
        """
        Get noting at all.

        Django Select2 abuses the cache to inject autosuggest values into the
        admin view via AJAX. That’s terrible. Since we don’t use that view,
        this method that references it needs to be overwritten.
        """
        pass

    def build_attrs(self, extra_attrs=None, **kwargs):
        """
        Remove the data attributes for the AJAX call we don’t use and
        customize a bit more.
        """
        attrs = super().build_attrs(extra_attrs=extra_attrs, **kwargs)
        # Originally:
        # {'data-token-separators': '[",", " "]',
        #  'data-minimum-input-length': 1,
        #  'data-tags': 'true',
        #  'data-ajax--type': 'GET',
        #  'data-ajax--cache': 'true',
        #  'data-ajax--url': '/select2/fields/auto.json',
        #  'class': 'django-select2 django-select2-heavy',
        #  'name': 'authors',
        #  'id': 'id_authors',
        #  'data-field_id': 'MTQwNTg5NTg3NzM3NTEy:1bD5p3:suZhZBqbvSzNpEMGve-KGjfmffw',
        #  'data-allow-clear': 'false'}
        attrs['data-token-separators'] = r'[","]'
        del attrs['data-ajax--url']
        del attrs['data-ajax--type']
        del attrs['data-ajax--cache']
        return attrs

    def prefix(self, item):
        return '{}{}'.format(self.value_prefix, item)

    def render_option(self, selected_choices, option_value, option_label):
        """
        Mark the option value so that we can recognize it later.
        """
        selected_choices = map(self.prefix, selected_choices)
        option_value = self.prefix(option_value)
        return super().render_option(selected_choices, option_value, option_label)

    def render(self, name, value, attrs=None, choices=()):
        output = super().render(name, value, attrs, choices)
        # Let’s think of something new if and when the page reaches 1+ MiB.
        output += """
            <script type="text/javascript">
                $('#%s').select2({
                  data: %s
                })
            </script>\n
        """ % (attrs['id'], json.dumps([
                {'id': self.prefix(obj.pk), 'text': getattr(obj, self.field)}
                for obj in self.model.objects.all()]))
        return mark_safe(output)

    @property
    def media(self):
        """
        Construct Media as a dynamic property.
        .. Note:: For more information visit
            https://docs.djangoproject.com/en/1.8/topics/forms/media/#media-as-a-dynamic-property
        """
        return Media(
            js=(settings.SELECT2_JS,),
            css={'screen': (settings.SELECT2_CSS,)})


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
    search_fields = ['title', 'authors__name', 'editors__name', 'url', 'categories__name',
                     'keywords__name', 'publisher', 'subtitle', 'abstract', 'review',
                     'journal', 'series', 'edition', 'sourcetype']
    list_filter = ['resource_type', 'categories', 'sourcetype']
    date_hierarchy = 'published'
    filter_horizontal = ['categories']
    fieldsets = (
        ('Main Fields', {
            'fields': ('title', 'subtitle', 'authors', 'editors', 'published', 'accessed', 'url',
                       'fulltext_url', 'resource_type')
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
            gist = Gist(response.text)
            self.title = gist.title
            self.fulltext = gist.text
            # Manipulating the request
            request.method = 'GET'
            request.GET = request.POST
            return self.add_view(request, form_url, extra_context=extra_context)
        return URLResourceAdmin(model=self.model, admin_site=self.admin_site) \
            .add_view(request, form_url, extra_context=extra_context)


class URLResourceAdmin(admin.ModelAdmin):
    add_form_template = 'api/add_form.html'
    fieldsets = [(None, {'fields': ['url']})]
