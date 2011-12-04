var embedCode, vtabIndex, followPage, myPage, userSearchPage, tuserSearchPage, myPageTimestamp, followTimestamp, usersearchTimestamp;
var weiboSearhPage = 1, picSearchPage = 1, userpicSearchPage =1, colSearchPage = 1, recSearchPage = 1, tweibosearchPage = 1, doubanItemCounts = 10, commentsPerQuery = 5, eventStartIndex = 1, bookStartIndex = 1, bookReviewStartIndex = 1, movieStartIndex = 1, movieReviewStartIndex = 1, musicStartIndex = 1, musicReviewStartIndex = 1, weibo_url = '/weibo/weibooperation.php', tweibo_url = '/tweibo/tweibooperation.php', douban_url = '/douban/doubanoperation.php', yupoo_url = '/yupoo/yupoooperation.php';

if( typeof( window.innerHeight ) == 'number' ){
//Non-IE
myHeight = window.innerHeight;
} else if( document.documentElement && document.documentElement.clientHeight) {
//IE 6+ in 'standards compliant mode'
myHeight = document.documentElement.clientHeight;
}
var l_used_height = 267, r_user_height = 326, height_adjust = 3, l_list_height = myHeight -l_used_height, r_list_height;

var browser_info = $.browser;

if (browser_info.mozilla )
{
  r_list_height = myHeight - r_user_height;
}
else if (browser_info.webkit) 
{
  r_list_height = myHeight - r_user_height-height_adjust;
}
else if (browser_info.msie) 
{
  r_list_height = myHeight - r_user_height+height_adjust;
}
$('#source_list').css('height', l_list_height);
$('#story_list').css('min-height', r_list_height);   


Array.prototype.getUnique = function()
{
  var o = {}, i, e;
  for (i=0; e=this[i]; i++) {o[e]=1};
  var a=new Array();
  for (e in o)
  {a.push (e)};
  return a;
}

String.prototype.len=function()
{
  return this.replace(/[^\x00-\xff]/g,"**").length;
}

function bindonbeforeunload()
{
  window.onbeforeunload=popalert;
}

function unbindonbeforeunload()
{
  window.onbeforeunload=null;
}

function popalert()
{
  return"本页面要求您确认您要离开 - 您输入的数据可能不会被保存";
}

function show_weibo_card(id)
{
  WB2.anyWhere(function(W){
	W.widget.hoverCard({
		id: id,
		search: true
		}); 
	});
} 

