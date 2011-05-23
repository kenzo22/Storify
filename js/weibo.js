WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
{
  var cfg = {
              key: '314237338',
			  xdpath: 'http://story.com/storify/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
  
  //WB.widget.atWhere.searchAndAt(document.getElementById("sourcelist_container"));
  //WB.widget.atWhere.searchAndAt(document.getElementById("story_pane"));
  //WB.widget.atWhere.searchAndAt(document.getElementById("source_list"));
});
			
function log(sData)
{
	$('#outputBox')[0].value = sData;
}
			
function weibo_login() 
{
  WB.connect.login(function() {
    self.location = '/storify/member/';
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

function my_weibo()
{
  $('.weibo_drag').remove();
  var method = '/statuses/user_timeline.json';
  var type = 'get';
  args['user_id'] = '11051';
  run_api_cmd(type, method, args);
}

function my_follow()
{
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
  $(event.target || event.srcElement).closest('.remove_item').hide();
}

function hide_close(event)
{
  $(event.target || event.srcElement).closest('.remove_item').show();
}

function run_api_cmd(type, method, args)
{
  $('.weibo_drag').remove();
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    
	$.each(sResult, function(i, result)
	{
	  var weiboText, from, from_id, time, photo;
	  weiboText= result.text;
	  time = result.created_at;
	  time = date_format(time);
	  
	  from = result.user.screen_name;
	  from_id = result.user.id;
	  
	  photo = result.user.profile_image_url;

	 
	  var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+photo+"' alt='"+from+"' border=0 /><div class='weibo_content'>\
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
		var weibo_Text= ui.draggable.find('.weibo_text').text();
	    var weibo_from = ui.draggable.find('.weibo_from').text();
	    //var weibo_from_id = $(srcElem).find('.weibo_from').text();
	    var weibo_time = ui.draggable.find('.create_time').text();
		//weibo_time = date_format(weibo_time);
	    var weibo_photo = ui.draggable.find('.profile_img').attr('src');
		var content = ("<li class='weibo_drop' onmouseover='display_close(event)' onmouseout='hide_close(event)'><span class='remove_item' style='margin:0; padding-left:360px; color:#ababac;'><a onclick='remove_item(event)'>X</a></span><div class='story_wrapper'><div><span>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='margin-right:34px; padding-left:235px;'>\
		<a href='http://weibo.com/"+from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span style='height:16px;'>"+weibo_time+"</span></div></div></div></li>");
		var oid = elem.id;
		var sid = "s" + oid;  

		//
		//$('.weibo_drop').mouseover(function(event){
		//$(event.target || event.srcElement).closest('span').show();
		//alert('over');
		//});
		//
        
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

/*
function run_api_cmd()
{
  var method = '/statuses/friends_timeline.json';
  var type = 'get';
  var args = {};
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    
	$.each(sResult, function(i, result)
	{
	  var weiboText, from, from_id, time, photo;
	  weiboText= result.text;
	  time = result.created_at;
	  time = date_format(time);
	  
	  from = result.user.screen_name;
	  from_id = result.user.id;
	  
	  photo = result.user.profile_image_url;

	 
	  var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+photo+"' alt='"+from+"' border=0 /><div class='weibo_content'>\
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
		ui.draggable.remove();
		var weibo_Text= ui.draggable.find('.weibo_text').text();
	    var weibo_from = ui.draggable.find('.weibo_from').text();
	    //var weibo_from_id = $(srcElem).find('.weibo_from').text();
	    var weibo_time = ui.draggable.find('.create_time').text();
		//weibo_time = date_format(weibo_time);
	    var weibo_photo = ui.draggable.find('.profile_img').attr('src');
		var content = ("<li class='weibo_drop'><div class='story_wrapper'><div><span>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='margin-right:34px; padding-left:235px;'>\
		<a href='http://weibo.com/"+from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span style='height:16px;'>"+weibo_time+"</span></div></div></div></li>");
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
*/

function run_api_cmd2()
{
  $('.weibo_drag').remove();
  var method = '/search.json';
  var type = 'get';
  var args = {};
  args['q'] = 'Angelababy';
  //var method = '/statuses/friends_timeline.json';
  //var type = 'get';
  //var args = {};
  //var loginfo;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    $.each(sResult.results, function(i, result)
	{
	  var weiboText, from, from_id, time, photo;
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
	 
	  var $weibo_li=("<li class='weibo_drag'><div class='story_wrapper'><img class='profile_img' style='width: 32px; height: 32px; float:left; overflow: hidden; margin-top:3px;' src='"+photo+"' alt='"+from+"' border=0 /><div class='weibo_content'>\
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
		var weibo_Text= ui.draggable.find('.weibo_text').text();
	    var weibo_from = ui.draggable.find('.weibo_from').text();
	    //var weibo_from_id = $(srcElem).find('.weibo_from').text();
	    var weibo_time = ui.draggable.find('.create_time').text();
		//weibo_time = date_format(weibo_time);
	    var weibo_photo = ui.draggable.find('.profile_img').attr('src');
		var content = ("<li class='weibo_drop'><div class='story_wrapper'><div><span>"+weibo_Text+"</span></div><div id='story_signature'><div style='float:right;'><a href='http://weibo.com/"+from_id+"' target='_blank'>\
		<img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"+weibo_photo+"' alt='"+weibo_from+"' border=0 /></a></div><div id='signature_text' style='margin-right:34px; padding-left:235px;'>\
		<a href='http://weibo.com/"+from_id+"' target='_blank' style='display:block; height:16px;'><span>"+weibo_from+"</span></a><span style='height:16px;'>"+weibo_time+"</span></div></div></div></li>");
		//var content = $(srcElem).html();
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
	  
	  //
	  
	});  
    //log(loginfo);
  },
  args,
  {
    'method': type
  })
}
