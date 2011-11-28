<?php
$html_title = "用户设置 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require  $_SERVER['DOCUMENT_ROOT']."/include/header.php";
if(!islogin())
{
  header("location: /login/login_form.php"); 
  exit;
}
$uid=intval($_SESSION['uid']);

$result=$DB->fetch_one_array("select * from story_user where id=".$uid);
if(!empty($result['photo']))
{
  if(substr($result['photo'], 0, 4) == 'http')
  {
     if(substr($userresult['photo'], 11, 4) == 'sina')
	 {
	   $pattern = "/(\d+)\/50\/(\d+)/";
	   $user_profile_img = preg_replace($pattern,"$1/180/$2",$result['photo']);
	 }
	 else
	 {
	   $pattern = "/50$/";
	   $user_profile_img = preg_replace($pattern,'100',$result['photo']);
	 }
	$userphoto="<img class='user_photo' src='".$user_profile_img."'> </img><div><a class='update_profile_img' href='/accounts/uploadphoto'>更换头像</a></div>";
  }
  else
  {
    $userphoto="<img class='user_photo' src='".$result['photo']."'> </img><div><a class='update_profile_img' href='/accounts/uploadphoto'>更换头像</a></div>";
  } 
}    
else
{
  $userphoto="<a href=/accounts/uploadphoto'>放你的头像上来</a>";
}			
$user_set = "<div class='inner'>
			<div class='page_title'>".$result['username']."的帐号"."</div>
			<div class='setting_bar'>
			  <div>
				<span class='now'>
				  <span>基本设置</span>
				</span>
				<a href='/accounts/source'>第三方应用授权</a>
			  </div>
			</div>
			<div class='clear'></div>
			<form id='lzform' name='lzform' method='post'>
			   <table align='center' cellpadding='5'>
				 <tr>
				   <td valign='top' align='right'>名　号: </td>
				   <td valign='top'>
					 <input id='user_name' name='user_name' type='text' size='15' maxlength='15' value='".$result['username']."'/>
					 <br/>名号30天内只能修改一次。<br/>
				   </td>
				 </tr>
				 <tr>
				   <td valign='top' align='right'>头　像:</td>
				   <td valign='top'>".$userphoto."</td>
				 </tr>
				 <tr>  
				   <td valign='top' align='right'>个人介绍:</td> 
				   <td  colspan=2 ><textarea id='user_intro' cols=80 rows=10 name='intro'>".$result['intro']."</textarea></td>  
				 </tr>
				 <tr>
				   <td align='right'>登录邮箱: </td>
				   <td valign='top'>
					 <span>".$result['email']."</span>
					 <a href='/accounts/change_email'>更改</a>
				   </td>
				 </tr>
				 <tr> 
				 <tr>
				   <td align='right'>登录密码: </td>
				   <td valign='top'>
					 <a href='/accounts/change_pwd'>更改</a>
				   </td>
				 </tr>
				 <tr>
					<td></td>
					<td><div id='update_btn'><a class='large blue awesome'>更新设置 &raquo;</a></div></td>
				 </tr>
			   </table>
			</form>
			</div>";
echo $user_set;
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm"
?>

<script type="text/javascript">
$(function()
{
  $('#update_btn a').click(function(e)
  {
	e.preventDefault();
	$('.update_notify').remove();
	var username_val = $('#user_name').val();
	var userintro_val = $('#user_intro').val();
	var postdata = {username: username_val, userintro: userintro_val};			  
    $.post('/accounts/modifysetting', postdata,
    function(data, textStatus)
    {
	  if("success" == textStatus)
	  {
	    $('#lzform').before(data);
	  }
    });
  });
});
</script>
</body>
</html>
