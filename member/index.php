<?php
include "../editorglobal.php";
session_start();
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
?>
<link type="text/css" href="/storify/css/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="/storify/css/jquery.ui.tabs.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="http://js.wcdn.cn/t3/style/css/common/card.css" />
<link rel="stylesheet" type="text/css" href="/storify/CLEditor/jquery.cleditor.css" />
<script type="text/javascript" src="/storify/CLEditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="/storify/js/jquery.embedly.min.js"></script>
<script type='text/javascript' src='/storify/js/weibo.js'></script>
<script type='text/javascript' src='/storify/js/jquery-ui-1.8.12.custom.min.js'></script>
<script type='text/javascript' src='/storify/js/jquery.scrollfollow.js'></script>

<?php
$content = "
<div id='storyContent' style='margin-bottom:0;'>
  <div class='inner'>
	<div class='left_half'>
	<div id='source_pane'>
	  <div id='sourcelist_container'>
	    <div id='vtab'>
		  <ul>
		    <li class='weiboLi'><a><img class='source_img' title='新浪微博' src='/storify/img/sina24.png' /></a></li>
			<li class='tweiboLi'><a><img class='source_img' title='腾讯微博' src='/storify/img/tencent24.png' /></a></a></li>
			<li class='doubanLi'><a><img class='source_img' title='豆瓣社区' src='/storify/img/logo_douban.png' /></a></a></li>
		    <li class='videoLi'><a><img class='source_img' title='优酷视频' src='/storify/img/icon-youku.png' /></a></li>
			<li class='yupooLi'><a><img class='source_img' title='又拍社区' src='/storify/img/yupoo-logo.png' /></a></li>
		  </ul>
		  <div id='weiboTabs'>
		    <ul>
			  <li><a id='search_tab' href='#tabs-1'>微博搜索</a></li>
		      <li><a id='my_tab' href='#tabs-2'>我的微博</a></li>
		      <li><a id='follow_tab' href='#tabs-3'>我的关注</a></li>
		      <li><a id='user_tab' href='#tabs-4'>用户搜索</a></li>
	        </ul> 
			<div id='tabs-1'> 

	        </div> 
	        <div id='tabs-2'> 

	        </div> 
	        <div id='tabs-3'> 

	        </div> 
	        <div id='tabs-4'> 
		      
	        </div>
			<div id='weibo_search'>
		      <form id='source_controller_form' action='#'>
		        <div class='sep'>         
			      <input id='keywords' name='keywords' type='text'>
			      <button id='weibo_search_btn' type='button' value='search'>搜索微博</button>
                </div>
		      </form>
		    </div>
		  </div>
		  <div id='doubanTabs'>
		    <ul>
			  <li><a id='book_tab' href='#dtabs-1'>图书</a></li>
		      <li><a id='movie_tab' href='#dtabs-2'>电影</a></li>
		      <li><a id='music_tab' href='#dtabs-3'>音乐</a></li>
		      <li><a id='event_tab' href='#dtabs-4'>活动</a></li>
	        </ul> 
			<div id='dtabs-1'> 

	        </div> 
	        <div id='dtabs-2'> 

	        </div> 
	        <div id='dtabs-3'> 

	        </div> 
	        <div id='dtabs-4'> 
		      
	        </div>
			<div id='douban_search'>
		      <form action='#'>
		        <div class='sep'>        
			      <input id='d_keywords' name='d_keywords' type='text'>
			      <button id='douban_search_btn' type='button' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  <div id='videoTabs'>
		    <form action='#' style='padding-top:15px; padding-bottom:33px;'>
		    <div>
			  <label for='videoUrl'>优酷视频地址:</label><br />           
			  <input style='margin-top:13px;' id='videoUrl' name='videoUrl' type='text'>
			  <button style='margin-top:13px;' type='button' value='嵌入视频' id='embedVideo'>嵌入视频</button>
            </div>
		    </form>
		  </div>
		  <div id='picTabs'>
		    <ul>
			  <li><a id='search_tab_pic' href='#pictabs-1'>图片搜索</a></li>
		      <li><a id='user_tab_pic' href='#pictabs-2'>用户搜索</a></li>
	        </ul> 
			<div id='pictabs-1'> 

	        </div> 
	        <div id='pictabs-2'> 

	        </div> 
			<div id='pic_search'>
		      <form action='#'>
		        <div class='sep'>           
			      <input id='pic_keywords' name='pic_keywords' type='text'>
			      <button id='pic_search_btn' type='button' value='search'>搜索</button>
                </div>
		      </form>
		    </div>
		  </div>
		  
		</div>
		<ul id='source_list' class='connectedSortable'>
		</ul>    	
	  </div>
	</div>
	</div>
	<div class='right_half'>
	<div id='story_pane'>
	  <div id='story'>";

