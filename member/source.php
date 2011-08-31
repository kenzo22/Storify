<?php
include "../global.php";
$result=$DB->fetch_one_array("SELECT weibo_user_id, tweibo_access_token, douban_access_token, yupoo_token FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if(intval($result['weibo_user_id']) == 0)
{
  $weibo_status = "未添加帐号";
  $weibo_action = "添加";
}
else
{
  $weibo_status = "已添加帐号";
  $weibo_action = "删除";
}
if($result['tweibo_access_token'] == '')
{
  $tweibo_status = "未添加帐号";
  $tweibo_action = "添加";
}
else
{
  $tweibo_status = "已添加帐号";
  $tweibo_action = "删除";
}

if($result['douban_access_token'] == '')
{
  $douban_status = "未添加帐号";
  $douban_action = "添加";
}
else
{
  $douban_status = "已添加帐号";
  $douban_action = "删除";
}

if($result['yupoo_token'] == '')
{
  $yupoo_status = "未添加帐号";
  $yupoo_action = "添加";
}
else
{
  $yupoo_status = "已添加帐号";
  $yupoo_action = "删除";
}

$content = "<div class='inner' style='padding-top:50px;'>
<h3>社会媒体信息，社交网络资讯是口立方的源头活水</h3>
<h3>为了您更好的使用口立方，我们建议您添加下面的信息源:</h3>
<p>您之后可以在 设置 -> 第三方应用授权 里作出更改</p>
<ul id='source_ul'>
  <li><a href='#'><img src='/storify/img/sina32.png' title='新浪微博' /><span style='margin-left:150px;' class='source_name'>新浪微博</span></a><span class='source_status'>".$weibo_status."<a id='sina_weibo' class='unbind_source'>".$weibo_action."</a></span></li>
  <li><a href='#'><img src='/storify/img/tencent32.png' title='腾讯微博' /><span style='margin-left:150px;' class='source_name'>腾讯微博</span></a><span class='source_status'>".$tweibo_status."<a id='tencent_weibo' class='unbind_source'>".$tweibo_action."</a></span></li>
  <li><a href='#'><img src='/storify/img/logo_douban.png' title='豆瓣社区' width='32px' height='32px'/><span style='margin-left:150px;' class='source_name'>豆瓣社区</span></a><span class='source_status'>".$douban_status."<a id='douban_forum' class='unbind_source'>".$douban_action."</a></span></li>
  <li><a href='#'><img src='/storify/img/yupoologo.png' title='又拍社区' /><span style='margin-left:102px;' class='source_name'>又拍社区</span></a><span class='source_status'>".$yupoo_status."<a id='yupoo_pic' class='unbind_source'>".$yupoo_action."</a></span></li>
  <li><a id='youku_video' href='#'><img src='/storify/img/youkulogo.gif' title='优酷视频' /><span style='margin-left:94px;' class='source_name'>优酷视频</span></a><span class='source_status'>无需添加帐号</span></li>
</ul>
<div class='float_r'>
  <span>&gt;&nbsp;<a href='./user_setting.php'>回到基本设置</a></span>
</div>
</div>";
echo $content;
?>

<script>
$(function(){
$('#sina_weibo').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('weibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('weibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#sina_weibo').text('添加');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#tencent_weibo').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('tweibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('tweibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#tencent_weibo').text('添加');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#douban_forum').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('doubansource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('doubansource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#douban_forum').text('添加');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#yupoo_pic').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('yupoosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('yupoosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#yupoo_pic').text('添加');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});
});
</script>

<?php
include "../include/footer.htm";
?>