import json
import re
import requests
from haystack.forms import SearchForm
from readability.readability import Document
from stop_words import get_stop_words
from utilofies.stdlib import cached_property
from django.contrib import admin
from django.db.models import Count
from django.forms import ModelForm, Media
from django.http import JsonResponse
from django.core.urlresolvers import reverse
from django.utils.html import format_html
from django.conf import settings
from django.conf.urls import url
from django.utils.safestring import mark_safe
from django.views.decorators.csrf import csrf_exempt
from django_select2.forms import ModelSelect2TagWidget
from .models import Person, Category, Keyword, Resource


class Gist:

    stop_words = set(get_stop_words('en'))

    def __init__(self, html):
        self.html = html
        self.document = Document(html)

    @property
    def title(self):
        self.document.short_title()

    @cached_property
    def text(self):
        text = self.document.summary()
        text = re.sub('<br[^>]+>', '\n', text)
        text = re.sub('</?p[^>]+>', '\n\n', text)
        text = re.sub('<[^>]+>', '', text)
        text = re.sub('^[ \t]+$', '', text)
        text = re.sub('\n{3,}', '\n\n', text, flags=re.MULTILINE)
        return text

    @staticmethod
    def _common_prefix(one, two):
        parallelity = [x == y for x, y in zip(one, two)] + [False]
        return parallelity.index(False)

    def _find_representative(self, stem):
        tokens = self.text.split()
        prefixes = {token: self._common_prefix(token, stem) for token in tokens}
        best = lambda token: (-token[1], len(token[0]))
        return sorted(prefixes.items(), key=best)[0][0]

    @property
    def keywords(self):
        whoosh_backend = SearchForm().searchqueryset.query.backend
        if not whoosh_backend.setup_complete:
            whoosh_backend.setup()
        with whoosh_backend.index.searcher() as searcher:
            keywords = searcher.key_terms_from_text(
                'text', self.text, numterms=10, normalize=False)
            keywords = list(zip(*keywords))[0]
        return [self._find_representative(keyword) for keyword in keywords
                if keyword not in self.stop_words]


class ModelSelect2TagWidgetBase(ModelSelect2TagWidget):

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

    def render(self, name, value, attrs=None, choices=()):
        output = super().render(name, value, attrs, choices)
        # Let’s think of something new if and when the page reaches 1+ MiB.
        output += """
            <script type="text/javascript">
                var select = $('#%s');
                select.select2({
                    createTag: function(params) {
                        return {id: -1, text: params.term}
                    },
                    tags: true,
                    data: %s
                });
                select.on('select2:select', register('%s'));
            </script>\n
        """ % (
            attrs['id'],
            json.dumps([
                {'id': obj.pk, 'text': getattr(obj, self.field)}
                for obj in self.model.objects.all()]),
            self.model._meta.model_name)
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

    def render(self, name, value, attrs=None, choices=()):
        output = super().render(name, value, attrs, choices)
        output += 'Suggestions: <span id="keyword_suggestions"></span>'
        return mark_safe(output)


class UsageCountListFilter(admin.SimpleListFilter):
    title = 'Usage count'
    parameter_name = 'usage_count'
    count_field = 'resource__id'

    def lookups(self, request, model_admin):
        return (
            ('0', 'Unused'),
            ('1', 'Used once'),
            ('2', 'Used twice'),
            ('3', 'Used thrice'),
            ('4', 'Used often'),
        )

    def queryset(self, request, queryset):
        value = int(self.value()) if self.value() else None
        if value is not None and value < 4:
            return queryset.annotate(resource_count=Count(self.count_field)) \
                .filter(resource_count=value)
        elif value is not None:
            return queryset.annotate(resource_count=Count(self.count_field)) \
                .filter(resource_count__gte=4)
        else:
            return queryset


class AuthorUsageCountListFilter(UsageCountListFilter):
    title = 'Usage count as author'
    parameter_name = 'usage_count_as_author'
    count_field = 'resources_authored__id'


class EditorUsageCountListFilter(UsageCountListFilter):
    title = 'Usage count as editor'
    parameter_name = 'usage_count_as_editor'
    count_field = 'resources_edited__id'



@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    list_display = ['name', 'usage_count']
    list_filter = [UsageCountListFilter]

    def usage_count(self, obj):
        return obj.resource_set.count()


@admin.register(Keyword)
class KeywordAdmin(admin.ModelAdmin):
    list_display = ['name', 'usage_count']
    list_filter = [UsageCountListFilter]

    def usage_count(self, obj):
        return obj.resource_set.count()


@admin.register(Person)
class PersonAdmin(admin.ModelAdmin):
    list_display = ['name', 'usage_count_as_author', 'usage_count_as_editor']
    list_filter = [AuthorUsageCountListFilter, EditorUsageCountListFilter]

    def usage_count_as_author(self, obj):
        return obj.resources_authored.count()

    def usage_count_as_editor(self, obj):
        return obj.resources_edited.count()


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
        self.keywords = None

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
            url(r'^create_person/$', self.create_person, name='api_resource_create_person'),
            url(r'^create_keyword/$', self.create_keyword, name='api_resource_create_keyword'),
        ]
        return new_urls + urls

    def get_changeform_initial_data(self, request):
        initial = super(ResourceAdmin, self).get_changeform_initial_data(request)
        initial['fulltext'] = self.fulltext
        initial['title'] = self.title
        return initial

    def add_url(self, request, form_url='', extra_context=None):
        if request.method == 'POST':
            extra_context = extra_context or {}
            url = request.POST['url']
            response = requests.get(url, timeout=10)
            gist = Gist(response.text)
            self.title = gist.title
            self.fulltext = gist.text
            extra_context['keywords'] = gist.keywords
            # Manipulating the request
            request.method = 'GET'
            request.GET = request.POST
            return self.add_view(request, form_url, extra_context=extra_context)
        return URLResourceAdmin(model=self.model, admin_site=self.admin_site) \
            .add_view(request, form_url, extra_context=extra_context)

    @csrf_exempt
    def create_person(self, request):
        """
        Create a person.

        Little wrapper to circumvent all the security stuff.
        https://docs.djangoproject.com/en/1.9/ref/csrf/#ajax

        TODO: Don’t circumvent all the security stuff.
        """
        person = Person.objects.create(name=request.POST['name'])
        return JsonResponse({'id': person.pk, 'text': person.name})

    @csrf_exempt
    def create_keyword(self, request):
        """
        Create a keyword.

        Little wrapper to circumvent all the security stuff.
        https://docs.djangoproject.com/en/1.9/ref/csrf/#ajax

        TODO: Don’t circumvent all the security stuff.
        """
        keyword = Keyword.objects.create(name=request.POST['name'])
        return JsonResponse({'id': keyword.pk, 'text': keyword.name})

    def get_form(self, request, obj=None, **kwargs):
        form = super().get_form(request, obj, **kwargs)
        form.base_fields['authors'].widget.can_add_related = False
        form.base_fields['editors'].widget.can_add_related = False
        form.base_fields['keywords'].widget.can_add_related = False
        return form


class URLResourceAdmin(admin.ModelAdmin):
    add_form_template = 'api/add_form.html'
    fieldsets = [(None, {'fields': ['url']})]
