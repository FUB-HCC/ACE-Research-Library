from django.contrib import admin
from django.core.urlresolvers import reverse
from django.utils.html import format_html
from .models import Person, Category, Keyword, Resource


class PersonAdmin(admin.ModelAdmin):
    pass


class CategoryAdmin(admin.ModelAdmin):
    pass


class KeywordAdmin(admin.ModelAdmin):
    pass


class ResourceAdmin(admin.ModelAdmin):
    list_display = ['augmented_title', 'concatenated_authors', 'published', 'resource_type']
    search_fields = ['title', 'authors', 'editors', 'url', 'categories', 'keywords', 'publisher',
                     'subtitle', 'abstract', 'review', 'journal', 'series', 'edition', 'sourcetype']
    list_filter = ['resource_type', 'categories', 'sourcetype']
    date_hierarchy = 'published'
    fieldsets = (
        ('Main Fields', {
            'fields': ('title', 'subtitle', 'authors', 'editors', 'published', 'accessed', 'url',
                       'categories', 'keywords', 'resource_type', 'abstract', 'review')
        }),
        ('Additional Fields', {
            'classes': ('collapse',),
            'fields': ('resource_file', 'publisher', 'journal', 'volume', 'number', 'startpage',
                       'endpage', 'series', 'edition', 'sourcetype'),
        }),
    )

    def augmented_title(self, obj):
        return format_html(
            '<a href="{external_url}" target=_blank title="View resource (external)">[↗]</a>'
            '&nbsp;&nbsp;<a href="{edit_url}">{title}</a>',
            edit_url=reverse('admin:api_resource_change', args=[obj.id]),
            external_url=obj.url,
            title=obj.title)
    augmented_title.short_description = 'Title'

    def concatenated_authors(self, obj):
        return ', '.join([person.name for person in obj.authors.all()])
    concatenated_authors.short_description = 'Authors'


admin.site.register(Person, PersonAdmin)
admin.site.register(Category, CategoryAdmin)
admin.site.register(Keyword, KeywordAdmin)
admin.site.register(Resource, ResourceAdmin)
