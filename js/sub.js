$(function()
{	  
  $('.load_more').live('click', function(e){
	e.preventDefault();
    var sort_val,postData,
		more_id_val = $(this).attr('id'),
	    more_array = more_id_val.split('_'),
	    first_item_val = more_array[1];
	if($('.sort_type .now').text() == "最新")
	{
	  sort_val = "time";
	}
	else
	{
	  sort_val = "popular";
	}
	postData = {from: "sub",first_item: first_item_val, sort: sort_val};
	imgloading = $("<img src='/img/loading.gif' />");
	$.ajax({
			type: 'POST',
			url: '/member/loadmorestory.php',
			data: postData, 
			beforeSend:function() 
			{
			  $('.load_more').html(imgloading);
			},
			success: function(data){
				$('.more_content').remove();
				$('.sto_cover_list').append(data).after($('.more_content').remove());
			}
			});
  })
});