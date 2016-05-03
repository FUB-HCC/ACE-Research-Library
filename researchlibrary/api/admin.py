from django.contrib import admin
from .models import Author, Publisher, Editor, Category, Resource


class AuthorAdmin(admin.ModelAdmin):
	pass
admin.site.register(Author, AuthorAdmin)

class EditorAdmin(admin.ModelAdmin):
	pass
admin.site.register(Editor, EditorAdmin)

class PublisherAdmin(admin.ModelAdmin):
	fieldsets = (
		(None, {
			'fields': ('name',)
		}),
		('Additional Fields', {
			'classes': ('collapse',),
			'fields': ('adress', 'city', 'state_providence', 'country'),
		}),
	)
admin.site.register(Publisher, PublisherAdmin)

class CategoryAdmin(admin.ModelAdmin):
	pass
admin.site.register(Category, CategoryAdmin)

class ResourceAdmin(admin.ModelAdmin):
	date_hierarchy = 'date'
	fieldsets = (
		('Mandatory Fields', {
			'fields' : ('title', 'authors', 'date')
		}),
		('Additional Fields', {
			'classes': ('collapse',),
			'fields': ('resource_file', 'url', 'categories', 'editors', 'publisher', 'subtitle', 'abstract', 'journal', 'volume', 'number', 'startpage', 'endpage', 'series', 'edition','sourcetype'),
		}),
	)
admin.site.register(Resource, ResourceAdmin)