function prepare_story_data(action_value)
{
  if(action_value !='Publish' &&  action_value !='Preview' && action_value != "Draft")
    alert("not a proper operation:"+action_value);
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
  $('#story_list li:not(.addTextElementAnchor, .textElement.editing)').each(function(i)
  {
	story_content_val.content[i] = {};
	story_content_val.content[i].id = i;
	if($(this).hasClass('sina'))
	{
	  story_content_val.content[i].type = 'weibo';
	  var wid = $(this).attr('id').substr(2);
	  var wnic = $(this).find('.weibo_from_drop').text();
	  var wuid = $(this).find('.weibo_from_drop').attr('href').replace(/http:\/\/weibo.com\//,"");
	  var weibo_metadata = {id: wid, nic: wnic, uid: wuid};
	  story_content_val.content[i].content = weibo_metadata;
	}
	else if($(this).hasClass('tencent'))
	{
	  story_content_val.content[i].type = 'tweibo';
	  var tid = $(this).attr('id').substr(2);
	  var tnic = $(this).find('.weibo_from_drop').text();
	  var tname = $(this).find('.weibo_from_drop').attr('href').replace(/http:\/\/t.qq.com\//,"");
	  var tweibo_metadata = {id: tid, nic: tnic, name: tname};
	  story_content_val.content[i].content = tweibo_metadata;
	}
	else if($(this).hasClass('textElement'))
	{
	  story_content_val.content[i].type = 'comment';
	  story_content_val.content[i].content = $(this).find('.commentBox').html();
	}
	else if($(this).hasClass('douban'))
	{
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
	  var item_per_id = $(this).attr('id').substr(2);
	  var douban_metadata = {item_type: item_type_val, item_id: item_per_id};
	  story_content_val.content[i].content = douban_metadata;
	}
	else if($(this).hasClass('video_drop'))
	{
	  story_content_val.content[i].type = 'video';
	  var video_title = $(this).find('.videoTitle').text();
	  var video_src = $(this).find('embed').attr('src');
	  var video_url = $(this).find('.videoTitle').attr('href');
	  var video_meta = {title: video_title, src: video_src, url: video_url};
	  story_content_val.content[i].content = video_meta;
	}
	else if($(this).hasClass('pic_drop'))
	{
	  story_content_val.content[i].type = 'photo';
	  var photo_title = $(this).find('.pic_title').text();
	  var photo_author = $(this).find('.pic_author').attr('href').replace(/http:\/\/www.yupoo.com\/photos\//,"");
	  var author_nic =  $(this).find('.pic_author').text();
	  var pic_link = $(this).find('.pic_title').attr('href');
	  var temp_y = pic_link.split('/');
	  var temp_y_len = temp_y.length;
	  var yid = temp_y[temp_y_len-1];
	  var photo_per_url = $(this).find('.pic_img').attr('src');
	  var photo_metadata = {id: yid, title: photo_title, author: photo_author, nic: author_nic, url: photo_per_url};
	  story_content_val.content[i].content = photo_metadata;
	}
  });
  var story_content_val_string = JSON.stringify(story_content_val);
  var story_title_val = $('#sto_title').attr('value');
  var summary_txt = $('#sto_summary').val();
  var story_summary_val = (summary_txt == '给你的故事写一个简短的描述'? '': summary_txt);
  var tag_txt = $('#sto_tag').attr('value');
  var story_tag_val = (tag_txt == '添加故事标签，空格或逗号分隔'? '': tag_txt);
  var story_pic_val = $('#story_thumbnail').attr('src');
  var storydata = {story_id: story_id_val, story_title: story_title_val, story_summary: story_summary_val, story_pic: story_pic_val, story_tag: story_tag_val, story_content: story_content_val_string, action:action_value};	
  return storydata;
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

function remove_item(evt)
{
	var $temp = $(evt.target || evt.srcElement).closest('li');
	if($temp.index() == 1 && !$temp.hasClass('textElement') && !$temp.hasClass('video_drop'))
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
	if($('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)').size() == 0)
	{
	  $('#story_thumbnail').css('background-color', '#EFEFEF').attr('src', '');
	}
}

function change_story_pic(direction)
{
  var item_pic_url, story_pic_array = [], url_array_length = story_pic_array.length;
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
  var unique_array_length = story_pic_array.length;
  var current_pic_url = $('#story_thumbnail').attr('src');
  var i;
  for(i=0;i<unique_array_length ;i++)
  {
	if(story_pic_array[i]===current_pic_url)
    {
	  break;
    }
  }
  if(i == unique_array_length)
  {
    i=0;
  }
  else
  {
    if(direction == 'next')
    {
      i = i+1;
	  if(i == unique_array_length)
	  {
	    i=0;
	  }
    }
    else if(direction == 'prev')
    {
      i = i-1;
	  if(i<0)
	  {
	    i=unique_array_length-1;
	  }
    }
  }
  $('#story_thumbnail').attr('src', story_pic_array[i]);
}

$(function() {				
		var $weiboTabs = $( '#weiboTabs' ).tabs();
		var $doubanTabs = $( '#doubanTabs' ).tabs();
		var $picTabs = $( '#picTabs' ).tabs();
		
		$('#keywords, #d_keywords, #videoUrl, #pic_keywords').bind('keyup', function(e)
		{
		  var code = e.keyCode || e.which; 
		  if(code == 13)
		  {
			$(this).next().click();
		  }
		});
		
		$('#connectBtn').live('click', function(e)
	    {
		  e.preventDefault();
		  $.post('/accounts/login/sina_auth.php', {}, 		
		  function(data, textStatus)
		  {
		    $('#dialog.window').hide();
		    self.location=data;
		  });
	    });

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
		
		$('.cross').live('click', function(e){
		  e.preventDefault();
		  remove_item(e);
		})
		
		show_weibo_card('story_list');
		
		var tag_txt = $('#sto_tag').val();
		if(tag_txt == ' ')
		{
		  $('#sto_tag').val('添加故事标签，空格或逗号分隔').addClass('imply_color');
		}
		  
		$('#keywords').val('关键字').addClass('imply_color');
		
		$('#keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      var weibo_selected = $weiboTabs.tabs('option', 'selected');
			  if(weibo_selected == 0)
			  {
			    $(this).val('关键字').addClass('imply_color');
			  }
			  else if(weibo_selected == 3)
			  {
			    $(this).val('微博用户名').addClass('imply_color');
			  }
		    }
		  }).focus(function(){
		    var weibo_selected = $weiboTabs.tabs('option', 'selected');
			if((weibo_selected == 0 && $(this).val() == '关键字') || (weibo_selected == 3 && $(this).val() == '微博用户名'))
			{
			  $(this).val('').removeClass('imply_color');
			}
		  });
		  
		$('#d_keywords').val('书名').addClass('imply_color');
		
		$('#videoUrl').val('浏览器地址栏url').addClass('imply_color');
		
		$('#videoUrl').blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('浏览器地址栏url').addClass('imply_color');
		  }
		}).focus(function(){
		  if($(this).val() == '浏览器地址栏url')
		  {
		    $(this).val('').removeClass('imply_color');
		  }
		});
		
		$('#book_tab').click(function(){
		  bookStartIndex = 1;
		  $('#d_keywords').val('书名').addClass('imply_color');
		  $('#source_list').children().remove();
		});
		
		$('#movie_tab').click(function(){
		  movieStartIndex = 1;
		  $('#d_keywords').val('电影名').addClass('imply_color');
		  $('#source_list').children().remove();
		});
		
		$('#music_tab').click(function(){
		  musicStartIndex = 1;
		  $('#d_keywords').val('歌曲名').addClass('imply_color');
		  $('#source_list').children().remove();
		});
		
		$('#event_tab').click(function(e){
		   eventStartIndex = 1;
		   $('#d_keywords').val('搜活动').addClass('imply_color');
		   $('#source_list').children().remove();
		});
		
		$('#d_keywords').blur(function(){
		    if($(this).val() == '')
		    {
			  $(this).addClass('imply_color');
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
			  $(this).val('').removeClass('imply_color');
			}
		  });
		
		$('#pic_keywords').val('关键字').addClass('imply_color');
		
		$('#pic_keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      $(this).addClass('imply_color');
			  var yupoo_selected = $picTabs.tabs('option', 'selected');
			  if(yupoo_selected == 0)
			  {
			    $(this).val('关键字');
			  }
			  else if(yupoo_selected == 1 || yupoo_selected == 2)
			  {
			    $(this).val('又拍用户名，注意不是昵称');
			  }
			  else
			  {
			    $(this).val('可指定日期如2010-6,默认搜索全部');
			  }
		    }
		  }).focus(function(){
		    var yupoo_selected = $picTabs.tabs('option', 'selected');
			var yupoo_txt = $(this).val();
			if((yupoo_selected == 0 && yupoo_txt == '关键字') || (yupoo_selected == 1 && yupoo_txt == '又拍用户名，注意不是昵称') || (yupoo_selected == 2 && yupoo_txt == '又拍用户名，注意不是昵称') || (yupoo_selected == 3 && yupoo_txt == '可指定日期如2010-6,默认搜索全部'))
			{
			  $(this).val('').removeClass('imply_color');
			}
		  });
		
		$('#my_tab').click(function()
		{
		  $('.weibo_drag').remove();
		  $('.loadmore').remove();
		  $('#weibo_search').addClass('none');
		  myPage = 1;
		  myPageTimestamp = 0;
		  
		  if(0 == vtabIndex)
		  {
		    var sinaFlag = true;
		  }
		  if(sinaFlag)
		  {
		    if($(this).hasClass('sina_disable'))
			{
			  var imply_txt = "<div class='bind_txt'><div class='imply_color'>查看我的关注需要绑定新浪微博帐号</div><a href='/accounts/source'>马上绑定</a></div>";
			  $('#source_list').html(imply_txt);
			  return false;
			}
		  }
		  else
		  {
		    if($(this).hasClass('tencent_disable'))
			{
			  var imply_txt = "<div class='bind_txt'><div class='imply_color'>查看我的广播需要绑定腾讯微博帐号</div><a href='/accounts/source'>马上绑定</a></div>";
			  $('#source_list').html(imply_txt);
			  return false;
			}
		  }
		  
		  var getUrl, getData;
		  if(sinaFlag)
		  {
		    getUrl = weibo_url;
			getData = {operation: 'my_weibo', page: myPage};
		  }
		  else
		  {
		    getUrl = tweibo_url;
			getData = {operation: 'my_weibo', page: 0, timestamp: myPageTimestamp};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			if(0 == vtabIndex)
			{
			  show_weibo_card('source_list');
			}
		  }
		  });
		});
		
		$('#follow_tab').click(function()
		{
		  $('.weibo_drag').remove();
		  $('.loadmore').remove();
		  $('#weibo_search').addClass('none');
		  followPage = 1;
		  followTimestamp = 0;
		  
		  if(0 == vtabIndex)
		  {
		    var sinaFlag = true;
		  }
		  if(sinaFlag)
		  {
		    if($(this).hasClass('sina_disable'))
			{
			  var imply_txt = "<div class='bind_txt'><div class='imply_color'>查看我的关注需要绑定新浪微博帐号</div><a href='/accounts/source'>马上绑定</a></div>";
			  $('#source_list').html(imply_txt);
			  return false;
			}
		  }
		  else
		  {
		    if($(this).hasClass('tencent_disable'))
			{
			  var imply_txt = "<div class='bind_txt'><div class='imply_color'>查看我的收听需要绑定腾讯微博帐号</div><a href='/accounts/source'>马上绑定</a></div>";
			  $('#source_list').html(imply_txt);
			  return false;
			}
		  }
		  
		  var getUrl, getData;
		  if(sinaFlag)
		  {
		    getUrl = weibo_url;
			getData = {operation: 'my_follow', page: followPage};
		  }
		  else
		  {
		    getUrl = tweibo_url;
			getData = {operation: 'my_follow', page: 0, timestamp: followTimestamp};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
			var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			if(0 == vtabIndex)
			{
			  show_weibo_card('source_list');
			}
		  }
		  });
		});
		
		$('#search_tab').click(function(e)
		{
		  weiboSearhPage = 1;
		  tweibosearchPage = 1;
		  $('#keywords').val('关键字').addClass('imply_color');
		  $('#source_list').children().remove();
		  if(0 == vtabIndex)
		  {
		    $('#weibo_search_btn').text('搜索话题');
			e.preventDefault();
		    var getUrl = weibo_url;
		    var getData;
		    getData = {operation: 'list_ht'};
		  
		    $.ajax({
		    type: 'GET',
		    url: getUrl,
		    data: getData, 
		    beforeSend:function() 
		    {
		      var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		      $('#source_list').html(imgloading);
		    },
		    success: function(data)
		    {
			  $('#source_list').html(data);
		    }
		    }); 
		  }
		  else
		  {
		    $('#weibo_search_btn').text('搜索微博');
		  }
		  $('#weibo_search').addClass('imply_color').removeClass('none');
		});
		
		$('#user_tab').click(function()
		{
		  userSearchPage = 1;
		  tuserSearchPage = 1;
		  //usersearchTimestamp = 0;
		  $('#keywords').val('微博用户名').addClass('imply_color');
		  $('#source_list').children().remove();
		  $('#weibo_search_btn').text('搜索用户');
		  $('#weibo_search').addClass('imply_color').removeClass('none');
		});
		
		$('#weibo_search_btn').click(function(e){
		  e.preventDefault();
		  weiboSearhPage = 1;
		  userSearchPage = 1;
		  tuserSearchPage = 1;
		  tweibosearchPage = 1;
		  $('.loadmore').remove();
		  var words = $('#keywords').val();
		  var type = $('#weibo_search_btn').text();
		  var getUrl;
		  var getData;
		  if(type === '搜索用户')
		  {
		    if(0 == vtabIndex)
		    {
		      getUrl = weibo_url;
			  getData = {operation: 'user_search', keywords: words, page:userSearchPage};
		    }
		    else
		    {
			  getUrl = tweibo_url;
			  getData = {operation: 'list_user', keywords: words, page: tuserSearchPage};
		    }	
		  }
		  else
		  {
			if(0 == vtabIndex)
		    {
		      getUrl = weibo_url;
			  getData = {operation: 'weibo_search', keywords: words, page:weiboSearhPage};
		    }
		    else
		    {
		      //need to revise according to Tencen API
			  getUrl = tweibo_url;
			  getData = {operation: 'weibo_search', keywords: words, page:tweibosearchPage};
		    }	
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			if(0 == vtabIndex)
			{
			  show_weibo_card('source_list');
			}
		  }
		  });
		});
		
		//Douban part
		$('#douban_search_btn').click(function(e){
		  e.preventDefault();
		  var doubanSelected = $doubanTabs.tabs('option', 'selected');
		  var getUrl = douban_url;
		  var keywords_val = $('#d_keywords').val();
		  var getData;
		  switch(doubanSelected)
		  {
		    case 0: getData = {operation: 'book', keywords: keywords_val, startIndex: bookStartIndex, numResults: doubanItemCounts};
			break;
			case 1: getData = {operation: 'movie', keywords: keywords_val, startIndex: movieStartIndex, numResults: doubanItemCounts};
			break;
			case 2: getData = {operation: 'music', keywords: keywords_val, startIndex: musicStartIndex, numResults: doubanItemCounts};
			break;
			case 3: getData = {operation: 'event', keywords: keywords_val, startIndex: eventStartIndex, numResults: doubanItemCounts};
			break;
			default:
			break;
		  }		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
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
		  $('#pic_keywords').val('关键字').addClass('imply_color');
		});
		
		$('#user_tab_pic').click(function()
		{
		  userpicSearchPage = 1;
		  $('#source_list').children().remove();
		  $('#pic_keywords').val('又拍用户名，注意不是昵称').addClass('imply_color');
		});
		
		$('#collect_tab_pic').click(function()
		{
		  colSearchPage = 1;
		  $('#source_list').children().remove();
		  $('#pic_keywords').val('又拍用户名，注意不是昵称').addClass('imply_color');
		});
		
		$('#recom_tab_pic').click(function()
		{
		  recSearchPage = 1;
		  $('#source_list').children().remove();
		  $('#pic_keywords').val('可指定日期如2010-6,默认搜索全部').addClass('imply_color');
		});
		
		$('#pic_search_btn').click(function(e)
		{
		  e.preventDefault();
		  $('.loadmore').remove();
		  var words = $('#pic_keywords').val();
		  var selected = $picTabs.tabs('option', 'selected');
		  var getUrl = yupoo_url;
		  var getData;
		  if(0 == selected)
		  {
		    getData = {operation: 'pic_search', keywords: words, page: picSearchPage};
		  }
		  else if(1 == selected)
		  {
		    if($('#user_tab_pic').hasClass('yupoo_disable'))
			{
			  var imply_txt = "<div class='bind_txt'><div class='imply_color'>用户搜索功能需要绑定又拍帐号</div><a href='/accounts/source'>马上绑定</a></div>";
			  $('#source_list').html(imply_txt);
			  return false;
			}
			getData = {operation: 'user_search', keywords: words, page: userpicSearchPage};
		  }
		  else if(2 == selected)
		  {
		    getData = {operation: 'col_search', keywords: words, page: colSearchPage};
		  }
		  else if(3 == selected)
		  {
		    getData = {operation: 'rec_search', keywords: words, page: recSearchPage};
		  }
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
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
			cancel: ".weibo_drop, .douban_drop, .video_drop, .textElement, .tuser, .loadmore, .ht_wrapper",
			receive: function(evt, ui) 
			{
			  var dragItem = ui.item;
			  var commentContent = ("<li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>");
			  var prev_li = dragItem.prev('li');
			  var next_li = dragItem.next('li');
			  if(!prev_li.hasClass('addTextElementAnchor'))
			  {
			    dragItem.before(commentContent);
			  }
			  if(!next_li.hasClass('addTextElementAnchor'))
			  {
			    dragItem.after(commentContent);
			  }
			  var list_item_have_pic = $('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)');
			  if(dragItem.hasClass('weibo_drag'))
			  {
				var weibo_img_content = "";
				var weibo_retweet_img_content = "";
				var weibo_from_url = dragItem.find('.user_page').attr('href');
			　  var weibo_Text= dragItem.find('.weibo_text').html();
			    //var repost_flag = dragItem.find('.weibo_text').hasClass('is_repost');
			　  var weibo_from = dragItem.find('.weibo_from').text();
			　  //var weibo_from_id = dragItem.find('.user_page').attr('href').replace(/http:\/\/weibo.com\//,"");
			  　var weibo_time = dragItem.find('.create_time').text();
			　  var weibo_photo = dragItem.find('.profile_img').attr('src');
				var content;	
				if(dragItem.hasClass('sina'))
				{
				  if(dragItem.find('.weibo_img img').length != 0)
				  {
				    var weibo_img = dragItem.find('.weibo_img img').attr('src').replace(/thumbnail/,"bmiddle");
				    weibo_img_content = "<div class='weibo_img_drop'><img src='"+weibo_img+"' /></div>";
				  }
				  if(dragItem.find('.weibo_retweet_img img').length != 0)
				  {
				    var weibo_retweet_img = dragItem.find('.weibo_retweet_img img').attr('src').replace(/thumbnail/,"bmiddle");
				    weibo_retweet_img_content = "<div class='weibo_retweet_img_drop'><img src='"+weibo_retweet_img+"' /></div>";
				  }
				  dragItem.removeClass('weibo_drag').addClass('weibo_drop sina').children().remove();
				  content = ("<div class='cross' action='delete'></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>"
					+weibo_Text+"</span>"+weibo_retweet_img_content+weibo_img_content+"</div><div class='story_signature'><span class='float_r'><a href='"+weibo_from_url+"' target='_blank'><img class='profile_img_drop' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><div class='signature_text_drop'><div class='text_wrapper'><span><a class='weibo_from_drop' href='"
					+weibo_from_url+"' target='_blank'>"+weibo_from+"</a></span></div><div class='weibo_date_drop'>"+weibo_time+"</div></div></div></div>");
				  if(dragItem.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', weibo_photo.replace(/(\d+)\/50\/(\d+)/, "$1\/180\/$2"));
				  }
				}
				else
				{
				  if(dragItem.find('.weibo_img img').length != 0)
				  {
				    var weibo_img = dragItem.find('.weibo_img img').attr('src').replace(/120$/,"240");
				    weibo_img_content = "<div class='weibo_img_drop'><img src='"+weibo_img+"' /></div>";
				  }
				  if(dragItem.find('.weibo_retweet_img img').length != 0)
				  {
				    var weibo_retweet_img = dragItem.find('.weibo_retweet_img img').attr('src').replace(/120$/,"240");
				    weibo_retweet_img_content = "<div class='weibo_retweet_img_drop'><img src='"+weibo_retweet_img+"' /></div>";
				  }
				  dragItem.removeClass('weibo_drag').addClass('weibo_drop tencent').children().remove();
				  content = ("<div class='cross' action='delete'></div><div class='handle'></div><div class='story_wrapper'><div class='content_wrapper'><span class='weibo_text_drop'>"
					+weibo_Text+"</span>"+weibo_retweet_img_content+weibo_img_content+"</div><div class='story_signature'><span class='float_r'><a href='"+weibo_from_url+"' target='_blank'><img class='profile_img_drop' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><div class='signature_text_drop'><div class='text_wrapper'><span ><a class='weibo_from_drop' href='"
					+weibo_from_url+"' target='_blank'>"+weibo_from+"</a></span></div><div class='weibo_date_drop'>"+weibo_time+"</div></div></div></div>");
				  if(dragItem.index(list_item_have_pic) == 0)
				  {
					$('#story_thumbnail').attr('src', weibo_photo.replace(/50$/, "180"));
				  }
				}
				dragItem.append(content);
				if(0 == vtabIndex)
				{
				  show_weibo_card(dragItem.attr('id'));
				}
			  }
			  else if(dragItem.hasClass('douban_drag'))
			  {
				var doubanContent = "";
				var douban_profile_img = dragItem.find('.profile_img').attr('src');
				var douban_profile_name = dragItem.find('.profile_img').attr('title');
				var douban_profile_url = dragItem.find('.douban_from').attr('href');
				if(dragItem.hasClass('event'))
				{
				  var event_title = dragItem.find('.event_title a').text();
				  var event_summary = dragItem.find('.event_summary').text();
				  var event_initiator_name = dragItem.find('.event_initiator a').text();
				  var event_initiator_url = dragItem.find('.event_initiator a').attr('href');
				  var event_start_time = dragItem.find('.start_time').text();
				  var event_end_time = dragItem.find('.end_time').text();
				  var event_link = dragItem.find('.event_title a').attr('href');
				  var event_pic = dragItem.find('.event_img_wrapper img').attr('src');
				  var event_location = dragItem.find('.event_location').text();
				  var event_city = dragItem.find('.event_city').text();
				  doubanContent=("<div class='cross' action='delete'></div><div class='handle'></div><div class='douban_wrapper'><div class='content_wrapper'><div class='event_summary_drop'>"+event_summary+"</div><div class='event_wrapper'><a href='"
				  +event_link+"' target='_blank'><img class='item_img_drop float_l' src='"+event_pic+"' /></a><div class='item_meta_drop'><div class='event_title_drop'>活动：<a href='"
				  +event_link+"' target='_blank'>"+event_title+"</a></div><div class='event_initiator_drop'>发起人：<a href='"+event_initiator_url+"' target='_blank'>"
				  +event_initiator_name+"</a></div><div class='start_time_drop'>"+event_start_time+"</div><div class='end_time_drop'>"+event_end_time+"</div><div class='event_city_drop'>"
				  +event_city+"</div><div class='event_location_drop'>"+event_location+"</div></div></div></div><div class='douban_signature'><span class='float_r'><a href='"+douban_profile_url+"' target='_blank'><img class='profile_img_drop' src='"
					+douban_profile_img+"' alt='"+douban_profile_name+"' border=0 /></a></span><span class='signature_text_drop'><div class='text_wrapper'><span ><a class='douban_from_drop' href='"
					+douban_profile_url+"' target='_blank'>"+douban_profile_name+"</a></span></div><div class='douban_date_drop'></div></span> </div></div>");
				  
				  dragItem.removeClass('douban_drag').addClass('douban_drop').children().remove();
				  if(dragItem.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', douban_profile_img);
				  }　
			      dragItem.append(doubanContent);
				}
				else if(dragItem.hasClass('bookReviews') || dragItem.hasClass('movieReviews') || dragItem.hasClass('musicReviews'))
				{
				  var douban_per_url = dragItem.find('.item_title').attr('href');
				  var douban_comment_title = dragItem.find('.comment_title').text();
				  var douban_comment_summary = dragItem.find('.comment_summary').html();
				  var douban_comment_date = dragItem.find('.comment_date').text();
				  var douban_item_img = dragItem.find('.item_img').attr('src');
				  var douban_item_title = dragItem.find('.item_title').text();
				  var douban_item_author = dragItem.find('.item_author').text();
				  var douban_item_date = dragItem.find('.item_date').text();
				  var douban_average_rating = dragItem.find('.average_rating').text();
				  var douban_item_rating = dragItem.find('.item_rating').text();
				  doubanContent = ("<div class='cross' action='delete'></div><div class='handle'></div><div class='douban_wrapper'><div class='content_wrapper'><div><div class='comment_title_drop'>"
					+douban_comment_title+"</div><div class='comment_summary_drop'>"+douban_comment_summary+"</div></div><div class='item_info_drop'><a href='"+douban_per_url+"' target='_blank'><img class='item_img_drop float_l' src='"
				  +douban_item_img+"' /></a><div class='item_meta_drop'><div><a class='item_title_drop' href='"+douban_per_url+"' target='_blank'>"+douban_item_title+"</a></div><div class='item_author_drop'>"
				  +douban_item_author+"</div><div class='item_date_drop'>"+douban_item_date+"</div><div class=item_rating_drop>"+douban_item_rating+"</div><div class='average_rating_drop'>"+douban_average_rating+"</div></div></div></div><div class='douban_signature'><span class='float_r'><a href='"+douban_profile_url+"' target='_blank'><img class='profile_img_drop' src='"
					+douban_profile_img+"' alt='"+douban_profile_name+"' border=0 /></a></span><span class='signature_text_drop'><div class='text_wrapper'><span ><a class='douban_from_drop' href='"
					+douban_profile_url+"' target='_blank'>"+douban_profile_name+"</a></span></div><div class='douban_date_drop'>"+douban_comment_date+"</div></span> </div></div>");
				  dragItem.removeClass('douban_drag').addClass('douban_drop').children().remove();
				  if(dragItem.index(list_item_have_pic) == 0)
				  {
				    $('#story_thumbnail').attr('src', douban_profile_img);
				  }　
			      dragItem.append(doubanContent);
				}
				else
				{
				  if(dragItem.index(list_item_have_pic) == 0)
				  {
					$('#story_thumbnail').attr('src', dragItem.find('.item_img').attr('src'));
				  }　
				  dragItem.removeClass('douban_drag').addClass('douban_drop');
				  dragItem.find('.douban_flag').removeClass().addClass('content_wrapper');
				  dragItem.find('.douban_review').closest('div').remove();
				  dragItem.prepend("<div class='cross' action='delete'></div><div class='handle'></div>");
				}
			  }
			  else if(dragItem.hasClass('video_drag'))
			  {
			    //var thumbnailUrl = dragItem.find('.youku_thumbnail').attr('src');
				var videoUrl = dragItem.find('.videoTitle').attr('href');
				var videoTitle = dragItem.find('.videoTitle').text();
				var videoContent = ("<div class='cross' action='delete'></div><div class='handle'></div><div class='youku_wrapper'><div><a class='videoTitle' target='_blank' href='"
				+videoUrl+"'>"+videoTitle+"</a></div>"+embedCode+"</div>");
				dragItem.removeClass('video_drag').addClass('video_drop').children().remove();　
			    dragItem.append(videoContent);
				/*if(dragItem.index() == 1)
				{
				  $('#story_thumbnail').attr('src', thumbnailUrl);
				}*/
			  }
			  else if(dragItem.hasClass('pic_drag'))
			  {
				var picUrl = dragItem.find('img').attr('src');
				var picTitle = dragItem.find('.pic_title').text();
				var picLink = dragItem.find('.pic_title').attr('href');
				var picAuthor = dragItem.find('.pic_author').text();
				var authorLink = dragItem.find('.pic_author').attr('href');
				var temp_array = picUrl.split("\/");
				var temp_array_length = temp_array.length;
				temp_array[temp_array_length-1] = "small";
				picUrl = temp_array.join("\/");
				
				var picContent = ("<div class='cross' action='delete'></div><div class='handle'></div><div class='yupoo_wrapper'><a target='_blank' href='"+picLink+"'><img class='pic_img' src='"
				+picUrl+"'/></a><div><a class='pic_title' target='_blank' href='"+picLink+"'>"+picTitle+"</a></div><div><a class='pic_author' target='_blank' href='"+authorLink+"'>"+picAuthor+"</a></div><div class='yupoo_sign'></div></div>");
				dragItem.removeClass('pic_drag').addClass('pic_drop').children().remove();　
			    dragItem.append(picContent);
				if(dragItem.index(list_item_have_pic) == 0)
				{
				  $('#story_thumbnail').attr('src', picUrl.replace(/small$/, "square"));
				  //$('#story_thumbnail').attr('src', picUrl);
				}
			  }
			}
		});/*.disableSelection();*/
		
		$('#embedVideo').click(function(e)
		{
		  e.preventDefault();
		  var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		  $('#source_list').html(imgloading);
		  
		  var videoTitle;
		  var videoUrl = $('#videoUrl').val();
		  $.embedly(videoUrl, {key: '4ac512dca79011e0aeec4040d3dc5c07', maxWidth: 420, wrapElement: 'div', method : "afterParent"  }, function(oembed){				
          if (oembed != null)
		  {
			embedCode = oembed.code;
			videoTitle = oembed.title;
			var post = "<li class='video_drag'><div class='urlWrapper'><div><a class='videoTitle' target='_blank' href='"+videoUrl+"'>"+oembed.title+"</a></div><div class='videoContent'><div class='video_domain'><div class='video_favicon'></div><div class='video_author'><a target='_blank' href='"+videoUrl+"'>v.youku.com</a></div></div><div><img class='youku_thumbnail' src='"+oembed.thumbnail_url+"' /><div class='video_description'>"+oembed.description+"</div></div></div></div></li>";
			$('#source_list').html(post);  
		  }		  			
          });
		})
		
		if($('#sto_title').val() =='')
		{
		  $('#sto_title').val('你的故事标题').removeClass('imply_color').focus(function(){
		  if($(this).val() == '你的故事标题')
		  {
		    $(this).val('').removeClass('imply_color');
		  }
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('你的故事标题').removeClass('imply_color');
		  }
		  });
		}
		
		
		if($('#sto_summary').val() =='')
		{
		  $('#sto_summary').val('给你的故事写一个简短的描述').addClass('imply_color').focus(function(){
		  if($(this).val() == '给你的故事写一个简短的描述')
		  {
		    $(this).val('').removeClass('imply_color');
		  }		  
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('给你的故事写一个简短的描述').addClass('imply_color');
		  }
		  });
		}

		if($('#sto_tag').val() =='')
		{
		  $('#sto_tag').val('添加故事标签，空格或逗号分隔').addClass('imply_color').focus(function(){
		  if($(this).val() == '添加故事标签，空格或逗号分隔')
		  {
		    $(this).val('').removeClass('imply_color');
		  }		  
		  }).blur(function(){
		  if($(this).val() == '')
		  {
		    $(this).val('添加故事标签，空格或逗号分隔').addClass('imply_color');
		  }
		  });
		}	
		
		$('#story_list li').live('mouseover', function(e)
		{
		  $(this).find('.cross').css('visibility', 'visible');
		});
		
		$('#story_list li').live('mouseout', function(e)
		{
		  $(this).find('.cross').css('visibility', 'hidden');
		});
		
		$('#actions').click(function(e)
		{
		  e.preventDefault();
		  unbindonbeforeunload();
		  if($(e.target).hasClass('disable'))
		  {
			var winH = $(window).height();
			var winW = $(window).width();
			var scrollTop = $(document).scrollTop();
			var scrollLeft = $(document).scrollLeft();
			var login_dialog = $('#boxes #dialog');
				  
			login_dialog.css('top',  winH/2-login_dialog.height()/2+scrollTop-100);
			login_dialog.css('left', winW/2-login_dialog.width()/2+scrollLeft);
		
			login_dialog.fadeIn(1000);
		  }
		  else
		  {
		      var story_title_txt = $('#sto_title').attr('value');
			  var postdata; 
			  var posturl = '/member/publish.php';
			  if($(e.target).is('#publishBtn'))
			  {
				postdata = prepare_story_data('Publish');
			  }
			  else if($(e.target).is('#previewBtn'))
			  {
				postdata = prepare_story_data('Preview');
			  }
			  else
			  {
				postdata = prepare_story_data('Draft');
			  }
			  if($(e.target).is('#publishBtn') && story_title_txt == '你的故事标题')
			  {
				alert('请为你的故事输入一个标题');
				$('#sto_title').focus();
			  }
			  else
			  {
				$.post(posturl, postdata,
				function(data, textStatus)
				{					
				  self.location = data;
				});
			  }
		  }
		});
		
		//douban reviews part
		$('.douban_review').live('click', function(e){
		  e.preventDefault();
		  var getUrl = '/douban/doubanreviewsoperation.php';
		  var getData;
		  var itemSubjectId = $(this).closest('.douban_drag').attr('id').substr(2);
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
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
		  }
		  }); 
		});
		
		//tencent user weibo part
		$('.list_tweibo').live('click', function(e){
		  usersearchTimestamp = 0;
		  e.preventDefault();
		  var getUrl = tweibo_url;
		  var getData;
		  var tUserName = $(this).closest('.weibo_drag').attr('id');
		  getData = {operation: 'user_search', keywords: tUserName, page: 0, timestamp: usersearchTimestamp};
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
		  }
		  }); 
		});
		
		$('.list_t_weibo').live('click', function(e){
		  e.preventDefault();
		  weiboSearhPage = 1;
		  var getUrl = weibo_url;
		  var getData;
		  var words_val = $(this).text();
		  getData = {operation: 'weibo_search', keywords: words_val, page: weiboSearhPage};
		  
		  $.ajax({
		  type: 'GET',
		  url: getUrl,
		  data: getData, 
		  beforeSend:function() 
		  {
		    var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		    $('#source_list').html(imgloading);
		  },
		  success: function(data)
		  {
			$('#source_list').html(data);
			show_weibo_card('source_list');
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
		  if ($(e.target).is('.loadmore'))
		  {
			var getUrl;
			var getData;
			if(0 == selected)
			{
			  var words;
			  if(0 == vtabIndex)
		      {
		        words = $('#keywords').val();
				getUrl = weibo_url;
				weiboSearhPage++;
				getData = {operation: 'weibo_search', keywords: words, page: weiboSearhPage};
		      }
		      else if(1 == vtabIndex)
		      {
		        words = $('#keywords').val();
				getUrl = tweibo_url;
				//weibosearchTimestamp = $('.loadmore span').attr('id');
				tweibosearchPage++;
				getData = {operation: 'weibo_search', keywords: words, page: tweibosearchPage}; 
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('book'))
				{
				  getUrl = douban_url;
				  bookStartIndex = bookStartIndex+doubanItemCounts;
				  getData = {operation: 'book', keywords: $('#d_keywords').val(), startIndex: bookStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('bookReviews'))
				{
				  getUrl = '/douban/doubanreviewsoperation.php';
				  bookReviewStartIndex = bookReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'bookReviews', subjectID: loadMoreItem.attr('id'), startIndex: bookReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = yupoo_url;
				picSearchPage++;
				getData = {operation: 'pic_search', keywords: words, page: picSearchPage};
			  }
			  $('.loadmore').remove();
			  //add weibo search function
			  
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				if(0 == vtabIndex)
				{
				  show_weibo_card('source_list');
				}
			  });
			}
			else if(1 == selected)
			{
			  if(0 == vtabIndex)
		      {
		        getUrl = weibo_url;
				myPage++;
				getData = {operation: 'my_weibo', page: myPage}
		      }
		      else if(1 == vtabIndex)
		      {
		        getUrl = tweibo_url;
				myPageTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'my_weibo', page: 1, timestamp: myPageTimestamp}; 
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('movie'))
				{
				  getUrl = douban_url;
				  movieStartIndex = movieStartIndex+doubanItemCounts;
				  getData = {operation: 'movie', keywords: $('#d_keywords').val(), startIndex: movieStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('movieReviews'))
				{
				  getUrl = '/douban/doubanreviewsoperation.php';
				  movieReviewStartIndex = movieReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'movieReviews', subjectID: loadMoreItem.attr('id'), startIndex: movieReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = yupoo_url;
				userpicSearchPage++;
				getData = {operation: 'user_search', keywords: words, page: userpicSearchPage};
			  }
			  $('.loadmore').remove();					  
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				if(0 == vtabIndex)
				{
				  show_weibo_card('source_list');
				}
			  });
			}
			else if(2 == selected)
			{
			  if(0 == vtabIndex)
		      {
		        getUrl = weibo_url;
				followPage++;
				getData = {operation: 'my_follow', page: followPage}
		      }
		      else if(1 == vtabIndex)
		      {
		        getUrl = tweibo_url;
				followTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'my_follow', page: 1, timestamp: followTimestamp};
		      }
			  else if(2 == vtabIndex)
		      {
				var loadMoreItem = $('.loadmore');
				if(loadMoreItem.hasClass('music'))
				{
				  getUrl = douban_url;
				  musicStartIndex = musicStartIndex+doubanItemCounts;
				  getData = {operation: 'music', keywords: $('#d_keywords').val(), startIndex: musicStartIndex, numResults: doubanItemCounts};
				}
				else if(loadMoreItem.hasClass('musicReviews'))
				{
				  getUrl = '/douban/doubanreviewsoperation.php';
				  musicReviewStartIndex = musicReviewStartIndex+commentsPerQuery;
				  getData = {operation: 'musicReviews', subjectID: loadMoreItem.attr('id'), startIndex: musicReviewStartIndex, numResults: commentsPerQuery};
				}
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = yupoo_url;
				colSearchPage++;
				getData = {operation: 'col_search', keywords: words, page: colSearchPage};
			  }
			  $('.loadmore').remove();
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				if(0 == vtabIndex)
				{
				  show_weibo_card('source_list');
				}
			  });
			}
			else
			{
			  if(0 == vtabIndex)
		      {
		        getUrl = weibo_url;
				userSearchPage++;
				getData = {operation: 'user_search', keywords: $('#keywords').val(), page:userSearchPage};
		      }
		      else if(1 == vtabIndex)
		      {
				getUrl = tweibo_url;
				//usersearchTimestamp = $('.loadmore span').attr('id');
				//getData = {operation: 'user_search', keywords: $('#keywords').val(), page: 1, timestamp: usersearchTimestamp};
				if($(e.target).closest('.loadmore').hasClass('tuser'))
				{
				  tuserSearchPage++;
				  getData = {operation: 'list_user', keywords: $('#keywords').val(), page: tuserSearchPage};
				}
				else
				{
				  var tUserName = $('.loadmore').prev().find('.user_page').attr('href').replace(/http:\/\/t.qq.com\//,"");
				  usersearchTimestamp = $('.loadmore span').attr('id');
				  getData = {operation: 'user_search', keywords: tUserName, page: 1, timestamp: usersearchTimestamp};
				}
		      }
			  else if(2 == vtabIndex)
		      {
		        getUrl = douban_url;
				eventStartIndex = eventStartIndex+doubanItemCounts;
				getData = {operation: 'event', keywords: $('#d_keywords').val(), startIndex: eventStartIndex, numResults: doubanItemCounts};
		      }
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = yupoo_url;
				recSearchPage++;
				getData = {operation: 'rec_search', keywords: words, page: recSearchPage};
			  }
			  $('.loadmore').remove();
			  $.get(getUrl, getData,
			  function(data, textStatus)
			  {
				$('#source_list').append(data);
				if(0 == vtabIndex)
				{
				  show_weibo_card('source_list');
				}
			  });
			}
		  }
		});
		
		$('.addTextElementAnchor').live('mouseenter', function(){
		  $(this).css('background-color','#FDFFD2').append('<span class=\"add_text\">点击添加文字</span>');
		}).live('mouseleave', function(){
		  $(this).css('background-color','#FFFFFF').find('.add_text').remove();
		});
		
		$('#story_list').click(function(e)
		{
		  if ($(e.target).is('.add_comment') || $(e.target).is('.add_text') || $(e.target).is('.addTextElementAnchor'))
		  {
		    e.preventDefault();
			var $comment_box = $("<li class='textElement editing'><div class='editingDiv'><form class='formTextElement'><textarea class='inputEditor' name='inputEditor'></textarea></form><div class='belowTextEdit'><div class='actions'><button class='submit submitComment' type='submit'>确定</button><button class='cancel cancelEditor' type='reset'>取消</button></div></div></div></li><li class='addTextElementAnchor'><span><a class='add_comment'></a></span></li>");
		    $(e.target).closest('li').after($comment_box);
			$(".inputEditor").cleditor({
			width:476,
			height:150,
			controls:"bold italic underline strikethrough link | font size"
			});
		  }
		  
		  if($(e.target).is('.cancelEditor'))
		  {
			e.preventDefault();
			$(e.target).closest('.textElement').next('.addTextElementAnchor').remove();
			$(e.target).closest('.textElement').remove();
		  }
		  
		  if($(e.target).is('.submitComment'))
		  {
			e.preventDefault();
			var $textElement = $(e.target).closest('.textElement');
			var comment = $textElement.find('.inputEditor').val();
			if(comment == '' || comment == '<br>')
			{
			  $(e.target).closest('.textElement').next('.addTextElementAnchor').remove();
			  $(e.target).closest('.textElement').remove();
			}
			else
			{
			  $(e.target).closest('.editingDiv').remove();
			  var $commentDiv = $("<div class='cross' action='delete'></div><div class='handle'></div><div class='commentBox'>"+comment+"</div>");
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
		  $('#search_tab').text('微博搜索');
		  $('#my_tab').text('我的广播');
		  $('#follow_tab').text('我的收听');
		  $('#weibo_search_btn').text('搜索微博');
		  if(1 != selVTab)
		  {
		    $weiboTabs.tabs( "select" , 0 );
		    $('#weibo_search').removeClass('none');
			$('#source_list').children().remove();
			$('#keywords').val('关键字').addClass('imply_color');
		  }
		  selVTab = 1;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(2 == vtabIndex)
		{
		  if(2 != selVTab)
		  {
		    $doubanTabs.tabs( "select" , 0 );
			$('#source_list').children().remove();
			$('#d_keywords').val('书名').addClass('imply_color');
		  } 
		  selVTab = 2;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(3 == vtabIndex)
		{
		  if(3 != selVTab)
		  {
		    $('#source_list').children().remove();
		  } 
		  selVTab = 3;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
		else if(4 == vtabIndex)
		{
		  if(4 != selVTab)
		  {
		    $picTabs.tabs( "select" , 0 );
			$('#source_list').children().remove();
			$('#pic_keywords').val('关键字').addClass('imply_color');
		  } 
		  selVTab = 4;
		  $('#vtab>div').hide().eq(vtabIndex-1).show();
		}
        else
		{
		  $('#search_tab').text('话题搜索');
		  $('#my_tab').text('我的微博');
		  $('#follow_tab').text('我的关注');
		  $('#weibo_search_btn').text('搜索话题');
		  $('#keywords').val('关键字').addClass('imply_color');
		  if(0 != selVTab)
		  {
		    $weiboTabs.tabs( "select" , 0 );
		    $('#weibo_search').removeClass('none');
			$('#source_list').children().remove();
		    var getUrl = weibo_url;
		    var getData;
		    getData = {operation: 'list_ht'};
		  
		    $.ajax({
		    type: 'GET',
		    url: getUrl,
		    data: getData, 
		    beforeSend:function() 
		    {
		      var imgloading = $("<span class='loading_wrapper'><img src='../img/loading.gif' /></span>");
		      $('#source_list').html(imgloading);
		    },
		    success: function(data)
		    {
			  $('#source_list').html(data);
		    }
		    });
		  }
		  selVTab = 0;
		  $('#vtab>div').hide().eq(vtabIndex).show();
		}
        }).eq(0).click();

		$('.window .close').click(function (e) {
			e.preventDefault();
			$('#mask').hide();
			$('.window').hide();
		});		

		$('#mask').click(function () {
			$(this).hide();
			$('.window').hide();
		});	
	});
