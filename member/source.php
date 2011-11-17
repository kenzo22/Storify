<?php
$html_title = "第三方授权 - 口立方";
require "../global.php";
require  "../include/header.php";
if(!islogin())
{
  header("location: /login/login_form.php"); 
  exit;
} 
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

$content = "<div style='height:30px;'></div>
<div class='inner'>
<h4 class='text'>你可以用口立方报道新闻，追踪网络热点事件，汇总美食，旅游，时尚周边信息，写书评影评，等等～～～</h4>
<h4 class='text'>为了您更好的使用口立方，我们建议您添加下面的信息源:</h4>
<p class='text'>您之后可以在 设置 -> 第三方应用授权 里作出更改</p>
<ul id='source_ul'>
  <li><span class='source_status'>".$weibo_status."<a id='sina_weibo' class='unbind_source' href='#'>".$weibo_action."</a></span><a href='http://weibo.com' target='_blank' class='sina_source' title='新浪微博'><span>新浪微博</span></a></li>
  <li><span class='source_status'>".$tweibo_status."<a id='tencent_weibo' class='unbind_source' href='#'>".$tweibo_action."</a></span><a href='http://t.qq.com' target='_blank' class='tencent_source' title='腾讯微博'><span>腾讯微博</span></a></li> 
  <li><span class='source_status'>".$douban_status."<a id='douban_forum' class='unbind_source' href='#'>".$douban_action."</a></span><a href='http://www.douban.com' target='_blank' class='douban_source' title='豆瓣社区'><span>豆瓣社区</span></a></li>
  <li><span class='source_status'>".$yupoo_status."<a id='yupoo_pic' class='unbind_source' href='#'>".$yupoo_action."</a></span><a href='http://www.yupoo.com' target='_blank' class='yupoo_source' title='又拍社区'><span>又拍社区</span></a></li>
  <li><span class='source_status'>无需添加帐号</span><a id='youku_video' target='_blank' href='http://www.youku.com' class='youku_source' title='优酷视频'><span>优酷视频</span></a></li>
</ul>
<div class='float_r'>
  <span>&gt;&nbsp;<a href='./settings.php'>回到基本设置</a></span>
</div>
</div>
<div style='height:40px;'></div>";
echo $content;
include "../include/footer.htm";
?>

<script type='text/javascript' src='../js/source.js'></script>
</body>
</html>
