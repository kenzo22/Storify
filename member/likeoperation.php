<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/user_auth_fns.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php';
session_start();

if(!islogin())
{
  exit;
}

$operation = $_POST['operation'];
$user_id=intval($_POST['uid']); 
if($user_id != intval($_SESSION['uid']))
    exit;
$post_id=intval($_POST['pid']);

if($operation == 'add_like')
{
    $sql="SELECT postid_str FROM story_favor where user_id=".$user_id;
    $result=$DB->query($sql);
    $num=$DB->num_rows($result);
    
    if($num == 0){
        $cmd="INSERT INTO story_favor SET user_id=".$user_id.",postid_str='".$post_id."'";
    }
    elseif($num == 1){
        $row=$DB->fetch_array($result); 
        //echo $row['postid_str'];
        $post_s=$row['postid_str'].":".$post_id;
        $cmd="UPDATE story_favor SET postid_str='".$post_s."'";
    }else{
        exit;
    }
    $DB->query($cmd);
}elseif($operation == 'del_like'){
    $sql="SELECT postid_str FROM story_favor WHERE user_id=".$user_id;
    $result=$DB->query($sql);
    if($DB->num_rows($result)!= 1){
        exit;
    }
    $row=$DB->fetch_array($result); 
    $tmp_array=explode(":",$row['postid_str']);
    $idx=array_search($post_id,$tmp_array);
    if($idx !== false){
        array_splice($tmp_array,$idx,1);
        if($tmp_array){
            $post_s=implode(":",$tmp_array);
            $cmd="UPDATE story_favor SET postid_str='".$post_s."'";
        }else{
            $cmd="DELETE FROM story_favor WHERE user_id=".$user_id;
        }
        $DB->query($cmd);
    }
}

?>
