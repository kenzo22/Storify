var embedCode;
var followPage;
var myPage;
var userSearchPage;
var weiboSearhPage = 1;
var picSearchPage = 1;
var userpicSearchPage =1;

var myPageTimestamp;
var followTimestamp;
var usersearchTimestamp;
var tweibosearchPage = 1;

var doubanItemCounts = 10;
var commentsPerQuery = 5;
var eventStartIndex = 1;
var bookStartIndex = 1;
var bookReviewStartIndex = 1;
var movieStartIndex = 1;
var movieReviewStartIndex = 1;
var musicStartIndex = 1;
var musicReviewStartIndex = 1;

var vtabIndex;

Array.prototype.getUnique = function()
{
  var o = new Object();
  var i, e;
  for (i = 0; e = this[i]; i++) {o[e] = 1};
  var a = new Array();
  for (e in o) {a.push (e)};
  return a;
} 

WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
{
  var cfg = {
              //key: '314237338',
			  key: '2417356638',
			  xdpath: 'http://story.com/storify/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
});

function prepare_story_data()
{
  var story_id_val;
  if (typeof(post_id)=="undefined" || post_id==null)
  {
	story_id_val = 0;
  }
  else
  {
	story_id_val = post_id;
  }
  
  var story_content_val = new Object;
  story_content_val.content = [];
  $('#story_list li:not(.addTextElementAnchor)').each(function(i)
  {
	story_content_val.content[i] = new Object;
	story_content_val.content[i].id = i;
	if($(this).hasClass('sina'))
	{
	  story_content_val.content[i].type = 'weibo';
	  story_content_val.content[i].content = $(this).attr('id');
	}
	else if($(this).hasClass('tencent'))
	{
	  story_content_val.content[i].type = 'tweibo';
	  story_content_val.content[i].content = $(this).attr('id');
	}
	else if($(this).hasClass('textElement'))
	{
	  story_content_val.content[i].type = 'comment';
	  story_content_val.content[i].content = $(this).find('.commentBox').html();
	}
	else if($(this).hasClass('douban'))
	{
	  debugger;
	  var doubanclass = $(this).attr('class');
	  var temp_douban = doubanclass.split(' ');
	  var temp_douban_length = temp_douban.length;
	  var j;
	  for(j=0; j<temp_douban_length; j++)
	  {
	    if(temp_douban[j]!='douban' && temp_douban[j]!='douban_drop')
		break;
	  }
	  var item_type_val;
	  item_type_val = temp_douban[j];
	  story_content_val.content[i].type = 'douban';
	  var item_per_id = $(this).attr('id');
	  var douban_metadata = {item_type: item_type_val, item_id: item_per_id};
	  story_content_val.content[i].content = douban_metadata;
	}
	else if($(this).hasClass('video_drop'))
	{
	  story_content_val.content[i].type = 'video';
	  story_content_val.content[i].content = $(this).find('.videoTitle').attr('href');
	}
	else if($(this).hasClass('pic_drop'))
	{
	  story_content_val.content[i].type = 'photo';
	  var photo_title = $(this).find('.pic_title').text();
	  var photo_author = $(this).find('.pic_author').text();
	  var photo_per_url = $(this).find('.pic_img').attr('src');
	  var photo_metadata = {title: photo_title, author: photo_author, url: photo_per_url};
	  story_content_val.content[i].content = photo_metadata;
	}
  });
  var story_content_val_string = JSON.stringify(story_content_val);
  var story_title_val = $('#sto_title').attr('value');
  var story_summary_val = $('#sto_summary').val();
  var story_tag_val = $('#sto_tag').attr('value');
  var story_pic_val = $('#story_thumbnail').attr('src');
  var storydata = {story_id: story_id_val, story_title: story_title_val, story_summary: story_summary_val, story_pic: story_pic_val, story_tag: story_tag_val, story_content: story_content_val_string};	
  return storydata;
}

function display_search()
{
  //$('.weibo_drag').remove();
  //need to compare the performance of this two remove method
  $('#source_list').children().remove();
  $('#weibo_search button').text('搜索微博');
  $('#weibo_search').css('display', 'block');
}

function display_user_search()
{
  $('#source_list').children().remove();
  $('#weibo_search button').text('搜索用户');
  $('#weibo_search').css('display', 'block');
}

function append_content(id_array, content_array)
{
  for (var i in id_array)
  {
	var $contentToAppend = $(content_array[i]);
	$("#" + id_array[i]).append($contentToAppend);
  }
}

function replaceURLWithHTMLLinks(source) {
	var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    replaced = source.replace(exp,"<a href='$1' target='_blank'>$1</a>"); 
	return replaced;
}

function append_video_content(url)
{
  $.embedly(url, {key: '4ac512dca79011e0aeec4040d3dc5c07', maxWidth: 420, wrapElement: 'div', method : 'afterParent'  }, function(oembed){        
  if (oembed != null)
  {
    var videoContent = oembed.code;
    $("a[href="+url+"]").parent().after(videoContent);
  }            
  });
}

