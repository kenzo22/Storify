<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<script type="text/javascript" src="/storify/js/jquery.js"></script>
		<script type="text/javascript" src="http://js.wcdn.cn/t3/platform/js/api/wb.js" charset="utf-8"></script>
	</head> 
	<body>
	<div id='weibo_div'>test weibo</div>
	<script type="text/javascript">		
		WB.core.load(['connect', 'client'], function() 
		{
			var cfg = {
				  key: '314237338',
				  xdpath: 'http://story.com/storify/html/xd.html'
				};
			WB.connect.init(cfg);
			WB.client.init(cfg);
		});
		
		function get_weibo_by_id(ID)
		{
		    var weibo_permanent_id = ID;
			WB.client.parseCMD('/statuses/show/#{id}.json', function(sResult, bStatus)
		    {
			  debugger;
			  var weiboText, from, from_id, created_time, photo;
			  weiboText= sResult.text;
			  created_time = sResult.created_at;
			  //created_time = date_format(created_time);  
			  from = sResult.user.screen_name;
			  from_id = sResult.user.id;
			  photo = sResult.user.profile_image_url;
			  var content = ('<li>test</li>');
			  $('#weibo_div').append(content);
		   },
		    {
			  id : weibo_permanent_id
			},
		    {
			  method: 'get'
		    });
		}
		
		window.onload = function()
		{
		  get_weibo_by_id(11990420186);
		}
		/*$(document).ready(function() 
		{
		  get_weibo_by_id(11990420186);
		});*/
	</script>
	</body>
</html>