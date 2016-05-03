import datetime

from django.db import models
from django.core.exceptions import ValidationError
from .models_choices import SOURCETYPE_CHOICES, ST_DEF, COUNTRY_CHOICES, C_DEF


class Publisher(models.Model):
	name		= models.CharField(max_length=30, unique=True)
	adress		= models.CharField(max_length=50, blank=True)
	city		= models.CharField(max_length=50, blank=True)
	state_providence= models.CharField(max_length=50, blank=True)
	country		= models.CharField(max_length=50, choices=COUNTRY_CHOICES, default=C_DEF, blank=True)
	website		= models.URLField(blank=True)
	def __str__(self): return self.name
	class Meta:
		ordering = ['name', 'country']

class Editor(models.Model):
	name		= models.CharField(max_length=50, blank=True)
	def __str__(self): return self.name
	class Meta:
		ordering = ['name']

class Author(models.Model):
	firstname	= models.CharField(max_length=30)
	lastname	= models.CharField(max_length=30)
	biography	= models.TextField(max_length=300, blank=True)
	email		= models.EmailField(blank=True)
	def __str__(self): return (self.firstname + ' ' + self.lastname)
	class Meta:
		ordering = ['lastname', 'firstname']

class Category(models.Model):
	name		= models.CharField(max_length=50)
	def __str__(self): return self.name
	class Meta:
		ordering = ['name']

class Resource(models.Model):
	#mandatory fields:
	authors		= models.ManyToManyField(Author)
	title 		= models.CharField(max_length=50, unique=True)
	date 		= models.DateField('date published')
	#other:
	resource_file	= models.FileField(upload_to=None, max_length=100, blank=True)
	url		= models.URLField(max_length=100, blank=True) 
	categories	= models.ManyToManyField(Category, blank=True)
	editors		= models.ManyToManyField(Editor, blank=True)
	publisher	= models.ForeignKey(Publisher, blank=True, null=True)
	subtitle 	= models.CharField(max_length=50, blank=True)
	abstract	= models.TextField(max_length=300, blank=True)
	journal		= models.CharField(max_length=30, blank=True)
	volume		= models.IntegerField(blank=True, null=True)
	number		= models.IntegerField(blank=True, null=True)
	startpage	= models.IntegerField(blank=True, null=True)
	endpage		= models.IntegerField(blank=True, null=True)
	series		= models.CharField(max_length=30, blank=True)
	edition		= models.CharField(max_length=30, blank=True)
	sourcetype	= models.CharField(max_length=30, choices=SOURCETYPE_CHOICES, default=ST_DEF, blank=True)
	#methods:
	def clean(self):
		if self.date and self.date > datetime.date.today():
			raise ValidationError("The entered date is invalid.")
		if self.startpage and self.endpage and self.startpage > self.endpage:
			raise ValidationError("The entered pagenumbers are invalid.")
	def save(self, *args, **kwargs):
		super(Resource, self).save(*args, **kwargs)
		if self.editors.count() <= 0 and self.publisher:
			pass #TODO(?): Add publisher to editor
	def __str__(self): return self.title
	def pages(self): return str(self.startpage) + ' ' + str(self.endpage)
	def was_published_recently(self): return self.date >= datetime.date.today() - datetime.timedelta(days=30)
	def get_absolute_url(self): return "/resources/%i/" % self.id
	#Template to get URL to individual objects on frontend:
	#<a href="{{ object.get_absolute_url }}">{{ object.title }}</a>
	#Meta:
	class Meta:
		ordering = ['-date', 'title']
		get_latest_by = 'date'



