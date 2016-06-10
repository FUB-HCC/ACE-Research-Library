from haystack import indexes
from .models import Resource, Person


class ResourceIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=True)
    published = indexes.DateField(model_attr='published')
    title = indexes.CharField(model_attr='title')
    subtitle = indexes.CharField(model_attr='subtitle')
    title_auto = indexes.EdgeNgramField(model_attr='title')
    subtitle_auto = indexes.EdgeNgramField(model_attr='subtitle')

    def get_model(self):
        return Resource

    def index_queryset(self, using=None):
        return self.get_model().objects.all()


class PersonIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=False)
    name = indexes.CharField(model_attr='name')
    name_auto = indexes.EdgeNgramField(model_attr='name')

    def get_model(self):
        return Person

    def index_queryset(self, using=None):
        return self.get_model().objects.all()
