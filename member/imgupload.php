<?php
header("Content-type: text/html; charset=utf-8");
require $_SERVER['DOCUMENT_ROOT']."/include/functions.php";

$fileSize = $_FILES['photofile']['size']; 
if($fileSize > 600000)
{
  echo "<div class='imply_color center'>请选择小于600K的照片</div>";
  exit;
}

if(($_FILES["photofile"]["type"] == "image/png") || ($_FILES["photofile"]["type"] == "image/x-png") || ($_FILES["photofile"]["type"] == "image/gif") || ($_FILES["photofile"]["type"] == "image/jpeg") || ($_FILES["photofile"]["type"] == "image/jpg") ||
($_FILES["photofile"]["type"] == "image/pjpeg") || ($_FILES["photofile"]["type"] == "image/bmp"))	
{
  $upload_dir= "../img/upload/"; 
  $original=htmlspecialchars(trim($_FILES['photofile']['name']));
  $type=$_FILES['photofile']['type'];
  $size=$_FILES['photofile']['size'];
  if (is_uploaded_file($_FILES['photofile']['tmp_name']) )
  {		
	$temp_array = explode(".",$original);
	$length = count($temp_array);
	$image_extention = $temp_array[$length - 1];
	$ranstr=produce_random_string();
	$current_time = time();
	$filename=$ranstr.$current_time.".".$image_extention;
	$local_file=$upload_dir.$filename;
	$stored_file="/img/upload/".$filename;
	if(!move_uploaded_file($_FILES['photofile']['tmp_name'],$local_file))
	{
		echo "<div class='imply_color center'>上传失败了，请您稍后再试</div>";
	}
	chmod($local_file,0755);
	echo "<li class='img_upload_drag'><div class='img_wrapper'><img src='".$stored_file."' /></div></li>";
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
  echo "<div class='imply_color center'>不支持的图片类型</div>";
}
?>