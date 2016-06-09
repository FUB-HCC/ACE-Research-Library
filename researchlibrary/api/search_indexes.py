from haystack import indexes
from .models import Resource


class ResourceIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=True)
    published = indexes.DateField(model_attr='published')

    def get_model(self):
        return Resource
