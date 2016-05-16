"""Includes all Dropdown-choices used in models.py"""


# Format choices

NEWS_ARTICLE = 'NEWS_ARTICLE'
BLOG_ARTICLE = 'BLOG_ARTICLE'
OPINION_PIECE = 'OPINION_PIECE'
HISTORICAL_DOCUMENT = 'HISTORICAL_DOCUMENT'
ENCYCLOPEDIA_ARTICLE = 'ENCYCLOPEDIA_ARTICLE'
STUDY = 'STUDY'
CASESTUDY = 'CASESTUDY'
QUASI_EXPERIMENT = 'QUASI_EXPERIMENT'
RCT = 'RCT'
RESEARCH_SUMMARY = 'RESEARCH_SUMMARY'
METASTUDY = 'METASTUDY'
SYSTEMATIC_REVIEW = 'SYSTEMATIC_REVIEW'
BOOK = 'BOOK'
OTHER = 'OTHER'

RESOURCE_TYPE_CHOICES = (
    (STUDY, 'Misc. object-level study'),
    (CASESTUDY, 'Case study'),
	(QUASI_EXPERIMENT, 'Quasi-experiment'),
    (RCT, 'Randomized controlled trial'),
	(RESEARCH_SUMMARY, 'Research summary (informal)'),
    (METASTUDY, 'Metastudy (informal)'),
    (SYSTEMATIC_REVIEW, 'Systematic review'),
	(OPINION_PIECE, 'Opinion piece'),
	(HISTORICAL_DOCUMENT, 'Historical document'),
	(ENCYCLOPEDIA_ARTICLE, 'Encyclopedia article'),
    (BOOK, 'Book'),
    (NEWS_ARTICLE, 'Misc. news article'),
    (BLOG_ARTICLE, 'Misc. blog article'),
    (OTHER, 'Other'),
)


# SourceType choices

NEWSPAPER = 'NEWSPAPER'
JOURNAL = 'JOURNAL'
BOOK = 'BOOK'
CONFERENCE = 'CONFERENCE'
BLOG = 'BLOG'

SOURCETYPE_CHOICES = (
    (NEWSPAPER, 'Newspaper'),
    (JOURNAL, 'Journal'),
    (BOOK, 'Book'),
    (CONFERENCE, 'Conference'),
    (BLOG, 'Blog'),
    (OTHER, 'Other'),
)