function remove_item(event)
{
	var $temp = $(event.target || event.srcElement).closest('li');
	if($temp.index() == 1 && !$temp.hasClass('textElement'))
	{
      var story_pic_url;
	  $('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)').each(function(index)
	  {
		if(index > 0)
		{
		  if($(this).hasClass('sina'))
		  {
		    story_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/(\d+)\/50\/(\d+)/, "$1\/180\/$2");
			return false;
		  }
		  else if($(this).hasClass('tencent'))
		  {
		    story_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/50$/, "180");
			return false;
		  }
		  else if($(this).hasClass('douban'))
		  {
		    if($(this).hasClass('book') || $(this).hasClass('movie') || $(this).hasClass('music'))
			{
			  story_pic_url = $(this).find('.item_img').attr('src');
			}
			else
			{
			  story_pic_url = $(this).find('.profile_img_drop').attr('src');
			}
			return false;
		  }
		  else if($(this).hasClass('pic_drop'))
		  {
		    story_pic_url = $(this).find('.pic_img').attr('src').replace(/small$/, "square");
			return false;
		  }
		}
	  });
	  $('#story_thumbnail').attr('src', story_pic_url);
	}
	$temp.next('li').remove();
	$temp.remove();
}

function change_story_pic(direction)
{
  var item_pic_url;
  var story_pic_array = [];
  var url_array_length = story_pic_array.length;
  $('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)').each(function(index){
	if($(this).hasClass('sina'))
    {
	  item_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/(\d+)\/50\/(\d+)/, "$1\/180\/$2");
	  story_pic_array[url_array_length] = item_pic_url;
	  url_array_length++;
    }
    else if($(this).hasClass('tencent'))
    {
	  item_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/50$/, "180");
	  story_pic_array[url_array_length] = item_pic_url;
	  url_array_length++;
    }
	else if($(this).hasClass('douban'))
    {
	  if($(this).hasClass('book') || $(this).hasClass('movie') || $(this).hasClass('music'))
	  {
	    item_pic_url = $(this).find('.item_img').attr('src');
	  }
	  else
	  {
	    item_pic_url = $(this).find('.profile_img_drop').attr('src');
	  }
	  story_pic_array[url_array_length] = item_pic_url;
	  url_array_length++;
    }
    else if($(this).hasClass('pic_drop'))
    {
	  item_pic_url = $(this).find('.pic_img').attr('src').replace(/small$/, "square");
	  story_pic_array[url_array_length] = item_pic_url;
	  url_array_length++;
    }
  });
  story_pic_array = story_pic_array.getUnique();
  var current_pic_url = $('#story_thumbnail').attr('src');
  var i;
  for(i=0;i<url_array_length ;i++)
  {
	if(story_pic_array[i]===current_pic_url)
    {
	  break;
    }
  }
  if(direction == 'next')
  {
    i = i+1;
	if(i == url_array_length)
	{
	  i=0;
	}
	$('#story_thumbnail').attr('src', story_pic_array[i]);
  }
  else if(direction == 'prev')
  {
    i = i-1;
	if(i<0)
	{
	  i=url_array_length-1;
	}
	$('#story_thumbnail').attr('src', story_pic_array[i]);
  }
}

