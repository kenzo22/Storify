<?php
include "../global.php";
$result=$DB->fetch_one_array("SELECT weibo_user_id, tweibo_access_token, yupoo_token FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
if(intval($result['weibo_user_id']) == 0)
{
  $weibo_status = "未绑定帐号";
}
else
{
  $weibo_status = "已绑定帐号";
}
if($result['tweibo_access_token'] == '')
{
  $tweibo_status = "未绑定帐号";
}
else
{
  $tweibo_status = "已绑定帐号";
}
if($result['yupoo_token'] == '')
{
  $yupoo_status = "未绑定帐号";
}
else
{
  $yupoo_status = "已绑定帐号";
}

$content = "<div class='inner' style='padding-top:50px;'>
<h3>社会媒体信息，社交网络资讯是口立方的源头活水</h3>
<h3>为了您更好的使用口立方，我们建议您添加下面的信息源:</h3>
<p>您之后可以在 设置 -> 第三方应用授权 里作出更改</p>
<ul id='source_ul'>
  <li><a id='sina_weibo' href='#'><img src='/storify/img/sina32.png'/><span style='margin-left:150px;' class='source_name'>新浪微博</span></a><span class='source_status'>".$weibo_status."</span></li>
  <li><a id='tencent_weibo' href='#'><img src='/storify/img/tencent32.png'/><span style='margin-left:150px;' class='source_name'>腾讯微博</span></a><span class='source_status'>".$tweibo_status."</span></li>
  <li><a id='yupoo_pic' href='#'><img src='/storify/img/yupoologo.png'/><span style='margin-left:102px;' class='source_name'>又拍社区</span></a><span class='source_status'>".$yupoo_status."</span></li>
  <li><a id='youku_video' href='#'><img src='/storify/img/youkulogo.gif'/><span style='margin-left:94px;' class='source_name'>优酷视频</span></a><span class='source_status'>无需绑定帐号</span></li>
</ul>
<ul id='source_info'></ul>
</div>";
echo $content;
?>

<script>
$(function(){
$('#sina_weibo').click(function(e){
e.preventDefault();
var postdata;
$.post('weibosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});

$('#tencent_weibo').click(function(e){
e.preventDefault();
var postdata;
$.post('tweibosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});

$('#yupoo_pic').click(function(e){
e.preventDefault();
var postdata;
$.post('yupoosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});
});
</script>

<?php
include "../include/footer.htm";
?>