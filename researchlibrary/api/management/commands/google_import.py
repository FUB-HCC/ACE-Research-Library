import datetime
import logging
import re
import gspread
from dateutil.parser import parse as parse_date
from oauth2client.service_account import ServiceAccountCredentials
from django.core import management
from django.db.models import Q
from ...models import Author, Resource, Category, Keyword
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
        author_names, editor_names, date, publisher, title, subtitle, url, resource_type, \
            keyword_names, abstract, review, fulltext_url, category, discussion, journal, volume, \
            number, startpage, endpage, series, edition, source_type = row[:23]
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
        review += '\n\nDiscussion: {}'.format(discussion)
        date = parse_date(date)
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
            date=date,
            accessed=accessed,
            publisher=publisher.strip(),
            title=title.strip(),
            subtitle=subtitle.strip(),
            url=url.strip(),
            resource_type=resource_type,
            abstract=abstract.strip(),
            review=review.strip(),
            journal=journal.strip(),
            volume=int(volume.strip()),
            number=int(number.strip()),
            startpage=int(startpage.strip()),
            endpage=int(endpage.strip()),
            series=series.strip(),
            edition=edition.strip(),
            source_type=source_type.strip())
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
            if row[0] == 'Title and Hyperlink':
                continue
            self.process(row)
