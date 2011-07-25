<?php

function dateFormat($origin_date)
{
  $temp_array = explode(" ", $origin_date);
  switch($temp_array[1])
  {
	case "Jan":
		$temp_array[1] = 1;
		break;
	case"Feb":
		$temp_array[1] = 2;
		break;
	case"Mar":
		$temp_array[1] = 3;
		break;
	case"Apr":
		$temp_array[1] = 4;
		break;
	case"May":
		$temp_array[1] = 5;
		break;
	case"Jun":
		$temp_array[1] = 6;
		break;
	case"Jul":
		$temp_array[1] = 7;
		break;
	case"Aug":
		$temp_array[1] = 8;
		break;
	case"Sep":
		$temp_array[1] = 9;
		break;
	case"Oct":
		$temp_array[1] = 10;
		break;
	case"Nov":
		$temp_array[1] = 11;
		break;
	case"Dec":
		$temp_array[1] = 12;
		break;
	default:
		$temp_array[1] = $temp_array[1];
		break;
  }
  $time_array = explode(":", $temp_array[3]);
  $temp_array[3] = $time_array[0].":".$time_array[1];
  return $temp_array[5]."-".$temp_array[1]."-".$temp_array[2]." ".$temp_array[3];
}

function stripslashes_array(&$array) {
        while (list($k, $v) = each($array)) {
                if ($k != 'argc' && $k != 'argv' && (strtoupper($k) != $k || '' . intval($k) == "$k")) {
                        if (is_string($v)) {
                                $array[$k] = stripslashes($v);
                        } 
                        if (is_array($v)) {
                                $array[$k] = stripslashes_array($v);
                        } 
                } 
        } 
        return $array;
} 

function xy_isset($value) {
        $value = trim($value);
        if (isset($value) AND $value != "") {
                return true;
        } else {
                return false;
        } 
} 


function makepagelink($link, $page, $pages) {
        if (empty($pages)) return "<b>1</b>";
        if ($page != 1) {
                $pagelink .= " <a href=\"$link&page=1\" title=\"第一页\">&laquo;</a> <a href=\"$link&page=" . ($page-1) . "\">上一页</a>";
        } 
        if ($page >= 6) {
                $pagelink .= " <a href=\"$link&page=" . ($page-5) . "\">...</a>";
        } 
        if ($page + 4 >= $pages) {
                $pagex = $pages;
        } else {
                $pagex = $page + 4;
        } 
        for($i = $page-4;$i <= $pagex;$i++) {
                if ($i <= 0) {
                        $i = 1;
                } 
                if ($i == $page) {
                        $pagelink .= " <b>$i</b>";
                } else {
                        $pagelink .= " <a href=\"$link&page=$i\">$i</a>";
                } 
        } 
        if (($pages - $page) >= 5) {
                $pagelink .= " <a href=\"$link&page=" . ($page + 5) . "\">...</a>";
        } 
        if ($page != $pages) {
                $pagelink .= " <a href=\"$link&page=" . ($page + 1) . "\">下一页</a> <a href=\"$link&page=" . $pages . "\" title=\"最后一页\">&raquo;</a>";
        } 

        return $pagelink;
} 

function filled_out($form_vars) {
  // test that each variable has a value
  foreach ($form_vars as $key => $value) {
     if ((!isset($key)) || ($value == '')) {
        return false;
     }
  }
  return true;
}



function go($url,$info='',$time=0) { //提示信息
 if(!empty($info))
	{   $info.="<br>如果没有跳转请点<a href=$url>这里</a>,$time 秒钟后将自动返回";
		echo "<table height='200'><tr><td></td></tr></table><table  align='center' width='600' height='50' border='4'   bordercolor='#999999' style='   font-size:14px;'><tr><td bgcolor='#cfcfcf' align='center'> 提示信息：$info </td></tr></table>" ;
    }
   echo  "<META  HTTP-EQUIV=\"Refresh\"  CONTENT=\" $time;  URL=$url\" >" ;
   exit;
  
}

function show_error($info,$url) { //提示信息
   
   echo "<table height='200'><tr><td></td></tr></table><table  align='center' width='600' height='50' border='4'   bordercolor='#999999' style='   font-size:14px;'><tr><td bgcolor='#cfcfcf' align='center'> 出错了：".$info."<br>请点<a href=$url>这里</a>返回正确页面</td></tr></table>" ;
   echo  "<META  HTTP-EQUIV=\"Refresh\"  CONTENT=\" 2;  URL=$url\" >" ;
   exit;
  
}



