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
	  
	  var $table = ("<div class='weibo_div'><table class='showborder weibo_table' border=0 cellpadding=1 cellspacing=1 align='center' width='370' style='padding-bottom:25px;'><tr><td valign='top' rowspan=2>\
	  <span style='font-size: 64px; color: #ccc;'> &#8220; </span></td><td valign='top'><span class='text' style='color:#4A4A4B;margin-top:13px;display:block;font-size:10.5pt;line-height:1.3;'>" +weiboText+"</span>\
	  </td></tr><tr><td><table border=0 cellpadding=1 cellspacing=1 align='right'><tr><td align='right'><a href='http://twitter.com/ReallyVirtual' style='text-decoration: none;' target='_blank'>\
	  <span class='author' style='color: #000; font-weight: bold;font-size:12px;'>"+from+"</span></a><span class='timestamp' style='display: block;'>\
	  <a href='http://weibo.com/"+from_id+"' style='color: #939393; text-decoration: none; margin-left: 5px; font-size:11px;' target='_blank'>"+time+"</a></span></td><td>\
	  <a href='http://weibo.com/"+from_id+"' target='_blank'><img style='width: 32px; height: 32px; float: left; overflow: hidden; margin-left: 10px;' src='"+photo+"' alt='"+from+"' border=0 /></a></td></tr></table></td>\
	  </tr></table></div>");
	  var $parent = $('.source_drag');
	  $parent.append($table);
	   //$parent.append($weibo_div);
	  //
	  var elem = '';
	  $('.weibo_div').draggable({
	  helper: 'clone',
	  opacity: 0.55,
	  start:function(e, ui)
      {
          elem = e.srcElement || e.target;
      }
	  });
	  
	  $('#edit_pane').droppable({
	  accept: '.weibo_div',
	  activeClass: 'droppable-active',
	  hoverClass: 'droppable-hover',
	  drop: function(ev, ui) 
      {
		var content = $('.weibo_div').html();
		//var content = '<p>test<p>';
		var oid = elem.id;
		var sid = "s" + oid;       
        
		//有相同的就不插入了。
		if ( document.getElementById(sid) == null)
		{
            //$(this).append( "<div id='" + sid + "' title='"+ o +"' class='menunav'>" + "<a href='http://ioa.zte.com.cn'>" + content + "</a>" + "<a href='#' onclick='javascript:$(this.parentNode).remove();' title='删除此栏'> X</a></div>" );
			$(this).append('<div>'+content+'</div>');
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