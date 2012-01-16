<?php
$html_title = "上传照片 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";

if(!islogin()){
    header("location:/");
    exit;
}

$uid=intval($_SESSION['uid']);
$result=$DB->fetch_one_array("select username,photo from story_user where id=".$uid);
$u_name = $result['username'];

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
	$userphoto="<div id='user_profile_img'><img width='120px' height='120px' src='".$user_profile_img."' alt='".$u_name."' /><img width='80px' height='80px' src='".$user_profile_img."' alt='".$u_name."' /><img width='25px' height='25px' src='".$user_profile_img."' alt='".$u_name."' /></div>";
  }
  else
  {
	$userphoto="<div id='user_profile_img'><img width='120px' height='120px' src='".$result['photo']."' alt='".$u_name."' /><img width='80px' height='80px' src='".$result['photo']."' alt='".$u_name."' /><img width='25px' height='25px' src='".$result['photo']."' alt='".$u_name."' /></div>";
  } 
}    
else
{
  $userphoto="<div id='user_profile_img'>暂无头像</div>";
}
$content = "<div class='inner'><form id='upload_form' method='post' encType='multipart/form-data' action='/accounts/photoedit'>
<h1 class='page_title'>添加或更改您的头像</h1>".$userphoto."<div class='clear'></div>
<div>
  <h3>头像将会显示在故事的作者信息中。</h3>
  <div style='margin-bottom:10px;'>你可以上传JPG、PNG、GIF格式的文件。</div>
  <div> 
	<input type='file' id='upfile' name='image' style='height:22px;' />
  </div>
  <div style='margin-top:15px;'>
	<a id='upload_btn' class='large blue awesome'>上传照片 &raquo;</a>
	<input type='hidden' name='act' value='upload' />
  </div>   
</div>
</form>
<div id='go_back_setting'>
  <span>&gt;&nbsp;<a href='/accounts/setting'>回到基本设置</a></span>
</div>
</div>";
echo $content;

include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm"
?>
<script type="text/javascript">
$('#upload_btn').click(function(e)
{
    $('#upload_form').submit();
})
</script>
</body>
</html>
