import json
from django.conf import settings
from django.forms import Media
from django.utils.safestring import mark_safe
from django_select2.forms import ModelSelect2TagWidget
from ..models import Person, Keyword


class ModelSelect2TagWidgetBase(ModelSelect2TagWidget):

    def get_url(self):
        """
        Get noting at all.

        Django Select2 abuses the cache to inject autosuggest values into the
        admin view via AJAX. That’s terrible. Since we don’t use that view,
        this method that references it needs to be overwritten.
        """
        pass

    def build_attrs(self, extra_attrs=None, **kwargs):
        """
        Remove the data attributes for the AJAX call we don’t use and
        customize a bit more.
        """
        attrs = super().build_attrs(extra_attrs=extra_attrs, **kwargs)
        # Originally:
        # {'data-token-separators': '[",", " "]',
        #  'data-minimum-input-length': 1,
        #  'data-tags': 'true',
        #  'data-ajax--type': 'GET',
        #  'data-ajax--cache': 'true',
        #  'data-ajax--url': '/select2/fields/auto.json',
        #  'class': 'django-select2 django-select2-heavy',
        #  'name': 'authors',
        #  'id': 'id_authors',
        #  'data-field_id': 'MTQwNTg5NTg3NzM3NTEy:1bD5p3:suZhZBqbvSzNpEMGve-KGjfmffw',
        #  'data-allow-clear': 'false'}
        attrs['data-token-separators'] = r'[","]'
        attrs['data-model'] = self.model._meta.model_name
        del attrs['data-ajax--url']
        del attrs['data-ajax--type']
        del attrs['data-ajax--cache']
        return attrs

    def render(self, name, value, attrs=None, choices=()):
        output = super().render(name, value, attrs, choices)
        # Let’s think of something new if and when the page reaches 1+ MiB.
        output += """
            <p class="help">Complete entries by hitting enter or comma.</p>
            <script type="text/javascript">
                var select = $('#%s');
                select.data('entries', %s);
                initSelect2(select);
                select.on('select2:select', register);
            </script>\n
        """ % (
            attrs['id'],
            json.dumps([
                {'id': obj.pk, 'text': getattr(obj, self.field)}
                for obj in self.model.objects.all()]))
        return mark_safe(output)

    @property
    def media(self):
        """
        Construct Media as a dynamic property.
        .. Note:: For more information visit
            https://docs.djangoproject.com/en/1.8/topics/forms/media/#media-as-a-dynamic-property
        """
        return Media(
            js=(settings.SELECT2_JS,),
            css={'screen': (settings.SELECT2_CSS,)})


class PersonModelSelect2TagWidget(ModelSelect2TagWidgetBase):
    model = Person
    field = 'name'


class KeywordModelSelect2TagWidget(ModelSelect2TagWidgetBase):
    model = Keyword
    field = 'name'

    def render(self, name, value, attrs=None, choices=()):
        output = super().render(name, value, attrs, choices)
        output += '<span id="keyword_suggestions">Suggestions: </span>'
        return mark_safe(output)
