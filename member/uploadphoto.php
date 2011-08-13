<?php
include "../global.php";
$uid=intval($_SESSION['uid']);

$result=$DB->fetch_one_array("select * from story_user where id=".$uid);
if(!empty($result['photo']))
{
$userphoto="<div id='user_profile_img'><img width='90px' src='".$rooturl."/img/user/".$result['photo']."' /> </div>";
}    
else
{
$userphoto="<div id='user_profile_img'>暂无头像</div>";
}
$content = "<div class='inner' style='padding-top:50px;'><form name='form1'  method='post'  encType='multipart/form-data' target='hidden_frame' >
<h3>照片</h3>
<div>".$userphoto."</div>
<div>
<span>
  <input type='hidden' name='MAX_FILE_SIZE' value='1000000' />  
  <input type='file' id='upfile' name='photofile' value='".$result['username']."' />
  <input type='submit' value='上传照片' onclick='javascript:updatephoto();' />
  <input type='hidden' name='act' value='uploadphoto' />
</span> 
</div> 
</form></div>";
echo $content;

if($_POST['act'] == 'uploadphoto')
{
	if(!islogin())  
		go($rooturl."/login","请先登录..",2);			

	$err_code=$_FILESs['photofile']['error'];
	if($err_code != 0 ){
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

	$original=htmlspecialchars(trim($_FILES['photofile']['name']));
	$type=$_FILES['photofile']['type'];
	$size=$_FILES['photofile']['size'];

	$upload_dir= "../img/user/"; 

	$ftype=explode("/",$type);

	if($ftype[0] != "image"){
			echo "文件类型错误";
			exit;
	}

	if (is_uploaded_file($_FILES['photofile']['tmp_name']) ){
			$reslut=$DB->fetch_one_array("select photo from ".$db_prefix."user where ID=".$uid);
			if(!empty($reslut['Photo']))
					unlink($upload_dir.$reslut['photo']);

			$filename=$uid.substr($original,-4,4);
			$local_file=$upload_dir.$filename;
			if(!move_uploaded_file($_FILES['photofile']['tmp_name'],$local_file)){
					echo "无法将文件移到目的位置";
			}
			chmod($local_file,0755);
			$DB->query("update ".$db_prefix."user set Photo='".$filename."' where  ID=".$uid);
			//go($rooturl."/member/user_setting.php","上传照片成功",2);	
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
  
}

include "../include/footer.htm"
?>

<script language="javascript">

function updatephoto() 
{
	debugger;
	var path=document.getElementById("upfile").value;
	//$('.user_profile_img').attr("src", path);
	document.getElementById("user_profile_img").innerHTML="<img width='90px' src='"+path+"'>";
}
</script>