if(isset($_GET['post_id']))
{  
  $c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
  $t = new TWeiboClient( MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']  );
  $post_id = $_GET['post_id'];
  echo "<script language=javascript >
  var post_id=$post_id;
  </script>";
  $result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
  if(!$result)
  {
	throw new Exception('Could not execute query.');
  }
  $story_title=$result['post_title'];
  $story_summary=$result['post_summary'];
  $story_pic=$result['post_pic_url'];
  $story_content=$result['post_content'];
  $story_content_array = json_decode($story_content, true);
  $weibo_id_array = array();
  $tweibo_id_array = array();
	
  $content .="<div id='story_header'>
		  <div id='story_pic'>
		    <p><img id='story_thumbnail' width='88' alt='thumbnail' src='".$story_pic."'</p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'>prev</a></li>
			  <li><a id='next_img' href='#'>next</a></li>
			</ul>
		  </div>
		  <span > <input type='text' value='".$story_title."' name='story_title' id='sto_title'> </span>
		  <div>
		    <textarea id='sto_summary'>".$story_summary."</textarea>
		  </div>
		  <div>
		    <span ><input type='text' value='' name='story_tag' id='sto_tag'></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'><li class='addTextElementAnchor'>
			  <span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>";
  
  foreach($story_content_array['content'] as $key=>$val)
  {	
	if($val['type'] === 'weibo')
	{
	  $weibo_per_id = $val['content'];
	  $single_weibo  = $c->show_status($weibo_per_id );
		
	  if ($single_weibo === false || $single_weibo === null)
	  {
	    echo "Error occured";
	    return false;
	  }
	  if (isset($single_weibo['error_code']) && isset($single_weibo['error']))
	  {
		echo ('Error_code: '.$single_weibo['error_code'].';  Error: '.$single_weibo['error'] );
		return false;
	  }
	  if (isset($single_weibo['id']) && isset($single_weibo['text']))
	  {
		$createTime = dateFormat($single_weibo['created_at']);
		$content .= ("<li class='weibo_drop sina' id='$weibo_per_id'><div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"
					.$single_weibo['text']."</span></div><div id='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
					.$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'  style='text-align:right; height:16px;'><span> <img border='0' style='position:relative; top:2px' src='/storify/img/sina16.png'/><a>"
					.$createTime."</a></span></div></span></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>");
	  }
	}
	else if($val['type'] === 'tweibo')
	{
	  $tweibo_per_id = $val['content'];
	  $tweibo_id_array[] = $tweibo_per_id;
	  $content .="<li class='weibo_drop tencent' id='$tweibo_per_id'><div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div></li>
	  <li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>";
	}
	else if($val['type'] === 'comment')
	{
	  $comment_text = $val['content'];
	  $content .="<li class='textElement editted'><div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='commentBox'>"
	  .$comment_text."</div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>";		
	}
	else if($val['type'] === 'video')
	{
	  $video_url_php = $val['content'];
	  $content .="<li class='video_drop'><div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div><a class='videoTitle' target='_blank' href='"
	  .$video_url_php."'></a></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>";    	
	}
	else if($val['type'] === 'photo')
	{
	  $photo_meta_data = $val['content'];
	  $photo_title = $photo_meta_data['title'];
	  $photo_author = $photo_meta_data['author'];
	  $photo_per_url = $photo_meta_data['url'];	 
	  $content .="<li class='pic_drop'><div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div style='margin:0px auto; text-align:center; border: 5px solid #FFFFFF; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4); max-width: 260px;'><img class='pic_img' src='"
				.$photo_per_url."'/><div class='pic_title' style='line-height:1.5;'>".$photo_title."</div><div class='pic_author' style='line-height:1.5;'>".$photo_author."</div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span></li>";    	
	}
  }
  
  if(count($tweibo_id_array) > 0)
  {
    $tweibo_ids = implode(",", $tweibo_id_array);
    echo "<script language='javascript' >
		$(function()
		{			  
		  $.get('../tweibo/tweibooperation.php', {operation: 'list_weibo', weibo_ids: '$tweibo_ids'},
		  function(data, textStatus)
		  {
			if(textStatus == 'success')
			{
			  var count = $(data).find('li').length;
			  for(var j=0; j<count; j++)
			  {
				var li = $('li:eq('+j+')', data);
				var temp_id = li.attr('id');
				$('#'+temp_id).append(li.contents());
			  }
			}
		  });
		});
		</script>";
  }

  $content .="</ul></div></div></div></div></div></div>";
  echo $content;
  echo "<script language='javascript' >
			window.onload = function()
			{			  
			  $('.video_drop').each(function(){
			  var videoUrlJs = $(this).find('.videoTitle').attr('href');
			  append_video_content(videoUrlJs);
			  });
			}
			</script>";
}
else
{
  $content .= "<div id='story_header'>
		  <div id='story_pic'>
		    <p><img id='story_thumbnail' width='88' height='88' src='' style='background-color:#EFEFEF;'/></p>
			<ul id='imagecontroller'>
			  <li><a id='prev_img' href='#'>prev</a></li>
			  <li><a id='next_img' href='#'>next</a></li>
			</ul>
		  </div>
		  <span ><input type='text' value='' name='story_title' id='sto_title'></span>
		  <div>
		    <textarea id='sto_summary'></textarea>
		  </div>
		  <div>
		    <span ><input type='text' value='' name='story_tag' id='sto_tag'></span>
		  </div>
		</div>
		<div id='storylist_container'>
		  <ul id='story_list' class='connectedSortable' style='padding:0;'>
		    <li class='addTextElementAnchor'>
			  <span><a><img class='add_comment' src='/storify/img/editcomment.png' border='0'/></a></span>
		    </li>
		  </ul>
		</div>
	  </div>
	</div>
	</div>
  </div>
</div>";
  echo $content;
}
?>

<script>
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

function display_search()
{
  //$('.weibo_drag').remove();
  //need to compare the performance of this two remove method
  $('#source_list').children().remove();
  //$('#keywords').val('');
  $('#weibo_search button').text('搜索微博');
  $('#weibo_search').css('display', 'block');
}

function display_user_search()
{
  $('#source_list').children().remove();
  //$('#keywords').val('');
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
		  else if($(this).hasClass('pic_drop'))
		  {
		    story_pic_url = $(this).find('.pic_img').attr('src').replace(/small$/, "square");
			return false;
		  }
		  /*else if($(this).hasClass('video_drop'))
		  {
		    story_pic_url = $(this).find('.videoTitle').attr('id');
			return false;
		  }*/
		}
	  });
	  $('#story_thumbnail').attr('src', story_pic_url);
	}
	$temp.next('li').remove();
	$temp.remove();
}

