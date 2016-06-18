import json
from django import template
from django.utils.safestring import mark_safe

register = template.Library()


@register.filter('json')
def json_filter(value, indent=None):
    return mark_safe(json.dumps(value, indent=indent))
