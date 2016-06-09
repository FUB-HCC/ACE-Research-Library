import datetime
import logging
import re
import gspread
from dateutil.parser import parse as parse_date
from oauth2client.service_account import ServiceAccountCredentials
from django.core import management
from django.db.models import Q
from ...models import Person, Resource, Category, Keyword
from ... import models_choices

SHEET = 'Sheet1'


logger = logging.getLogger(__name__)


class Command(management.base.BaseCommand):
    help = 'Imports the spreadsheet but leaves post-processing to humans'

    def add_arguments(self, parser):
        parser.add_argument('credentials', help='JSON file with credentials')
        parser.add_argument('id', help=('ID of Google Spreadsheet '
                                        '(you may have to create a copy and share it with the'
                                        ' email address from the credentials)'))

    def process(self, row):
        _, author_names, editor_names, published, publisher, title, subtitle, url, resource_type, \
            keyword_names, abstract, review, fulltext_url, category, discussion, journal, volume, \
            number, startpage, endpage, series, edition, sourcetype = row[:23]
        if Resource.objects.filter(url=url).exists():
            logger.info('Skipping existing entry %r', title)
            return
        authors = [Person.objects.get_or_create(name=author_name.strip())[0]
                   for author_name in author_names.split(',')]
        editors = [Person.objects.get_or_create(name=author_name.strip())[0]
                   for author_name in author_names.split(',')]
        keywords = [Keyword.objects.get_or_create(name=keyword_name.strip())[0]
                    for keyword_name in keyword_names.split(',')]
        categories = Category.objects.get_or_create(name=category.strip())[:1]
        if discussion.strip():
            review += '\n\nDiscussion: {}'.format(discussion)
        published = parse_date(published)
        accessed = parse_date('2015-11-03')
        resource_type = {
            'Academic Paper': models_choices.STUDY,
            'Academic Paper (Unpublished)': models_choices.STUDY,
            'Blog Post': models_choices.BLOG_ARTICLE,
            'Book': models_choices.BOOK,
            'Historical Document': models_choices.HISTORICAL_DOCUMENT,
            'Industry Publication': models_choices.RESEARCH_SUMMARY,
            'Newspaper opinion piece': models_choices.OPINION_PIECE,
            'Research Summary': models_choices.RESEARCH_SUMMARY,
            'Wikipedia Entry': models_choices.ENCYCLOPEDIA_ARTICLE,
            '': models_choices.OTHER}[resource_type]
        resource = Resource(
            published=published,
            accessed=accessed,
            publisher=publisher.strip(),
            title=title.strip(),
            subtitle=subtitle.strip(),
            url=url.strip(),
            resource_type=resource_type,
            abstract=abstract.strip(),
            review=review.strip(),
            journal=journal.strip(),
            volume=int(volume.strip()) if volume.strip() else None,
            number=int(number.strip()) if number.strip() else None,
            startpage=int(startpage.strip()) if startpage.strip() else None,
            endpage=int(endpage.strip()) if endpage.strip() else None,
            series=series.strip(),
            edition=edition.strip(),
            sourcetype=sourcetype.strip())
        resource.save()
        resource.authors = authors
        resource.editors = editors
        resource.keywords = keywords
        resource.categories = categories
        resource.save()

    def handle(self, *args, **options):
        scope = ['https://spreadsheets.google.com/feeds']
        credentials = ServiceAccountCredentials.from_json_keyfile_name(
            options['credentials'], scope)
        gc = gspread.authorize(credentials)
        doc = gc.open_by_key(options['id'])
        worksheet = doc.worksheet(SHEET).get_all_values()
        for row in worksheet:
            if row[1] == 'Author':
                continue
            if row[1] == '':
                break
            self.process(row)
