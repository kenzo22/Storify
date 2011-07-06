WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
{
  var cfg = {
              key: '314237338',
			  xdpath: 'http://story.com/storify/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
});
			
function log(sData)
{
	$('#outputBox')[0].value = sData;
}
			
function weibo_login() 
{
  WB.connect.login(function() {
    self.location = '/storify/member/';
	//self.location = '/storify/member/testweibo.php';
	log('login');
  });
}
			
function weibo_logout() 
{
  WB.connect.logout(function() {
	log('logout');
  });
}

function date_format(origin_date)
{
  var temp_array = origin_date.split(' ');
  switch(temp_array[1])
  {
	case('Jan'):temp_array[1] = 1;
	break;
	case('Feb'):temp_array[1] = 2;
	break;
	case('Mar'):temp_array[1] = 3;
	break;
	case('Apr'):temp_array[1] = 4;
	break;
	case('May'):temp_array[1] = 5;
	break;
	case('Jun'):temp_array[1] = 6;
	break;
	case('Jul'):temp_array[1] = 7;
	break;
	case('Aug'):temp_array[1] = 8;
	break;
	case('Sep'):temp_array[1] = 9;
	break;
	case('Oct'):temp_array[1] = 10;
	break;
	case('Nov'):temp_array[1] = 11;
	break;
	case('Dec'):temp_array[1] = 12;
	break;
	default:temp_array[1] = temp_array[1];
	break;
  }
  var time_array = temp_array[3].split(':');
  temp_array[3] = time_array[0]+':'+time_array[1];
  return temp_array[5]+'-'+temp_array[1]+'-'+temp_array[2]+' '+temp_array[3];
}

function weibo_search()
{
	var keywords = $('#keywords').val();
	var type = $('#weibo_search button').text();
	if(type === '搜索微博')
	{
	  run_api_cmd2(keywords);
	}
	else
	{
	  var method = '/statuses/user_timeline.json';
	  var type = 'get';
	  var args = {};
	  args['screen_name'] = keywords;
	  run_api_cmd(type, method, args);
	}
}

function get_weibo_byID(ID)
{
  var method = '/statuses/show.json';
  var type = 'get';
  var args = {};
  args['id'] = ID;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    var weiboText, from, from_id, created_time, photo;
	weiboText= sResult.text;
	created_time = sResult.created_at;
	created_time = date_format(created_time);  
	from = sResult.user.screen_name;
	from_id = sResult.user.id;
	photo = sResult.user.profile_image_url;
	var content = ("<li class='weibo_drop'><div class='story_wrapper'><div><span class='weibo_text'>"+weiboText+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'><img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+photo+"' alt='"+from+"' border=0 /></a></div><div id='signature_text' style='float:right; margin-right:5px;'><a class='weibo_from' href='http://weibo.com/"+from_id+"' target='_blank' style='display:block; height:16px;'><span>"+from+"</span></a><span class='weibo_date' style='display:block; height:16px;'>"+created_time+"</span></div></div></div></li>");
	$('#weibo_ul').append(content);
 },
  args,
  {
    'method': type
  });
}


function user_search()
{
	var keywords = $('#keywords').val();
	run_api_cmd2(keywords);
}

function my_weibo()
{
  $('#weibo_search').css('display', 'none');
  $('.weibo_drag').remove();
  var method = '/statuses/user_timeline.json';
  var type = 'get';
  //args['user_id'] = '11051';
  var args = {};
  run_api_cmd(type, method, args);
}

function my_follow()
{
  $('#weibo_search').css('display', 'none');
  $('.weibo_drag').remove();
  var method = '/statuses/friends_timeline.json';
  var type = 'get';
  var args = {};
  run_api_cmd(type, method, args);
}

function remove_item(event)
{
	$(event.target || event.srcElement).closest('.weibo_drop').remove();
	//alert(txt);
}

function display_close(event)
{
  //alert('over');
  $('.cross').css('visibility', 'visible');
  //$(event.fromElement).closest('.cross').css('visibility', 'visible');
  //var txt = $(event.target || event.srcElement).id;
  //alert(txt);
}

function hide_close(event)
{
  $('.cross').css('visibility', 'hidden');
}

