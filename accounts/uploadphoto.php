<?php
$html_title = "上传照片 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
$uid=intval($_SESSION['uid']);

$result=$DB->fetch_one_array("select * from story_user where id=".$uid);

if($_POST['act'] == 'uploadphoto')
{
	if(!islogin())  
		header("location:/accounts/login/");

    $err_code=$_FILES['photofile']['error'];
    if ($err_code > 0)
    {
	  echo "<div class='inner'><div class='page_title'>出错了</div>";
	  switch($err_code) 
	  {
		case 1: echo "<div>照片超出了允许上传的最大尺寸</div>";
				break;
		case 2: echo "<div>照片超出了最大尺寸，请选择小于1M的照片</div>";
				break;
		case 3: echo "<div>上传出错，请您再试一次</div>";
				break;
		case 4: echo "<div>您还没有选择照片</div>";
				break;
		case 6: echo "<div>上传失败，请您稍后再试</div>";
				break;
		case 7: echo "<div>上传失败，请您稍后再试</div>";
				break;
	  }
	  echo "<div class='spacer'></div><a class='large blue awesome' href='/accounts/uploadphoto'>重新上传 &raquo;</a><div class='footer_spacer'></div></div>";
    }
	else if(($_FILES["photofile"]["type"] == "image/png") || ($_FILES["photofile"]["type"] == "image/gif") || ($_FILES["photofile"]["type"] == "image/jpeg") || ($_FILES["photofile"]["type"] == "image/jpg") ||
	($_FILES["photofile"]["type"] == "image/pjpeg") || ($_FILES["photofile"]["type"] == "image/bmp"))	
	{
	  $upload_dir= "../img/user/"; 
	  $original=htmlspecialchars(trim($_FILES['photofile']['name']));
	  $type=$_FILES['photofile']['type'];
	  $size=$_FILES['photofile']['size'];
	  if (is_uploaded_file($_FILES['photofile']['tmp_name']) )
	  {
		$reslut=$DB->fetch_one_array("select photo from ".$db_prefix."user where ID=".$uid);
		if(!empty($reslut['photo']))
		{
		  if(substr($result['photo'], 0, 4) != 'http')
		  {
			unlink($upload_dir.basename($reslut['photo']));
		  }
		}		
		$temp_array = explode(".",$original);
		$length = count($temp_array);
		$image_extention = $temp_array[$length - 1];
					$ranstr=produce_random_string();
		$filename=$ranstr.$uid.".".$image_extention;
		$local_file=$upload_dir.$filename;
					// Document root is /storify
					$stored_file="/img/user/".$filename;
		if(!move_uploaded_file($_FILES['photofile']['tmp_name'],$local_file))
		{
			echo "<div class='inner'><div class='page_title'>出错了</div><div>上传失败了，请您稍后再试</div><div class='spacer'></div><a class='large blue awesome' href='/accounts/uploadphoto'>重新上传 &raquo;</a><div class='footer_spacer'></div></div>";
		}
		chmod($local_file,0755);
		$DB->query("update ".$db_prefix."user set photo='".$stored_file."' where  ID=".$uid);
		header("location: /accounts/setting"); 
		/*echo "<script language='javascript' >
			window.onload = function()
			{
			  debugger;
			  var imgPath = '$local_file_absolute';
			  $('.user_profile_img').removeChildren().html(<img width='80px' src='"+imgPath+"' />);
			};
			</script>";*/
	  }
	  else
	  {
		echo "可能出现文件上传攻击。文件名:";
		echo $_FILES['photofile']['name'];
		exit;
	  }
	}
	else
	{
	  echo "<div class='inner'><div class='page_title'>出错了</div><div>不支持的图片类型</div><div class='spacer'></div><a class='large blue awesome' href='/accounts/uploadphoto'>重新上传 &raquo;</a><div class='footer_spacer'></div></div>";
	}
}
else
{
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
		$userphoto="<div id='user_profile_img'><img width='80px' height='80px' src='".$user_profile_img."' /> </div>";
	  }
	  else
	  {
		$userphoto="<div id='user_profile_img'><img width='80px' height='80px' src='".$result['photo']."' /> </div>";
	  } 
	}    
	else
	{
	  $userphoto="<div id='user_profile_img'>暂无头像</div>";
	}
	$content = "<div class='inner'><form id='upload_form' name='form1'  method='post'  encType='multipart/form-data' target='hidden_frame' >
	<h1 class='page_title'>添加或更改您的头像</h1>
	<div style='float:left; margin-right:40px;'>".$userphoto."</div>
	<div style='margin-left:120px;'>
	  <h3>头像将会显示在故事的作者信息中。</h3>
	  <div style='margin-bottom:10px;'>你可以上传JPG、JPEG、GIF、PNG或BMP文件。</div>
	  <div>
		<input type='hidden' name='MAX_FILE_SIZE' value='1000000' />  
		<input type='file' id='upfile' name='photofile' value='".$result['username']."' style='height:22px;' />
	  </div> 
	  <div style='margin-top:15px;'>
		<a id='upload_btn' class='large blue awesome'>上传照片 &raquo;</a>
		<input type='hidden' name='act' value='uploadphoto' />
	  </div> 
	</div>
	<div></div> 
	</form>
	<div style='height:240px;'></div>
	</div>";
	echo $content;
}

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
