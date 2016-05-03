#Includes all Dropdown-choices used in models.py
# -*- coding: utf-8 -*-

#__SOURCETYPE-CHOICES:
TEXTBOOK		= 'TEXTBOOK'
NOVEL			= 'NOVEL'
BIOGRAPHY		= 'BIOGRAPHY'
LETTER			= 'LETTER'
INTERVIEW		= 'INTERVIEW'
ACADEMIC_PAPER		= 'ACADEMIC_PAPER'
NEWSPAPER_ARTICLE	= 'NEWSPAPER_ARTICLE'
JOURNAL			= 'JOURNAL'
BOOK			= 'BOOK'
CONFERENCE_PAPER	= 'CONFERENCE_PAPER'
BLOG			= 'BLOG'
ST_DEF			= 'OTHER'

SOURCETYPE_CHOICES = (
	(TEXTBOOK, 'Textbook'),
	(NOVEL, 'Novel'),
	(BIOGRAPHY, 'Biography'),
	(LETTER, 'Letter'),
	(INTERVIEW, 'Interview'),
	(ACADEMIC_PAPER, 'Academic Paper'),
	(NEWSPAPER_ARTICLE, 'Newspaper Article'),
	(JOURNAL, 'Journal'),
	(BOOK, 'Book'),
	(CONFERENCE_PAPER, 'Conference Paper'),
	(BLOG, 'Blog'),
	(ST_DEF, 'Other'),
)

#__COUNTRY-CHOICES:
#TODO: Missing countries
SPAIN			= 'SPAIN'
GERMANY			= 'GERMANY'
FRANCE			= 'FRANCE'
USA			= 'USA'
CANADA			= 'CANADA'
RUSSIA			= 'RUSSIA'
CHINA			= 'CHINA'
C_DEF			= 'OTHER'

COUNTRY_CHOICES = (
	(SPAIN, 'Spain'),
	(GERMANY, 'Germany'),
	(FRANCE, 'France'),
	(USA, 'United States Of America'),
	(CANADA, 'Canada'),
	(RUSSIA, 'Russia'),
	(CHINA, 'China'),
	(C_DEF, 'Other'),
)