function run_api_cmd(type, method, args)
{
  //debugger;
  $('.weibo_drag').remove();
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
      
	$.each(sResult, function(i, result)
	{
	  
	  var weiboId, weiboText, from, from_id, time, photo;
	  weiboId=result.id;
	  weiboText= result.text;
	  time = result.created_at;
	  time = date_format(time);
	  
	  from = result.user.screen_name;
	  from_id = result.user.id;
	  
	  photo = result.user.profile_image_url;

	  //var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+weiboId+"' alt='"+from+"' border=0 /><div class='weibo_content'>\
	  //<a class='user_page' href='http://weibo.com/"+from_id+"' target='_blank' style = 'display:block;'><span class='weibo_from'>"+from+"</span></a><span class='weibo_text'>"+weiboText+"</span><div><span class='create_time'>"+time+"</span><span style='float:right;'><a>[转发]</a></span></div></div></div></li>");
	  
	  var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+photo+"' alt='"+from+"' border=0 /><div class='weibo_content' id='"+weiboId+"'>\
	  <a class='user_page' href='http://weibo.com/"+from_id+"' target='_blank' style = 'display:block;'><span class='weibo_from'>"+from+"</span></a><span class='weibo_text'>"+weiboText+"</span><div><span class='create_time'>"+time+"</span><span style='float:right;'><a>[转发]</a></span></div></div></div></li>");
	 
	  var $parent = $('#source_list');
	  $parent.append($weibo_li);
	  //
	  
	  //
	  var elem = '';
	  $('.weibo_drag').draggable({
	  cursor: 'move',
	  revert:true,
	  helper: 'original',
	  opacity: 0.55,
	  drag:function(e, ui)
      {
		  //$(this).remove();
      }
	  });
	  
	  
	  
	  $('#story_pane').droppable({
	  accept: '.weibo_drag',
	  activeClass: 'droppable-active',
	  hoverClass: 'droppable-hover',
	  //tolerance: 'touch',
	  drop: function(ev, ui) 
      {
		//$('.weibo_drag').remove();
		ui.draggable.remove();
		var weibo_id = ui.draggable.find('.weibo_content').attr('id');
		var weibo_Text= ui.draggable.find('.weibo_text').text();
	    var weibo_from = ui.draggable.find('.weibo_from').text();
	    var weibo_from_id = ui.draggable.find('.user_page').attr('href').replace(/http:\/\/weibo.com\//,"");
	    var weibo_time = ui.draggable.find('.create_time').text();
		//weibo_time = date_format(weibo_time);
	    var weibo_photo = ui.draggable.find('.profile_img').attr('src');
		/*var content = ("<li class='weibo_drop'><div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>\
		<a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='margin-right:34px; padding-left:235px;'>\
		<a class='weibo_from_drop' href='http://weibo.com/"+weibo_from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span class='weibo_date_drop' style='height:16px;'>"+weibo_time+"</span></div></div></div></li>");*/
		
		var content = ("<li class='weibo_drop' id='"+weibo_id+"'><div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>\
		<a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='float:right; margin-right:5px;'>\
		<a class='weibo_from_drop' href='http://weibo.com/"+weibo_from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span class='weibo_date_drop' style='display:block; height:16px;'>"+weibo_time+"</span></div></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/Storify/img/editcomment.png' border='0'/></a></span></li>");
		
		var oid = elem.id;
		var sid = "s" + oid;  

        
		if ( document.getElementById(sid) == null)
		{
			$('#story_list').append(content);
	    }else 	{	
		alert ("您已经添加了这个栏目菜单了,请您删除后再添加，谢谢！");
		} 
		WB.widget.atWhere.searchAndAt(document.getElementById("story_list"));
	  }
	
      });
	  //
	  WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
	  
	  //
	});  
  },
  args,
  {
    'method': type
  })
}

