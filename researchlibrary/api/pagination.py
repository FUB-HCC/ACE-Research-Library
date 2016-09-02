"""Acerl API pagination rules."""

from rest_framework import pagination


class ResourcePagination(pagination.PageNumberPagination):
    """
    Suitable pagination rules for resourcse-related search results.

    In particular we override the default that would not allow
    the caller to change the page size. The additional flexibility
    further decouples frontend and backend development.
    """

    page_size = 10
    page_size_query_param = 'len'
    max_page_size = 1000
