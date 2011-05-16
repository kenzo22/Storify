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
  args['q'] = '丝袜';
  //var loginfo;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    $.each(sResult.results, function(i, result)
	{
	  //$.each(result, function(key, value)
	  //{
		//loginfo += result[key] + '\n';
	  //});
	  var weiboText= result.text;
	  var from = result.from_user;
	  var from_id = result.from_user_id;
	  var time = result.created_at;
	  var photo = result.profile_image_url;
	  
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
	  
	  var $table = $("<table border=0 cellpadding=1 cellspacing=1 align='center' width='370' style='padding-bottom:25px;'></table>");
	  $table.append($tr1);
	  $table.append($tr2);
	  
	  var $parent = $('.source_drag');
	  $parent.append($table);
	  //
	 
	  //
	  
	});  
    //log(loginfo);
  },
  args,
  {
    'method': type
  })
}