function change_story_pic(direction)
{
  //debugger;
  var item_pic_url;
  var story_pic_array = new Array();
  $('#story_list li:not(.addTextElementAnchor, .textElement, .video_drop)').each(function(index){
    if($(this).hasClass('sina'))
    {
	  item_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/(\d+)\/50\/(\d+)/, "$1\/180\/$2");
	  story_pic_array.push(item_pic_url);
    }
    else if($(this).hasClass('tencent'))
    {
	  item_pic_url = $(this).find('.profile_img_drop').attr('src').replace(/50$/, "180");
	  story_pic_array.push(item_pic_url);
    }
    else if($(this).hasClass('pic_drop'))
    {
	  item_pic_url = $(this).find('.pic_img').attr('src').replace(/small$/, "square");
	  story_pic_array.push(item_pic_url);
    }
  });
  story_pic_array = story_pic_array.getUnique();
  var current_pic_url = $('#story_thumbnail').attr('src');
  var url_array_length = story_pic_array.length;
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
		  $('#d_keywords').val('书名').css('color', '#999999');
		});
		
		$('#movie_tab').click(function(){
		  $('#d_keywords').val('电影名').css('color', '#999999');
		});
		
		$('#music_tab').click(function(){
		  $('#d_keywords').val('歌曲名').css('color', '#999999');
		});
		
		$('#event_tab').click(function(){
		  $('#d_keywords').val('搜活动').css('color', '#999999');
		});
		
		$('#d_keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      var douban_selected = $doubanTabs.tabs('option', 'selected');
			  if(douban_selected == 0)
			  {
			    $(this).val('书名').css('color', '#999999');
			  }
			  else if(douban_selected == 1)
			  {
			    $(this).val('电影名').css('color', '#999999');
			  }
			  else if(douban_selected == 2)
			  {
			    $(this).val('歌曲名').css('color', '#999999');
			  }
			  else if(douban_selected == 3)
			  {
			    $(this).val('搜活动').css('color', '#999999');
			  }
		    }
		  }).focus(function(){
		    var douban_selected = $doubanTabs.tabs('option', 'selected');
			if(douban_selected == 0 && $(this).val() == '书名')
			{
			  $(this).val('').css('color', 'black');
			}
			else if(douban_selected == 1 && $(this).val() == '电影名')
			{
			  $(this).val('').css('color', 'black');
			}
			else if(douban_selected == 2 && $(this).val() == '歌曲名')
			{
			  $(this).val('').css('color', 'black');
			}
			else if(douban_selected == 3 && $(this).val() == '搜活动')
			{
			  $(this).val('').css('color', 'black');
			}
		  });
		
		$('#pic_keywords').val('关键字').css('color', '#999999');
		
		$('#pic_keywords').blur(function(){
		    if($(this).val() == '')
		    {
		      var yupoo_selected = $picTabs.tabs('option', 'selected');
			  if(yupoo_selected == 0)
			  {
			    $(this).val('关键字').css('color', '#999999');
			  }
			  else if(yupoo_selected == 1)
			  {
			    $(this).val('又拍用户名').css('color', '#999999');
			  }
		    }
		  }).focus(function(){
		    var yupoo_selected = $picTabs.tabs('option', 'selected');
			if(yupoo_selected == 0 && $(this).val() == '关键字')
			{
			  $(this).val('').css('color', 'black');
			}
			else if(yupoo_selected == 1 && $(this).val() == '又拍用户名')
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
		  //my_weibo(myPage);
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
		  //my_follow(followPage);
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
			cancel: ".weibo_drop, .video_drop, .textElement",
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
			    var position = ui.position;
			　  var weibo_id = ui.item.find('.weibo_drag').attr('id');
			　  var weibo_Text= ui.item.find('.weibo_text').text();
			　  var weibo_from = ui.item.find('.weibo_from').text();
			　  var weibo_from_id = ui.item.find('.user_page').attr('href').replace(/http:\/\/weibo.com\//,"");
			  　var weibo_time = ui.item.find('.create_time').text();
			　  var weibo_photo = ui.item.find('.profile_img').attr('src');
				var content;	
			    if(ui.item.hasClass('sina'))
				{
				  ui.item.removeClass('weibo_drag').addClass('weibo_drop sina').children().remove();
				  content = ("<div class='cross' action='delete'><a><img src='/storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"
					+weibo_Text+"</span></div><div id='story_signature'><span style='float:right;'><a href='http://weibo.com/"+weibo_from_id+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
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
					+weibo_Text+"</span></div><div id='story_signature'><span style='float:right;'><a href='http://weibo.com/"+weibo_from_id+"' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
					+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></span><span id='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'><span ><a class='weibo_from_drop' href='http://weibo.com/"
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
			    //debugger;
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
		
		
		$('#draftBtn').click(function(e){
		  e.preventDefault();
		  //console.log('begin draft');
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
		  story_content_val.content = new Array();		  
		  $('#story_list li:not(.addTextElementAnchor)').each(function(i)
		  {
		    if($(this).hasClass('sina'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'weibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('tencent'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'tweibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('textElement'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'comment';
			  story_content_val.content[i].content = $(this).find('.commentBox').html();
			}
			else if($(this).hasClass('video_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'video';
			  story_content_val.content[i].content = $(this).find('.videoTitle').attr('href');
			}
			else if($(this).hasClass('pic_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
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
		  var postdata = {story_id: story_id_val, story_title: story_title_val, story_summary: story_summary_val, story_pic: story_pic_val, story_tag: story_tag_val, story_content: story_content_val_string};		  
		  $.post('draft.php', postdata,
		  function(data, textStatus)
		  {
            //console.log(data);						
			self.location = data;
		  });
			
		});
		
		$('#previewBtn').click(function(e){
		  e.preventDefault();
		  //console.log('begin preview');
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
		  story_content_val.content = new Array();
		  $('#story_list li:not(.addTextElementAnchor)').each(function(i)
		  {
		    if($(this).hasClass('sina'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'weibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('tencent'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'tweibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('textElement'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'comment';
			  story_content_val.content[i].content = $(this).find('.commentBox').html();
			}
			else if($(this).hasClass('video_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'video';
			  story_content_val.content[i].content = $(this).find('.videoTitle').attr('href');
			}
			else if($(this).hasClass('pic_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
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
		  var postdata = {story_id: story_id_val, story_title: story_title_val, story_summary: story_summary_val, story_pic: story_pic_val, story_tag: story_tag_val, story_content: story_content_val_string};	
		  $.post('preview.php', postdata,
		  function(data, textStatus)
		  {
            //console.log(data);						
			self.location = data;
		  });
			
		});
		
		$('#publishBtn').click(function(e)
		{
		  //console.log(post_id);
		  e.preventDefault();
		  //console.log('begin publish');
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
		  story_content_val.content = new Array();
		  /*$('.weibo_drop').each(function(i) 
		  {
			story_content_val.content[i] = new Object;
			story_content_val.content[i].type = 'weibo';
			story_content_val.content[i].content = $(this).attr('id');
		  });
		  var story_content_val_string = JSON.stringify(story_content_val);*/
		  
		  $('#story_list li:not(.addTextElementAnchor)').each(function(i)
		  {
		    //debugger;
			if($(this).hasClass('sina'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'weibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('tencent'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'tweibo';
			  story_content_val.content[i].content = $(this).attr('id');
			}
			else if($(this).hasClass('textElement'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'comment';
			  story_content_val.content[i].content = $(this).find('.commentBox').html();
			}
			else if($(this).hasClass('video_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
			  story_content_val.content[i].type = 'video';
			  story_content_val.content[i].content = $(this).find('.videoTitle').attr('href');
			}
			else if($(this).hasClass('pic_drop'))
			{
			  story_content_val.content[i] = new Object;
			  story_content_val.content[i].id = i;
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
		  var postdata = {story_id: story_id_val, story_title: story_title_val, story_summary: story_summary_val, story_pic: story_pic_val, story_tag: story_tag_val, story_content: story_content_val_string};	
		  $.post('publish.php', postdata,
		  function(data, textStatus)
		  {
            //console.log(data);						
			self.location = data;			
		  });
		});
		
		$('#source_list').click(function(e)
		{
		  //debugger;
		  var selected;
		  if(0 == vtabIndex || 1 == vtabIndex)
		  {
		    selected = $weiboTabs.tabs('option', 'selected'); 
		  }
		  else if(4 == vtabIndex)
		  {
		    selected = $picTabs.tabs('option', 'selected'); 
		  }
		  if ($(e.target).is('.loadmore a'))
		  {
			//var selected = $weiboTabs.tabs('option', 'selected'); 
			//var yupoo_selected = $picTabs.tabs('option', 'selected'); 
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
			  else if(4 == vtabIndex)
			  {
			    words = $('#pic_keywords').val();
				getUrl = '../yupoo/yupoooperation.php';
				userpicSearchPage++;
				getData = {operation: 'user_search', keywords: words, page: userpicSearchPage};
			  }
			  $('.loadmore').remove();
			  
			  /*$.ajax({
			  type: 'GET',
			  url: getUrl,
			  data: getData, 
			  beforeSend:function() 
			  {
				var imgpath = '../img/loading.gif';
				var imgloading = $("<span style='padding-left:180px;'><img src='../img/loading.gif' /></span>");
				$('.loadmore').append(imgloading);
			  },
			  success: function(data)
			  {
				$('#source_list').append(data);
				WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
			  }
			  });*/
			  
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
		      else
		      {
		        getUrl = '../tweibo/tweibooperation.php';
				followTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'my_follow', page: 1, timestamp: followTimestamp};
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
			  var words = $('#keywords').val();
			  if(0 == vtabIndex)
		      {
		        getUrl = '../weibo/weibooperation.php';
				userSearchPage++;
				getData = {operation: 'user_search', keywords: words, page:userSearchPage};
		      }
		      else
		      {
		        getUrl = '../tweibo/tweibooperation.php';
				usersearchTimestamp = $('.loadmore span').attr('id');
				getData = {operation: 'user_search', keywords: words, page: 1, timestamp: usersearchTimestamp};
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
</script>

<?php
include "../include/footer.htm";
?>
