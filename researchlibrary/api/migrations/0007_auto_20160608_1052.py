# -*- coding: utf-8 -*-
# Generated by Django 1.9.5 on 2016-06-08 10:52
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('api', '0006_auto_20160608_1031'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='resource',
            name='authors',
        ),
        migrations.RemoveField(
            model_name='resource',
            name='editors',
        ),
        migrations.AddField(
            model_name='resource',
            name='authors',
            field=models.ManyToManyField(related_name='resources_authored', to='api.Person'),
        ),
        migrations.AddField(
            model_name='resource',
            name='editors',
            field=models.ManyToManyField(blank=True, related_name='resources_edited', to='api.Person'),
        ),
    ]