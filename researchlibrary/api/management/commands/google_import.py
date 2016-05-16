import datetime
import logging
import re
import gspread
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
        # Parsing the citation seems harder than fixing the 100 entries manually.
        anonymous_author, _ = Author.objects.get_or_create(name='Anonymous')
        title, url, resource_type, keyword_names, review, citation, fulltext_url, \
        category, _, discussion = row[:10]
        if Resource.objects.filter(Q(title=title) | Q(url=url)).exists():
            logger.info('Skipping existing entry %r', title)
            return
        category, _ = Category.objects.get_or_create(name=category.strip())
        keywords = set()
        for keyword_name in keyword_names.split(','):
            keyword_name = keyword_name.strip()
            keyword, _ = Keyword.objects.get_or_create(name=keyword_name)
            keywords.add(keyword)
        review += '\n\nCitation: {}\n\nDiscussion: {}'.format(citation, discussion)
        year = re.search(r'\((\d{4})\)', citation)
        if year:
            date = '{}-01-01'.format(year.group(1))
        else:
            date = datetime.date.today()
        resource_type = {
            'Academic Paper': models_choices.STUDY,
            'Academic Paper (Unpublished)': models_choices.STUDY,
            'Blogpost': models_choices.BLOG_ARTICLE,
            'Blog Post': models_choices.BLOG_ARTICLE,
            'Blogpost/ Think tank piece': models_choices.BLOG_ARTICLE,
            'Book': models_choices.BOOK,
            'Historical Document': models_choices.HISTORICAL_DOCUMENT,
            'Industry Publication': models_choices.RESEARCH_SUMMARY,
            'Newspaper opinion piece': models_choices.OPINION_PIECE,
            'Research Summary': models_choices.RESEARCH_SUMMARY,
            'Wikipedia Entry': models_choices.ENCYCLOPEDIA_ARTICLE,
            '': models_choices.OTHER}[resource_type]
        resource = Resource(
            title=title.strip(),
            date=date,
            url=url.strip(),
            review=review.strip(),
            resource_type=resource_type)
        resource.save()
        resource.keywords = keywords
        resource.authors = [anonymous_author]
        resource.categories = [category]
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
