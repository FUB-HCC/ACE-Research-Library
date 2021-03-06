# -*- coding: utf-8 -*-
# Generated by Django 1.9.7 on 2016-09-15 15:18
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('api', '0012_auto_20160702_1500'),
    ]

    operations = [
        migrations.AlterField(
            model_name='resource',
            name='accessed',
            field=models.DateField(blank=True, help_text='ISO 8601 format, e.g., 1806-05-20.', null=True, verbose_name='date accessed'),
        ),
        migrations.AlterField(
            model_name='resource',
            name='published',
            field=models.DateField(help_text='ISO 8601 format, e.g., 1946-07-06.', verbose_name='date published'),
        ),
    ]
