$(function(){

	$('#search').keyup(function() {

		$.ajax({
			type: "GET",
			url: "/api/v1/suggest/",
			data: {
				'q' : $('#search').val(),
				'csrfmiddlewaretoken' : $("input[name=csrfmiddlewaretoken]").val()
			},
			success: searchSuccess,
			dataType: 'html'
		});
	});
});

function searchSuccess(data, textStatus, jqXHR)
{
	$('#search-results').html(data);
}
