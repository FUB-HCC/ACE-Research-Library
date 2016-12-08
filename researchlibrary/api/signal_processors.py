from django.db import models
from haystack.signals import BaseSignalProcessor
from .models import Resource, Category, Keyword, Person


class SignalProcessor(BaseSignalProcessor):

    def setup(self):
        models.signals.post_save.connect(self.handle_save, sender=Resource)
        models.signals.post_delete.connect(self.handle_delete, sender=Resource)
        for model in (Category, Keyword, Person):
            models.signals.post_save.connect(self.handle_related, sender=model)
            models.signals.post_delete.connect(self.handle_related, sender=model)

    def teardown(self):
        models.signals.post_save.disconnect(self.handle_save, sender=Resource)
        models.signals.post_delete.disconnect(self.handle_delete, sender=Resource)
        for model in (Category, Keyword, Person):
            models.signals.post_save.disconnect(self.handle_related, sender=model)
            models.signals.post_delete.disconnect(self.handle_related, sender=model)

    def handle_save(self, sender, instance, **kwargs):
        """
        Given an individual model instance, determine which backends the
        update should be sent to & update the object on those backends.
        """
        assert sender == Resource
        using_backends = self.connection_router.for_write(instance=instance)
        for using in using_backends:
            index = self.connections[using].get_unified_index().get_index(Resource)
            index.update_object(instance, using=using)

    def handle_delete(self, sender, instance, **kwargs):
        """
        Given an individual model instance, determine which backends the
        delete should be sent to & delete the object on those backends.
        """
        assert sender == Resource
        using_backends = self.connection_router.for_write(instance=instance)
        for using in using_backends:
            index = self.connections[using].get_unified_index().get_index(Resource)
            index.remove_object(instance, using=using)

    def handle_related(self, sender, instance, **kwargs):
        """
        Given an individual model instance, determine which backends the
        update should be sent to & update the object on those backends.
        """
        using_backends = self.connection_router.for_write(instance=instance)
        for using in using_backends:
            index = self.connections[using].get_unified_index().get_index(Resource)
            if sender == Person:
                for resource in instance.resources_authored.all():
                    index.update_object(resource, using=using)
                for resource in instance.resources_edited.all():
                    index.update_object(resource, using=using)
            else:
                for resource in instance.resource_set.all():
                    index.update_object(resource, using=using)
