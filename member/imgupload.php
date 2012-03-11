<?php
header("Content-type: text/html; charset=utf-8");
require $_SERVER['DOCUMENT_ROOT']."/include/functions.php";

$fileSize = $_FILES['photofile']['size']; 
if($fileSize > 2*1024*1024)
{
  echo "<div class='bind_txt'><div class='imply_color'>请选择小于2M的照片</div></div>";
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
	$image_extention = strtolower($temp_array[$length - 1]);
	$ranstr=produce_random_string();
	$current_time = time();
	$filename=$ranstr.$current_time.".".$image_extention;
	$local_file=$upload_dir.$filename;
	if(!move_uploaded_file($_FILES['photofile']['tmp_name'],$local_file))
	{
		echo "<div class='bind_txt'><div class='imply_color'>上传失败了，请您稍后再试</div></div>";
	}
    
    // compress the image with Imagick
    try{
        $im= new Imagick();
        if($image_extention != "jpg" &&  $image_extention !="jpeg"){
            $im->readImage($local_file);
            $im->setImageFormat('jpeg');
            $newFileName=$ranstr.$current_time.".jpg";
            $destFileName=$upload_dir.$newFileName;
            $im->writeImage($destFileName);
            unlink($local_file);
            $filename=$newFileName;
            $local_file=$destFileName;
        }
        $im->readImage($local_file);
        if($im->getImageWidth() > 600){
            $im->scaleImage(600,0);
        }
        $im->setImageCompression(Imagick::COMPRESSION_JPEG);
        $im->setImageCompressionQuality(80);
        $im->writeImage();

        $im->clear();
        $im->destroy();
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
	$stored_file="/img/upload/".$filename;
	chmod($local_file,0644);
	echo "<li class='img_upload_drag'><div class='cross'></div><div class='img_wrapper'><img src='".$stored_file."' /></div></li>";
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
  echo "<div class='bind_txt'><div class='imply_color'>不支持的图片类型</div></div>";
}
?>
