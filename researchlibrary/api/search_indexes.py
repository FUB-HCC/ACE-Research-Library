"""Acerl API search indices.

The Acerl API allows indexed searches over the content of resources
(e.g. papers, books, blog posts) as well as several meta data. This
module defines the indices.
"""

from haystack import indexes
from .models import Resource, Keyword, Person


class ResourceIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=True)
    published = indexes.DateField(model_attr='published')
    abstract = indexes.CharField(model_attr='abstract')
    review = indexes.CharField(model_attr='review')
    publisher = indexes.CharField(model_attr='publisher')
    journal = indexes.CharField(model_attr='journal')
    volume = indexes.IntegerField(model_attr='volume', null=True)
    number = indexes.IntegerField(model_attr='number', null=True)
    pages = indexes.CharField(model_attr='pages')
    series = indexes.CharField(model_attr='series')
    edition = indexes.CharField(model_attr='edition')
    url = indexes.CharField(model_attr='url')
    resource_type = indexes.CharField(model_attr='resource_type')
    categories = indexes.MultiValueField(indexed=True, stored=True)
    authors = indexes.MultiValueField(indexed=True, stored=True)
    editors = indexes.MultiValueField(indexed=True, stored=True)
    keywords = indexes.MultiValueField(indexed=True, stored=True)
    title = indexes.CharField(model_attr='title')
    subtitle = indexes.CharField(model_attr='subtitle')
    title_auto = indexes.EdgeNgramField(model_attr='title')
    subtitle_auto = indexes.EdgeNgramField(model_attr='subtitle')
    #A search with the 'author:'-tag will look through this field:
    author = indexes.CharField(indexed=True, stored=True)

    def get_model(self):
        return Resource

    def index_queryset(self, using=None):
        return self.get_model().objects.all()

    def prepare_categories(self, obj):
        return [c for c in obj.categories.all()]

    def prepare_authors(self, obj):
        return [a for a in obj.authors.all()]

    def prepare_editors(self, obj):
        return [e for e in obj.editors.all()]

    def prepare_keywords(self, obj):
        return [k for k in obj.keywords.all()]

    def prepare_author(self, obj):
        return (' '.join([a.name for a in obj.authors.all() | obj.editors.all()]))


class PersonIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=False)
    name = indexes.CharField(model_attr='name')
    name_auto = indexes.EdgeNgramField(model_attr='name')

    def get_model(self):
        return Person

    def index_queryset(self, using=None):
        return self.get_model().objects.all()


class KeywordIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=False)
    keyword = indexes.CharField(model_attr='name')
    keyword_auto = indexes.EdgeNgramField(model_attr='name')

    def get_model(self):
        return Keyword

    def index_queryset(self, using=None):
        return self.get_model().objects.all()
