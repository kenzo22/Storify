<?php
header("Content-type:image/jpeg");
   $filename="show.jpg";
   $fp=fopen($filename,"r");
   $img=fread($fp,filesize($filename));
   echo $img;
   fclose($fp);

   $ip=$_SERVER["REMOTE_ADDR"];
   $page=$HTTP_REFERER;
  // $time=time();
   $time_str=date("Y-m-d H:i:s");
   $fp=fopen("log.txt","a+");
   fwrite($fp,"$time_str      "."$ip         "."$page          "."\r\n");
   fclose($fp);

?>