$(function() {
		$('#prev_img').click(function(e)
		{
		  e.preventDefault();
		  change_story_pic('prev');
		});
		
		$('#next_img').click(function(e)
		{
		  e.preventDefault();
		  change_story_pic('next');
		});
		
		var $weiboTabs = $( '#weiboTabs' ).tabs();
		var $doubanTabs = $( '#doubanTabs' ).tabs();
		var $picTabs = $( '#picTabs' ).tabs();
		/*var $weiboTabs = $( '#weiboTabs' ).tabs({
			ajaxOptions: {
				success: function( data, status){
				//$('.ui-tabs-panel').remove();
				$('.source_list').html(data);
				},
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			}
		});*/
		  
		$('#keywords').val('关键字').css('color', '#999999');
		
		$('#keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      var weibo_selected = $weiboTabs.tabs('option', 'selected');
			  if(weibo_selected == 0)
			  {
			    $(this).val('关键字').css('color', '#999999');
			  }
			  else if(weibo_selected == 3)
			  {
			    $(this).val('微博用户名').css('color', '#999999');
			  }
		    }
		  }).focus(function(){
		    var weibo_selected = $weiboTabs.tabs('option', 'selected');
			if(weibo_selected == 0 && $(this).val() == '关键字')
			{
			  $(this).val('').css('color', 'black');
			}
			else if(weibo_selected == 3 && $(this).val() == '微博用户名')
			{
			  $(this).val('').css('color', 'black');
			}
		  });
		  
		$('#d_keywords').val('书名').css('color', '#999999');
		
		$('#book_tab').click(function(){
		  bookStartIndex = 1;
		  $('#d_keywords').val('书名').css('color', '#999999');
		  $('#source_list').children().remove();
		});
		
		$('#movie_tab').click(function(){
		  movieStartIndex = 1;
		  $('#d_keywords').val('电影名').css('color', '#999999');
		  $('#source_list').children().remove();
		});
		
		$('#music_tab').click(function(){
		  musicStartIndex = 1;
		  $('#d_keywords').val('歌曲名').css('color', '#999999');
		  $('#source_list').children().remove();
		});
		
		$('#event_tab').click(function(e){
		   eventStartIndex = 1;
		   $('#d_keywords').val('搜活动').css('color', '#999999');
		   $('#source_list').children().remove();
		});
		
		$('#d_keywords').blur(function(){
		    if($(this).val() == '')
		    {
			  $(this).css('color', '#999999');
			  var douban_selected = $doubanTabs.tabs('option', 'selected');
			  switch(douban_selected)
			  {
			    case 0: $(this).val('书名');
				break;
				case 1: $(this).val('电影名');
				break;
				case 2: $(this).val('歌曲名');
				break;
				case 3: $(this).val('搜活动');
				break;
			  }
		    }
		  }).focus(function(){
		    var douban_selected = $doubanTabs.tabs('option', 'selected');
			var input_txt = $(this).val();
			if((douban_selected == 0 && input_txt == '书名') || (douban_selected == 1 && input_txt == '电影名') || (douban_selected == 2 && input_txt == '歌曲名') || (douban_selected == 3 && input_txt == '搜活动'))
			{
			  $(this).val('').css('color', 'black');
			}
		  });
		
		$('#pic_keywords').val('关键字').css('color', '#999999');
		
		$('#pic_keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      $(this).css('color', '#999999');
			  var yupoo_selected = $picTabs.tabs('option', 'selected');
			  if(yupoo_selected == 0)
			  {
			    $(this).val('关键字');
			  }
			  else if(yupoo_selected == 1)
			  {
			    $(this).val('又拍用户名');
			  }
		    }
		  }).focus(function(){
		    var yupoo_selected = $picTabs.tabs('option', 'selected');
			var yupoo_txt = $(this).val();
			if((yupoo_selected == 0 && yupoo_txt == '关键字') || (yupoo_selected == 1 && yupoo_txt == '又拍用户名'))
			{
			  $(this).val('').css('color', 'black');
			}
		  });
		
		$('#my_tab').click(function()
		{
		  $('.weibo_drag').remove();
		  $('.loadmore').remove();
		  $('#source_list').css('height', '722px');
		  $('#weibo_search').css('display', 'none');
		  myPage = 1;
		  myPageTimestamp = 0;
		  var getUrl;
		  var getData;
		  if(0 == vtabIndex)
		  {
		    getUrl = '../weibo/weibooperation.php';
			getData = {operation: 'my_weibo', page: myPage};
		  }
		  else
		  {
		    getUrl = '../tweibo/tweibooperation.php';
			getData = {operation: 'my_weibo', page: 0, timestamp: myPageTimestamp};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
		  }
		  });
		});
		
		$('#follow_tab').click(function()
		{
		  $('.weibo_drag').remove();
		  $('.loadmore').remove();
		  $('#source_list').css('height', '722px');
		  $('#weibo_search').css('display', 'none');
		  followPage = 1;
		  followTimestamp = 0;
		  var getUrl;
		  var getData;
		  if(0 == vtabIndex)
		  {
		    getUrl = '../weibo/weibooperation.php';
			getData = {operation: 'my_follow', page: followPage};
		  }
		  else
		  {
		    getUrl = '../tweibo/tweibooperation.php';
			getData = {operation: 'my_follow', page: 0, timestamp: followTimestamp};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
		  }
		  });
		});
		
		$('#search_tab').click(function()
		{
		  $('#source_list').css('height', '664px');
		  $('#keywords').val('关键字').css('color', '#999999');
		  weiboSearhPage = 1;
		  tweibosearchPage = 1;
		  display_search();
		});
		
		$('#user_tab').click(function()
		{
		  $('#source_list').css('height', '664px');
		  $('#keywords').val('微博用户名').css('color', '#999999');
		  userSearchPage = 1;
		  usersearchTimestamp = 0;
		  display_user_search();
		});
		
		$('#weibo_search_btn').click(function(){
		  $('.loadmore').remove();
		  var words = $('#keywords').val();
		  var type = $('#weibo_search button').text();
		  var getUrl;
		  var getData;
		  if(type === '搜索微博')
		  {
		    if(0 == vtabIndex)
		    {
		      getUrl = '../weibo/weibooperation.php';
			  getData = {operation: 'weibo_search', keywords: words, page:weiboSearhPage};
		    }
		    else
		    {
		      //need to revise according to Tencen API
			  getUrl = '../tweibo/tweibooperation.php';
			  getData = {operation: 'weibo_search', keywords: words, page:tweibosearchPage};
		    }
		  }
		  else
		  {
		    if(0 == vtabIndex)
		    {
		      getUrl = '../weibo/weibooperation.php';
			  getData = {operation: 'user_search', keywords: words, page:userSearchPage};
		    }
		    else
		    {
			  getUrl = '../tweibo/tweibooperation.php';
			  getData = {operation: 'user_search', keywords: words, page: 0, timestamp: usersearchTimestamp};
		    }	
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
		  }
		  });
		});
		
		//Douban part
		$('#douban_search_btn').click(function(){
		  var doubanSelected = $doubanTabs.tabs('option', 'selected');
		  var getUrl = '../douban/doubanoperation.php';
		  var keywords_val = $('#d_keywords').val();
		  var getData;
		  if(0 == doubanSelected)
		  {
		    getData = {operation: 'book', keywords: keywords_val, startIndex: bookStartIndex, numResults: doubanItemCounts};
		  }
		  else if(1 == doubanSelected)
		  {
		    getData = {operation: 'movie', keywords: keywords_val, startIndex: movieStartIndex, numResults: doubanItemCounts};
		  }
		  else if(2 == doubanSelected)
		  {
		    getData = {operation: 'music', keywords: keywords_val, startIndex: musicStartIndex, numResults: doubanItemCounts};
		  }
		  else if(3 == doubanSelected)
		  {
		    getData = {operation: 'event', keywords: keywords_val, startIndex: eventStartIndex, numResults: doubanItemCounts};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
		  }
		  });
		});
		
		//Yupoo part
		$('#search_tab_pic').click(function()
		{
		  picSearchPage = 1;
		  $('#source_list').children().remove();
		  $('#source_list').css('height', '615px');
		  $('#pic_keywords').val('关键字').css('color', '#999999');
		});
		
		$('#user_tab_pic').click(function()
		{
		  userpicSearchPage = 1;
		  $('#source_list').children().remove();
		  $('#source_list').css('height', '615px');
		  $('#pic_keywords').val('又拍用户名').css('color', '#999999');
		});
		
		$('#pic_search_btn').click(function()
		{
		  $('.loadmore').remove();
		  var words = $('#pic_keywords').val();
		  var selected = $picTabs.tabs('option', 'selected');
		  var getUrl = '../yupoo/yupoooperation.php';
		  var getData;
		  if(0 == selected)
		  {
		    getData = {operation: 'pic_search', keywords: words, page: picSearchPage};
		  }
		  else
		  {
		    getData = {operation: 'user_search', keywords: words, page: userpicSearchPage};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
		  }
		  });
		});
		
		$( "#source_list, #story_list" ).sortable({
			connectWith: ".connectedSortable",
			cancel: ".weibo_drop, .douban_drop, .video_drop, .textElement",
			receive: function(event, ui) 
			{
			  var commentContent = ("<li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>");
			  if(!ui.item.prev('li').hasClass('addTextElementAnchor'))
			  {
			    ui.item.before(commentContent)
			  }
			  if(!ui.item.next('li').hasClass('addTextElementAnchor'))
			  {
			    ui.item.after(commentContent)
			  }
			  var list_item_have_pic = $('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)');
			  if(ui.item.hasClass('weibo_drag'))
			  {
			    //debugger;
				var weibo_img_content = "";
				var weibo_retweet_img_content = "";
				var position = ui.position;
			　  var weibo_id = ui.item.find('.weibo_drag').attr('id');
			　  var weibo_Text= ui.item.find('.weibo_text').text();
				if(ui.item.find('.weibo_img img').length != 0)
				{
				  var weibo_img = ui.item.find('.weibo_img img').attr('src').replace(/thumbnail/,"bmiddle");
				  weibo_img_content = "<div class='weibo_img_drop'><img src='"+weibo_img+"' /></div>";
				}
				if(ui.item.find('.weibo_retweet_img img').length != 0)
				{
				  var weibo_retweet_img = ui.item.find('.weibo_retweet_img img').attr('src').replace(/thumbnail/,"bmiddle");
				  weibo_retweet_img_content = "<div class='weibo_retweet_img_drop'><img src='"+weibo_retweet_img+"' /></div>";
				}
			　  var weibo_from = ui.item.find('.weibo_from').text();
			　  var weibo_from_id = ui.item.find('.user_page').attr('href').replace(/http:\/\/weibo.com\//,"");
			  　var weibo_time = ui.item.find('.create_time').text();
			　  var weibo_photo = ui.item.find('.profile_img').attr('src');
				var content;	
			    if(ui.item.hasClass('sina'))
				{
				  ui.item.removeClass('weibo_drag').addClass('weibo_drop sina').children().remove();
				  content = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"
					+weibo_Text+"</span>"+weibo_retweet_img_content+weibo_img_content+"</div><div id='story_signature'><span style='float:right;'><a href='http://weibo.com/"+weibo_from_id+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><span id='signature_text_drop' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
					+weibo_from_id+"' target='_blank'>"+weibo_from+"</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span> <img border='0' style='position:relative; top:2px' src='/storify/img/sina16.png'/><a>"
					+weibo_time+"</a></span></div></span> </div></div>");
				  if(ui.item.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', weibo_photo.replace(/(\d+)\/50\/(\d+)/, "$1\/180\/$2"));
				  }
				}
				else
				{
				  ui.item.removeClass('weibo_drag').addClass('weibo_drop tencent').children().remove();
				  content = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"
					+weibo_Text+"</span>"+weibo_retweet_img_content+weibo_img_content+"</div><div id='story_signature'><span style='float:right;'><a href='http://weibo.com/"+weibo_from_id+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><span id='signature_text_drop' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
					+weibo_from_id+"' target='_blank'>"+weibo_from+"</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span> <img border='0' style='position:relative; top:2px' src='/storify/img/tencent16.png'/><a>"
					+weibo_time+"</a></span></div></span> </div></div>");
				  if(ui.item.index(list_item_have_pic) == 0)
				  {
					$('#story_thumbnail').attr('src', weibo_photo.replace(/50$/, "180"));
				  }
				}
				ui.item.append(content);	
			    WB.widget.atWhere.searchAndAt(document.getElementById("story_list"));
			  }
			  else if(ui.item.hasClass('douban_drag'))
			  {
				var doubanContent = "";
				var douban_profile_img = ui.item.find('.profile_img').attr('src');
				var douban_profile_name = ui.item.find('.profile_img').attr('title');
				var douban_profile_url = ui.item.find('.douban_from').attr('href');
				if(ui.item.hasClass('event'))
				{
				  var event_title = ui.item.find('.event_title a').text();
				  var event_summary = ui.item.find('.event_summary').text();
				  var event_initiator_name = ui.item.find('.event_initiator a').text();
				  var event_initiator_url = ui.item.find('.event_initiator a').attr('href');
				  var event_start_time = ui.item.find('.start_time').text();
				  var event_end_time = ui.item.find('.end_time').text();
				  var event_link = ui.item.find('.event_title a').attr('href');
				  var event_pic = ui.item.find('.event_img_wrapper img').attr('src');
				  var event_location = ui.item.find('.event_location').text();
				  var event_city = ui.item.find('.event_city').text();
				  doubanContent=("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='douban_wrapper'><div class='event_summary_drop'>"+event_summary+"</div><div style='margin-top:10px; overflow:auto;'><a href='"
				  +event_link+"' target='_blank'><img class='item_img_drop' src='"+event_pic+"' style='float:left;' /></a><div class='item_meta_drop' style='margin-left:220px;'><div class='event_title_drop'>活动：<a href='"
				  +event_link+"' target='_blank'>"+event_title+"</a></div><div class='event_initiator_drop'>发起人：<a href='"+event_initiator_url+"' target='_blank'>"
				  +event_initiator_name+"</a></div><div class='start_time_drop'>"+event_start_time+"</div><div class='end_time_drop'>"+event_end_time+"</div><div class='event_city_drop'>"
				  +event_city+"</div><div class='event_location_drop'>"+event_location+"</div></div></div><div id='douban_signature'><span style='float:right;'><a href='"+douban_profile_url+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+douban_profile_img+"' alt='"+douban_profile_name+"' border=0 /></a></span><span class='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='douban_from_drop' href='"
					+douban_profile_url+"' target='_blank'>"+douban_profile_name+"</a></span></div><div class='douban_date_drop'  style='text-align:right; height:16px;'><span><img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='/storify/img/logo_douban.png'/></span></div></span> </div></div>");
				  
				  ui.item.removeClass('douban_drag').addClass('douban_drop').children().remove();
				  if(ui.item.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', douban_profile_img);
				  }　
			      ui.item.append(doubanContent);
				}
				else if(ui.item.hasClass('bookReviews') || ui.item.hasClass('movieReviews') || ui.item.hasClass('musicReviews'))
				{
				  var douban_per_url = ui.item.find('.item_title').attr('href');
				  var douban_comment_title = ui.item.find('.comment_title').text();
				  var douban_comment_summary = ui.item.find('.comment_summary').text();
				  var douban_comment_date = ui.item.find('.comment_date').text();
				  var douban_comment_rating = ui.item.find('.item_rating').text();
				  var douban_comment_url = ui.item.find('.comment_full_url').attr('href');
				  var douban_item_img = ui.item.find('.item_img').attr('src');
				  var douban_item_title = ui.item.find('.item_title').text();
				  var douban_item_author = ui.item.find('.item_author').text();
				  var douban_item_date = ui.item.find('.item_date').text();
				  var douban_average_rating = ui.item.find('.average_rating').text();
				  var douban_item_rating = ui.item.find('.item_rating').text();
				  doubanContent = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='douban_wrapper'><div><div class=item_rating_drop>"+douban_item_rating+"</div><div class='comment_title_drop' style='font-weight:bold;'>"
					+douban_comment_title+"</div><div class='comment_summary_drop'>"+douban_comment_summary+"</div><div style='text-align:right;'><a href='"+douban_comment_url+"' target='_blank'>查看评论全文</a></div></div><div class='item_info_drop' style='overflow:auto;'><a href='"+douban_per_url+"' target='_blank'><img class='item_img_drop' src='"
				  +douban_item_img+"' style='float:left;' /></a><div class='item_meta_drop' style='margin-left:100px;'><div><a class='item_title_drop' href='"+douban_per_url+"' target='_blank'>"+douban_item_title+"</a></div><div class='item_author_drop'>"
				  +douban_item_author+"</div><div class='item_date_drop'>"+douban_item_date+"</div><div class='average_rating_drop'>"+douban_average_rating+"</div></div></div><div id='douban_signature'><span style='float:right;'><a href='"+douban_profile_url+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+douban_profile_img+"' alt='"+douban_profile_name+"' border=0 /></a></span><span class='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='douban_from_drop' href='"
					+douban_profile_url+"' target='_blank'>"+douban_profile_name+"</a></span></div><div class='douban_date_drop'  style='text-align:right; height:16px;'><span> <img border='0' style='position:relative; top:2px; width:16px; height:16px;' src='/storify/img/logo_douban.png'/><a>"
					+douban_comment_date+"</a></span></div></span> </div></div>");
				  ui.item.removeClass('douban_drag').addClass('douban_drop').children().remove();
				  if(ui.item.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', douban_profile_img);
				  }　
			      ui.item.append(doubanContent);
				}
				else
				{
				  if(ui.item.index(list_item_have_pic) == 0)
				  {
					$('#story_thumbnail').attr('src', ui.item.find('.item_img').attr('src'));
				  }　
				  ui.item.removeClass('douban_drag').addClass('douban_drop');
				  ui.item.find('.douban_review').closest('div').remove();
				  ui.item.prepend("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div>");
				}
			  }
			  else if(ui.item.hasClass('video_Drag'))
			  {
			    //var thumbnailUrl = ui.item.find('.youku_thumbnail').attr('src');
				var videoUrl = ui.item.find('.videoTitle').attr('href');
				var videoTitle = ui.item.find('.videoTitle').text();
				var videoEmbedCode;
				var videoContent = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div><a class='videoTitle' target='_blank' href='"
				+videoUrl+"'>"+videoTitle+"</a></div>"+embedCode);
				ui.item.removeClass('video_Drag').addClass('video_drop').children().remove();　
			    ui.item.append(videoContent);
				/*if(ui.item.index() == 1)
				{
				  $('#story_thumbnail').attr('src', thumbnailUrl);
				}*/
			  }
			  else if(ui.item.hasClass('pic_Drag'))
			  {
				var picUrl = ui.item.find('img').attr('src');
				var picTitle = ui.item.find('.pic_title').text();
				var picAuthor = ui.item.find('.pic_author').text();
				var temp_array = picUrl.split("\/");
				var temp_array_length = temp_array.length;
				temp_array[temp_array_length-1] = "small";
				picUrl = temp_array.join("\/");
				
				var picContent = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div style='margin:0px auto; text-align:center; border: 5px solid #FFFFFF; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4); max-width: 260px;'><img class='pic_img' src='"
				+picUrl+"'/><div class='pic_title' style='line-height:1.5;'>"+picTitle+"</div><div class='pic_author' style='line-height:1.5;'>"+picAuthor+"</div></div>");
				ui.item.removeClass('pic_Drag').addClass('pic_drop').children().remove();　
			    ui.item.append(picContent);
				if(ui.item.index(list_item_have_pic) == 0)
				{
				  $('#story_thumbnail').attr('src', picUrl.replace(/small$/, "square"));
				  //$('#story_thumbnail').attr('src', picUrl);
				}
			  }
			}
		});/*.disableSelection();*/
		
		$('#embedVideo').click(function(e)
		{
		  var imgpath = '../img/loading.gif';
		  var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		  $('#source_list').html(imgloading);
		  
		  var videoTitle;
		  var videoUrl = $('#videoUrl').val();
		  $.embedly(videoUrl, {key: '4ac512dca79011e0aeec4040d3dc5c07', maxWidth: 420, wrapElement: 'div', method : "afterParent"  }, function(oembed){				
          if (oembed != null)
		  {
			embedCode = oembed.code;
			videoTitle = oembed.title;
			var post = "<li class='video_Drag'><div class='urlWrapper'><div><a class='videoTitle' target='_blank' href='"+videoUrl+"'>"+oembed.title+
			"</a></div><div class='videoContent'><div class='video_domain'><div class='video_favicon' style='display:inline; position:relative; top:4px'><img src='/storify/img/youku.ico'/></div><div class='video_author' style='display:inline; margin-left:3px;'><a target='_blank' href='"
			+videoUrl+"'>v.youku.com</a></div></div><div><img class='youku_thumbnail' src='"+oembed.thumbnail_url+"' style='float:left; margin-right:5px; border: 1px solid #E9E9E9; padding:3px;'/><div class='video_description' style='line-height:1.5;'>"+oembed.description+"</div></div></div></div></li>";
			$('#source_list').html(post);  
		  }		  			
          });
		})
		
		if($('#sto_title').val() =='')
		{
		  $('#sto_title').val('写下你的故事标题吧(这将会是你故事的链接地址)').css('color', 'black').focus(function(){
		  if($(this).val() == '写下你的故事标题吧(这将会是你故事的链接地址)')
		  {
		    $(this).val('').css('color', 'black');
		  }
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('写下你的故事标题吧(这将会是你故事的链接地址)').css('color', 'black');
		  }
		  });
		}
		
		
		if($('#sto_summary').val() =='')
		{
		  $('#sto_summary').val('给你的故事写一个简短的描述').css('color', '#999999').focus(function(){
		  if($(this).val() == '给你的故事写一个简短的描述')
		  {
		    $(this).val('').css('color', 'black');
		  }		  
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('给你的故事写一个简短的描述').css('color', '#999999');
		  }
		  });
		}

		if($('#sto_tag').val() =='')
		{
		  $('#sto_tag').val('添加故事标签').css('color', '#999999').focus(function(){
		  if($(this).val() == '添加故事标签')
		  {
		    $(this).val('').css('color', 'black');
		  }		  
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('添加故事标签').css('color', '#999999');
		  }
		  });
		}	
		
		/*$('#story_list').hover(function(e){
		if ($(e.target).is('.weibo_drop'))
		{
		  //$('.cross').css('visibility', 'hidden');
		  $(e.target).children('.cross').css('visibility', 'visible');
		}
		},
		function(ev)
		{
		  if ($(ev.target).is('.weibo_drop'))
		  {
		    //$('.cross').css('visibility', 'hidden');
			$(ev.target).children('.cross').css('visibility', 'hidden');
		  }
		});*/
		
		
		/*$('#story_list').mouseover(function(e)
		{
		  if ($(e.target).is('li'))
		  {
		    $(e.target).find('.cross').css('visibility', 'visible');
		  }
		});
		
		$('#story_list').mouseout(function(e)
		{
		  if ($(e.target).is('li'))
		  {
		    $(e.target).find('.cross').css('visibility', 'hidden');
		  }
		});*/
		$('#actions').click(function(e)
		{
		  e.preventDefault();
		  var postdata = prepare_story_data();
		  var posturl;
		  if($(e.target).is('#publishBtn'))
		  {
		    posturl = 'publish.php';
		  }
		  else if($(e.target).is('#previewBtn'))
		  {
		    posturl = 'preview.php';
		  }
		  else
		  {
		    posturl = 'draft.php';
		  }
		  $.post(posturl, postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
		});
		
		//douban reviews part
		$('.douban_review').live('click', function(e){
		  e.preventDefault();
		  var getUrl = '../douban/doubanreviewsoperation.php';
		  var getData;
		  var itemSubjectId = $(this).closest('.douban_drag').attr('id');
		  if($(this).hasClass('book'))
		  {
		    getData = {operation: 'bookReviews', subjectID: itemSubjectId, startIndex: bookReviewStartIndex, numResults: commentsPerQuery};
		  }
		  else if($(this).hasClass('movie'))
		  {
		    getData = {operation: 'movieReviews', subjectID: itemSubjectId, startIndex: movieReviewStartIndex, numResults: commentsPerQuery};
		  }
		  else if($(this).hasClass('music'))
		  {
		    getData = {operation: 'musicReviews', subjectID: itemSubjectId, startIndex: musicReviewStartIndex, numResults: commentsPerQuery};
		  } 
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgpath = '../img/loading.gif';
		    var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
		  }
		  }); 
		});
		
		$('#source_list').click(function(e)
		{
		  var selected;
		  if(0 == vtabIndex || 1 == vtabIndex)
		  {
		    selected = $weiboTabs.tabs('option', 'selected'); 
		  }
		  else if(2 == vtabIndex)
		  {
		    selected = $doubanTabs.tabs('option', 'selected'); 
		  }
		  else if(4 == vtabIndex)
		  {
		    selected = $picTabs.tabs('option', 'selected'); 
		  }
		  if ($(e.target).is('.loadmore a'))
		  {
			var getUrl;
			var getData;
			if(0 == selected)
			{
			  var words;
			  if(0 == vtabIndex)
		      {
		        words = $('#keywords').val();
				getUrl = '../weibo/weibooperation.php';
				weiboSearhPage++;
				getData = {operation: 'weibo_search', keywords: words, page: weiboSearhPage};
		      }
		      else if(1 == vtabIndex)
		      {
		        words = $('#keywords').val();
				getUrl = '../tweibo/tweibooperation.php';
				//weibosearchTimestamp = $('.loadmore span').attr('id');
				tweibosearchPage++;
				getData = {operation: 'weibo_search', keywords: words, page: tweibosearchPage}; 
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('book'))
				{
				  getUrl = '../douban/doubanoperation.php';
				  bookStartIndex = bookStartIndex+doubanItemCounts;
				  getData = {operation: 'book', keywords: $('#d_keywords').val(), startIndex: bookStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('bookReviews'))
				{
				  getUrl = '../douban/doubanreviewsoperation.php';
				  bookReviewStartIndex = bookReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'bookReviews', subjectID: loadMoreItem.attr('id'), startIndex: bookReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = '../yupoo/yupoooperation.php';
				picSearchPage++;
				getData = {operation: 'pic_search', keywords: words, page: picSearchPage};
			  }
			  $('.loadmore').remove();
			  //add weibo search function
			  
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
			  });
			}
			else if(1 == selected)
			{
			  if(0 == vtabIndex)
		      {
		        getUrl = '../weibo/weibooperation.php';
				myPage++;
				getData = {operation: 'my_weibo', page: myPage}
		      }
		      else if(1 == vtabIndex)
		      {
		        getUrl = '../tweibo/tweibooperation.php';
				myPageTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'my_weibo', page: 1, timestamp: myPageTimestamp}; 
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('movie'))
				{
				  getUrl = '../douban/doubanoperation.php';
				  movieStartIndex = movieStartIndex+doubanItemCounts;
				  getData = {operation: 'movie', keywords: $('#d_keywords').val(), startIndex: movieStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('movieReviews'))
				{
				  getUrl = '../douban/doubanreviewsoperation.php';
				  movieReviewStartIndex = movieReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'movieReviews', subjectID: loadMoreItem.attr('id'), startIndex: movieReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = '../yupoo/yupoooperation.php';
				userpicSearchPage++;
				getData = {operation: 'user_search', keywords: words, page: userpicSearchPage};
			  }
			  $('.loadmore').remove();					  
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
			  });
			}
			else if(2 == selected)
			{
			  if(0 == vtabIndex)
		      {
		        getUrl = '../weibo/weibooperation.php';
				followPage++;
				getData = {operation: 'my_follow', page: followPage}
		      }
		      else if(1 == vtabIndex)
		      {
		        getUrl = '../tweibo/tweibooperation.php';
				followTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'my_follow', page: 1, timestamp: followTimestamp};
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('music'))
				{
				  getUrl = '../douban/doubanoperation.php';
				  musicStartIndex = musicStartIndex+doubanItemCounts;
				  getData = {operation: 'music', keywords: $('#d_keywords').val(), startIndex: musicStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('musicReviews'))
				{
				  getUrl = '../douban/doubanreviewsoperation.php';
				  musicReviewStartIndex = musicReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'musicReviews', subjectID: loadMoreItem.attr('id'), startIndex: musicReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  $('.loadmore').remove();
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
			  });
			}
			else
			{
			  //add user search function
			  //var words = $('#keywords').val();
			  if(0 == vtabIndex)
		      {
		        getUrl = '../weibo/weibooperation.php';
				userSearchPage++;
				getData = {operation: 'user_search', keywords: $('#keywords').val(), page:userSearchPage};
		      }
		      else if(1 == vtabIndex)
		      {
		        getUrl = '../tweibo/tweibooperation.php';
				usersearchTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'user_search', keywords: $('#keywords').val(), page: 1, timestamp: usersearchTimestamp};
		      }
			  else if(2 == vtabIndex)
		      {
		        getUrl = '../douban/doubanoperation.php';
				eventStartIndex = eventStartIndex+doubanItemCounts;
				getData = {operation: 'event', keywords: $('#d_keywords').val(), startIndex: eventStartIndex, numResults: doubanItemCounts};
		      }
			  $('.loadmore').remove();
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
			  });
			}
		  }
		});
		
		$('#story_list').click(function(e)
		{
		  if ($(e.target).is('.add_comment'))
		  {
		    var $comment_box = $("<li class='textElement editing'><div class='editingDiv'><form class='formTextElement'><textarea class='inputEditor' name='inputEditor'></textarea></form><div class='belowTextEdit'><div class='actions' style='padding-left:338px;'><button class='cancel small cancelEditor' type='reset'>Cancel</button><button class='submit small blue submitComment' type='submit'>Done</button></div></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>");
		    $(e.target).closest('li').after($comment_box);
			$(".inputEditor").cleditor({
			width:455,
			height:150,
			controls:"bold italic underline strikethrough link | font size",
			
			});
			//$(e.target).closest('#input').cleditor();
		  }
		  
		  if($(e.target).is('.cancelEditor'))
		  {
			$(e.target).closest('.textElement').next('.addTextElementAnchor').remove();
			$(e.target).closest('.textElement').remove();
		  }
		  
		  if($(e.target).is('.submitComment'))
		  {
			var $textElement = $(e.target).closest('.textElement');
			var comment = $textElement.find('.inputEditor').val();
			if(comment == '')
			{
			  $(e.target).closest('.textElement').next('.addTextElementAnchor').remove();
			  $(e.target).closest('.textElement').remove();
			}
			else
			{
			  $(e.target).closest('.editingDiv').remove();
			  var $commentDiv = $("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='commentBox'>"+comment+"</div>");
			  $textElement.removeClass('editing').addClass('editted').append($commentDiv);
			}
		  }
		});				
		
		var $items = $('#vtab>ul>li');
		var selVTab = 0;
        $items.click(function() {
        $items.removeClass('selected');
        $(this).addClass('selected');
        vtabIndex = $items.index($(this));
		if(1 == vtabIndex)
		{
		  $('#my_tab').text('我的广播');
		  $('#follow_tab').text('我的收听');
		  if(1 != selVTab)
		  {
		    $weiboTabs.tabs( "select" , 0 );
		    $('#weibo_search').css('display', 'block');
			$('#source_list').css('height', '665px').children().remove();
		  }
		  selVTab = 1;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(2 == vtabIndex)
		{
		  if(2 != selVTab)
		  {
		    $('#source_list').css('height', '665px').children().remove();
		  } 
		  selVTab = 2;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(3 == vtabIndex)
		{
		  if(3 != selVTab)
		  {
		    $('#source_list').css('height', '665px').children().remove();
		  } 
		  selVTab = 3;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(4 == vtabIndex)
		{
		  if(4 != selVTab)
		  {
		    $('#source_list').css('height', '665px').children().remove();
		  } 
		  selVTab = 4;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
        else
		{
		  $('#my_tab').text('我的微博');
		  $('#follow_tab').text('我的关注');
		  if(0 != selVTab)
		  {
		    $weiboTabs.tabs( "select" , 0 );
		    $('#weibo_search').css('display', 'block');
			$('#source_list').css('height', '665px').children().remove();
		  }
		  selVTab = 0;
		  $('#vtab>div').hide().eq(vtabIndex).show();
		}
        }).eq(0).click();
	  
	});