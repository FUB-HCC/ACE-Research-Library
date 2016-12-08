import re
from haystack.forms import SearchForm
from stop_words import get_stop_words
from readability.readability import Document
from utilofies.stdlib import cached_property
import string

class Gist:

    keyword_pattern = re.compile(r'^[^\d]+$')
    stop_words = set(get_stop_words('en'))

    def __init__(self, html):
        self.html = html
        self.document = Document(html)

    @property
    def title(self):
        return self.document.short_title()

    @cached_property
    def text(self):
        text = self.document.summary()
        text = re.sub('<br[^>]+>', '\n', text)
        text = re.sub('</?p[^>]+>', '\n\n', text)
        text = re.sub('<[^>]+>', '', text)
        text = re.sub('^[ \t]+$', '', text)
        text = re.sub('\n{3,}', '\n\n', text, flags=re.MULTILINE)
        return text

    @staticmethod
    def _common_prefix(one, two):
        parallelity = [x == y for x, y in zip(one, two)] + [False]
        return parallelity.index(False)

    @classmethod
    def _find_representative(cls, stem, text):
        tokens = text.split()
        prefixes = {token: cls._common_prefix(token, stem) for token in tokens}
        best = lambda token: (-token[1], len(token[0]))
        return sorted(prefixes.items(), key=best)[0][0]

    @classmethod
    def _is_good_keyword(cls, word):
        return (word not in cls.stop_words) and \
                cls.keyword_pattern.match(word)

    @classmethod
    def find_keywords(cls, text):
        whoosh_backend = SearchForm().searchqueryset.query.backend
        if not whoosh_backend.setup_complete:
            whoosh_backend.setup()
        with whoosh_backend.index.searcher() as searcher:
            keywords = searcher.key_terms_from_text(
                'text', text, numterms=10, normalize=False)
        keywords = list(zip(*keywords))[0] if keywords else []
        keywords = [cls._find_representative(keyword, text) for keyword in keywords]
        keywords = [keyword for keyword in keywords if cls._is_good_keyword(keyword)]
        #no double keywords in list
        keywords = list(set(keywords))
        #no punctuation in suggested keywords
        keywords = [''.join(c for c in s if c not in string.punctuation) for s in keywords]
        return keywords

    @property
    def keywords(self):
        return self.find_keywords(self.text)
