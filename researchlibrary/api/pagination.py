from rest_framework import pagination


class ResourcePagination(pagination.PageNumberPagination):

    page_size = 10
    page_size_query_param = 'len'
    max_page_size = 1000
