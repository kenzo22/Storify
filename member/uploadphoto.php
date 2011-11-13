<?php
include "../global.php";
$uid=intval($_SESSION['uid']);

$result=$DB->fetch_one_array("select * from story_user where id=".$uid);

if($_POST['act'] == 'uploadphoto')
{
	if(!islogin())  
		go("/login","请先登录..",2);

    if ((($_FILES["photofile"]["type"] == "image/png") || ($_FILES["photofile"]["type"] == "image/gif") || ($_FILES["photofile"]["type"] == "image/jpeg") || ($_FILES["photofile"]["type"] == "image/jpg") ||
	($_FILES["photofile"]["type"] == "image/pjpeg") || ($_FILES["photofile"]["type"] == "image/bmp")) && ($_FILES["photofile"]["size"] < 5000000))	
	{
	  $err_code=$_FILESs['photofile']['error'];
	  if ($err_code > 0)
      {
        echo 'Problems:';
		switch($err_code) 
		{
			case 1: echo "File exceeded upload_max_filesize";
					break;
			case 2: echo "File exceeded max_file_size";
					break;
			case 3: echo "File only partially uploaded";
					break;
			case 4: echo "No File uploaded";
					break;
			case 6: echo "Cannot upload File: No temp directory specified";
					break;
			case 7: echo "Upload failed: Cannot write to disk";
					break;
		}
		exit;
      }
	  else
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
			    echo "无法将文件移到目的位置";
                            return;
			}
			chmod($local_file,0755);
			$DB->query("update ".$db_prefix."user set photo='".$stored_file."' where  ID=".$uid);
			header("location: ./user_setting.php"); 
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
	}
	else
	{ 
	  echo "<br/>";
	  echo "Invalid file";
	  echo $_FILES["photofile"]["size"];
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
	$content = "<div class='inner' style='padding-top:50px; margin-bottom:540px;'><form id='upload_form' name='form1'  method='post'  encType='multipart/form-data' target='hidden_frame' >
	<h1>添加或更改您的头像</h1>
	<h3>头像将会显示在故事的作者信息中</h3>
	<div style='float:left; margin-right:40px;'>".$userphoto."</div>
	<div style='margin-left:120px;'>
	  <div>从电脑中选择您喜欢的照片</div><br />
	  <div>你可以上传JPG、JPEG、GIF、PNG或BMP文件。</div><br />
	  <div style=''>
		<input type='hidden' name='MAX_FILE_SIZE' value='1000000' />  
		<input type='file' id='upfile' name='photofile' value='".$result['username']."' style='height:22px;' />
	  </div> 
	  <div style='margin-top:20px;'>
		<a id='upload_btn' class='large blue awesome'>上传照片 &raquo;</a>
		<input type='hidden' name='act' value='uploadphoto' />
	  </div> 
	</div>
	<div></div> 
	</form></div>";
	echo $content;
}

include "../include/footer.htm"
?>
<script type="text/javascript">
$('#upload_btn').click(function(e)
{
    $('#upload_form').submit();
})
</script>