function validate_articleid($articleid) {
        global $DB, $db_prefix,$siteurl;
        $articleid = intval($articleid);
        if (empty($articleid)) {
                show_information("该文章不存在或已经被删除！",$siteurl);
        } else {
                $articleinfo = $DB->fetch_one_array("SELECT * FROM " . $db_prefix . "article
                                                           WHERE articleid='$articleid' AND visible=1");
                if (empty($articleinfo)) {
                        show_information("该文章不存在或已经被删除！",$siteurl);
                } 
                return $articleinfo;
        } 
} 

function getip() {
        if (isset($_SERVER)) {
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                        $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                        $realip = $_SERVER["HTTP_CLIENT_IP"];
                } else {
                        $realip = $_SERVER["REMOTE_ADDR"];
                } 
        } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                        $realip = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_CLIENT_IP')) {
                        $realip = getenv('HTTP_CLIENT_IP');
                } else {
                        $realip = getenv('REMOTE_ADDR');
                } 
        } 
        return $realip;
} 



function validate_email($address) {
        if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' . '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address, $email)) {
                return true;
        } else {
                return false;
        } 
}

function valid_email($address) {
  // check an email address is possibly valid
  if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)) {
    return true;
  } else {
    return false;
  }
}


function getstuinfo($id,$pass)
{
	global $DB, $db_prefix;
$id=intval($id);
$pass=trim($pass);
$result=$DB->fetch_one_array("SELECT * FROM ".$db_prefix."student WHERE id='".$id."' and password='".$pass."'");
if (!empty($result))
	return  $result;
}


function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
 } 

function getoption($a,$id='')
{
  if ($id!='')
	{
     $content='';
	 while ( list( $i, $val) = each( $a ))
      {
		
		if ($i==$id)
		$content.="<option value='$i' selected>$val</option>";
		else
		$content.="<option value='$i' >$val</option>";		
	  }
	}
  else
	{
    $content="<option value='-1' selected>选择分类</option>";
	while ( list( $i, $val) = each( $a ))
    { 
	 
	 $content.="<option value='$i'>$val</option>";
    }
 }
   
  return $content;  

}  

function getcbox($a,$name,$id='')
{

if ($id!='')
	{
	  $content='';
	  while(list($i,$v)=each($a))
      {
	   if (strstr(",".$id.",",",".$i.","))
           $content.=" $v <input name='".$name."$i' TYPE='checkbox' checked> ";
	   else
		   $content.=" $v <input name='".$name."$i' TYPE='checkbox' > ";
	  }
   }
else
   {
     
	 while(list($i,$v)=each($a))
	 $content.=" $v <input name='".$name."$i' TYPE='checkbox' > ";

	}

  return $content;
}

function what(){

$realip = getenv('REMOTE_ADDR');
echo  "<a href='http://www.wheats.cn/ss'>power by sunson. hfut</a>";
}

function n2s($a,$n='',$fix=',')
{
  if ($n=='') $n=$a;

     $content='';
	 while ( list( $i, $val) = each( $a ))
      {
	    $t=$i+1;
		if (strstr(",".$n.",",",".$t.","))
		$content[]=$val;
	  }
   if (!empty($content)) return implode($fix,$content);  // sunson http//www.wheats.cn

}  

function makeradompw($length = 8, $list = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        mt_srand((double)microtime() * 1000000);
        $newstring = "";
        if ($length > 0) {
                while (strlen($newstring) < $length) {
                        $newstring .= $list[mt_rand(0, strlen($list)-1)];
                } 
        } 
        return $newstring;
} 

/*function islogin()
{
 global	$_SESSION;

 if (empty($_SESSION['uid']))
    return 0;
 else 
	return 1;
}*/

/*function islogin()
{
 global	$_SESSION;
 if(empty($_SESSION['uid']))
 {
   $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
   if(!empty($userinfo))
   {
     $_SESSION['uid']=intval($userinfo['id']);
     $_SESSION['username']=$userinfo['username'];
	 return 1;
   }
   return 0;
 } 
 else
 {
   return 1;
 } 	
}*/

function go_template($tpl_file)
{
global $rooturl,$siteurl;

$fp=@fopen($tpl_file,"r");
$tpl_content=@fread($fp,@filesize($tpl_file));
@fclose($fp);

echo $tpl_content;

include "footer.htm"; //尾部页面
}

function do_template($tpl_file,$tpl_var)
{
global $rooturl,$siteurl;

$fp=@fopen($tpl_file,"r");
$tpl_content=@fread($fp,@filesize($tpl_file));
@fclose($fp);
while ( list($v,$val) = each($tpl_var))
    {
	 global $$val;
     $tpl_content=str_replace("{".$v."}",$$val,$tpl_content);   
     }
  echo $tpl_content;

 include "footer.htm"; //尾部页面
}

/*function register($email, $password, $username) {
// register new person with db
// return true or error message

  // connect to db
  //$conn = db_connect();

  // check if email is unique
  $result = $DB->query("select * from ".$db_prefix."user where email='".$email."'");
  if (!$result) {
    throw new Exception('Could not execute query');
  }

  if ($result->num_rows>0) {
    throw new Exception('That email is taken - go back and choose another one.');
  }

  // if ok, put in db
  $result = $DB->query("insert into ".$db_prefix."user values
                         ('".$email."',  sha1('".$password."'), '".$username."')");
  if (!$result) {
    throw new Exception('Could not register you in database - please try again later.');
  }

  return true;
}*/

?>