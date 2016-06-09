import datetime

from django.db import models
from django.core.exceptions import ValidationError
from .models_choices import SOURCETYPE_CHOICES, RESOURCE_TYPE_CHOICES


class Person(models.Model):
    name = models.CharField(max_length=100)

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name


class Category(models.Model):
    name = models.CharField(max_length=50)

    def __str__(self):
        return self.name

    class Meta:
        verbose_name_plural = 'categories'


class Keyword(models.Model):
    name = models.CharField(max_length=50)

    def __str__(self):
        return self.name


class Resource(models.Model):
    # Mandatory fields
    authors = models.ManyToManyField(Person, related_name='resources_authored')
    title = models.CharField(max_length=300, unique=True)
    published = models.DateField('date published')
    resource_type = models.CharField(
        max_length=30, choices=RESOURCE_TYPE_CHOICES, blank=True)

    # Optional fields
    accessed = models.DateField('date accessed', null=True, blank=True)
    resource_file = models.FileField(upload_to=None, max_length=100, blank=True)
    url = models.URLField(max_length=2000, blank=True)
    categories = models.ManyToManyField(Category, blank=True)
    keywords = models.ManyToManyField(Keyword, blank=True)
    editors = models.ManyToManyField(Person, related_name='resources_edited', blank=True)
    publisher = models.CharField(max_length=300, blank=True)
    subtitle = models.CharField(max_length=500, blank=True)
    abstract = models.TextField(blank=True)
    review = models.TextField(blank=True)
    journal = models.CharField(max_length=300, blank=True)
    volume = models.IntegerField(blank=True, null=True)
    number = models.IntegerField(blank=True, null=True)
    startpage = models.IntegerField(blank=True, null=True)
    endpage = models.IntegerField(blank=True, null=True)
    series = models.CharField(max_length=300, blank=True)
    edition = models.CharField(max_length=300, blank=True)
    sourcetype = models.CharField(
        max_length=30, choices=SOURCETYPE_CHOICES, blank=True)

    def clean(self):
        if self.published and self.published > datetime.date.today():
            raise ValidationError('The entered published date is invalid.')
        if self.startpage and self.endpage and self.startpage > self.endpage:
            raise ValidationError('The entered pagenumbers are invalid.')

    def __str__(self):
        return self.title

    def pages(self):
        return str(self.startpage) + ' - ' + str(self.endpage)

    def get_absolute_url(self):
        return '/resources/%i/' % self.id

    class Meta:
        ordering = ['-published', 'title']
        get_latest_by = 'published'