function run_api_cmd2(keywords)
{
  //$('#weibo_search').css('visibility', 'visible');
  $('.weibo_drag').remove();
  var method = '/search.json';
  var type = 'get';
  var args = {};
  //args['q'] = 'Angelababy';
  args['q'] = keywords;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    $.each(sResult.results, function(i, result)
	{
	  var weiboId, weiboText, from, from_id, time, photo;
	  weiboId=result.id;
	  weiboText= result.text;
	  from = result.from_user;
	  from_id = result.from_user_id;
	  time = result.created_at;
	  time = date_format(time);
	  photo = result.profile_image_url;
	  
	  /*
	  var $table = ("<div class='weibo_div'><table class='showborder weibo_table' border=0 cellpadding=1 cellspacing=1 align='center' width='370' style='padding-bottom:25px;'><tr><td valign='top' rowspan=2>\
	  <span style='font-size: 64px; color: #ccc;'> &#8220; </span></td><td valign='top'><span class='text' style='color:#4A4A4B;margin-top:13px;display:block;font-size:10.5pt;line-height:1.3;'>" +weiboText+"</span>\
	  </td></tr><tr><td><table border=0 cellpadding=1 cellspacing=1 align='right'><tr><td align='right'><a href='http://twitter.com/ReallyVirtual' style='text-decoration: none;' target='_blank'>\
	  <span class='author' style='color: #000; font-weight: bold;font-size:12px;'>"+from+"</span></a><span class='timestamp' style='display: block;'>\
	  <a href='http://weibo.com/"+from_id+"' style='color: #939393; text-decoration: none; margin-left: 5px; font-size:11px;' target='_blank'>"+time+"</a></span></td><td>\
	  <a href='http://weibo.com/"+from_id+"' target='_blank'><img style='width: 32px; height: 32px; float: left; overflow: hidden; margin-left: 10px;' src='"+photo+"' alt='"+from+"' border=0 /></a></td></tr></table></td>\
	  </tr></table></div>");*/
	 
	  var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+photo+"' alt='"+from+"' border=0 /><div class='weibo_content' id='"+weiboId+"'>\
	  <a class='user_page' href='http://weibo.com/"+from_id+"' target='_blank' style = 'display:block;'><span class='weibo_from'>"+from+"</span></a><span class='weibo_text'>"+weiboText+"</span><div><span class='create_time'>"+time+"</span><span style='float:right;'><a>[转发]</a></span></div></div></div></li>");
	  var $parent = $('#source_list');
	  $parent.append($weibo_li);
	  //
	  //WB.widget.atWhere.searchAndAt(document.getElementById("content"));
	  //
	  
	  var elem = '';
	  $('.weibo_drag').draggable({
	  cursor: 'move',
	  revert:true,
	  helper: 'original',
	  opacity: 0.55,
	  drag:function(e, ui)
      {
		  //$(this).remove();
      }
	  
	  });
	  
	  $('#story_pane').droppable({
	  accept: '.weibo_drag',
	  activeClass: 'droppable-active',
	  hoverClass: 'droppable-hover',
	  //tolerance: 'touch',
	  drop: function(ev, ui) 
      {
		//var srcElem = ev.srcElement || ev.target;
		ui.draggable.remove();
		var weibo_id = ui.draggable.find('.weibo_content').attr('id');
		var weibo_Text= ui.draggable.find('.weibo_text').text();
	    var weibo_from = ui.draggable.find('.weibo_from').text();
	    var weibo_from_id = ui.draggable.find('.user_page').attr('href').replace(/http:\/\/weibo.com\//,"");
	    var weibo_time = ui.draggable.find('.create_time').text();
		//weibo_time = date_format(weibo_time);
	    var weibo_photo = ui.draggable.find('.profile_img').attr('src');
		/*var content = ("<li class='weibo_drop'><div class='story_wrapper'><div><span>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='margin-right:34px; padding-left:235px;'>\
		<a href='http://weibo.com/"+from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span style='height:16px;'>"+weibo_time+"</span></div></div></div></li>");*/
		var content = ("<li class='weibo_drop' id='"+weibo_id+"'><div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>\
		<a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a></div><div class='story_wrapper'><div><span class='weibo_text_drop'>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='float:right; margin-right:5px;'>\
		<a class='weibo_from_drop' href='http://weibo.com/"+weibo_from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span class='weibo_date_drop' style='display:block; height:16px;'>"+weibo_time+"</span></div></div></div></li><li class='addTextElementAnchor'><span><a><img class='add_comment' src='/Storify/img/editcomment.png' border='0'/></a></span></li>");
		var oid = elem.id;
		var sid = "s" + oid;       
        
		if ( document.getElementById(sid) == null)
		{
			$('#story_list').append(content);
	    }else 	{	
		alert ("您已经添加了这个栏目菜单了,请您删除后再添加，谢谢！");
		} 
	  }
	
      });
	});  
  },
  args,
  {
    'method': type
  })
}
