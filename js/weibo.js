WB.core.load(['connect', 'client'], function() 
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

function run_api_cmd()
{
  var method = '/search.json';
  var type = 'get';
  var args = {};
  args['q'] = 'Angelababy';
  //var loginfo;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    $.each(sResult.results, function(i, result)
	{
	  
	  var weiboText= result.text;
	  var from = result.from_user;
	  var from_id = result.from_user_id;
	  var time = result.created_at;
	  time = date_format(time);
	  var photo = result.profile_image_url;
	  
	  /*
	  var $quote_span = $("<span style='font-size: 64px; color: #ccc;'> &#8220; </span>");
	  var $quote_td = $("<td valign='top' rowspan=2></td>");
	  $quote_td.append($quote_span);
	  
	  var $text_span = $("<span class='text' style='color:#4A4A4B;margin-top:13px;display:block;font-size:10.5pt;line-height:1.3;'>" +weiboText+"</span>");
	  var $text_td = $("<td valign='top'></td>");
	  $text_td.append($text_span);
	  
	  var $tr1 = $("<tr></tr>");
	  $tr1.append($quote_td);
	  $tr1.append($text_td);
	  
	  //second part
	  var $name_span = $("<span class='author' style='color: #000; font-weight: bold;font-size:12px;'>"+from+"</span>");
	  var $name_a = $("<a href='http://twitter.com/ReallyVirtual' style='text-decoration: none;' target='_blank'></a>");
	  $name_a.append($name_span);
	  
	  //var $time_a = $("<a href='http://twitter.com/ReallyVirtual/status/64780730286358528' style='color: #939393; text-decoration: none; margin-left: 5px; font-size:11px;' target='_blank'>"+time+"</a>");
	  var $time_a = $("<a href='http://weibo.com/"+from_id+"' style='color: #939393; text-decoration: none; margin-left: 5px; font-size:11px;' target='_blank'>"+time+"</a>");
	  var $time_span = $("<span class='timestamp' style='display: block;'>"); 
	  $time_span.append($time_a);
	  
	  var $user_td = $("<td align='right'></td>");
	  $user_td.append($name_a);
	  $user_td.append($time_span);
	  
	  var $photo_img = $("<img style='width: 32px; height: 32px; float: left; overflow: hidden; margin-left: 10px;' src='"+photo+"' alt='"+from+"' border=0 />");
	  var $photo_a = $("<a href='http://weibo.com/"+from_id+"' target='_blank'></a>");
	  $photo_a.append($photo_img);
	  
	  var $photo_td = $("<td></td>");
	  $photo_td.append($photo_a);
	  
	  var $inner_tr = $("<tr></tr>");
	  $inner_tr.append($user_td);
	  $inner_tr.append($photo_td);
	  
	  var $inner_table = $("<table border=0 cellpadding=1 cellspacing=1 align='right'></table>");
	  $inner_table.append($inner_tr);
	  
	  var $content_td = $("<td></td>");
	  $content_td.append($inner_table);
	  
	  var $tr2 = $("<tr></tr>");
	  $tr2.append($content_td);
	  
	  var $table = $("<table class='showborder weibo_table' border=0 cellpadding=1 cellspacing=1 align='center' width='370' style='padding-bottom:25px;'></table>");
	  $table.append($tr1);
	  $table.append($tr2);
	  */
	  
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
	  
	  var elem = '';
	  $('.weibo_drag').draggable({
	  helper: 'clone',
	  opacity: 0.55,
	  start:function(e, ui)
      {
          elem = e.srcElement || e.target;
      }
	  });
	  
	  $('#story_pane').droppable({
	  accept: '.weibo_drag',
	  activeClass: 'droppable-active',
	  hoverClass: 'droppable-hover',
	  tolerance: 'touch',
	  drop: function(ev, ui) 
      {
		//var srcElem = ev.srcElement || ev.target;
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