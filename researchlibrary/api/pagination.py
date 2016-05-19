from rest_framework import viewsets, response, pagination

class ResourcePagination(pagination.PageNumberPagination):
    page_size = 10
    page_size_query_param = 'len'
    pax_page_size = 1000
    def get_paginated_response(self, data):
        return response.Response({
            'links': {
                'next': self.get_next_link(),
                'previous': self.get_previous_link()
            },
            'count': self.page.paginator.count,
            'results': data
        })

