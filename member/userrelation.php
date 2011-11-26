<?php
include_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
include_once $_SERVER['DOCUMENT_ROOT']."/include/functions.php";

$ftable=$db_prefix."follow";

session_start();

$uid=intval($_SESSION['uid']);
  
function follow($follow_uid)
{
        global $DB;
        global $ftable;
        global $uid;

        if($uid == $follow_uid){
                echo "出错了。关注自己！";
                exit;
        }

        $query="select * from ".$ftable." where user_id=".$uid." and follow_id=".$follow_uid;
        $result=$DB->query($query);
        $num=$DB->num_rows($result);
        // shouldn't reach here. If so, something is wrong. for redundant check.
        if($num > 0) {
                echo "您已经关注了此人";
                exit;
        }

	$query="insert into ".$ftable." (user_id, follow_id) values
                (".$uid.",".$follow_uid.")";
        if(!$DB->query($query))
                echo "数据库表格插入记录失败:".$query;

}

function unfollow($follow_uid)
{
        global $DB;
        global $ftable;
        global $uid;

        if($uid == $follow_uid){
                echo "出错了。尝试移除关注的是自己！";
                exit;
        }

        $query="select * from ".$ftable." where user_id=".$uid." and follow_id=".$follow_uid;
        $result=$DB->query($query);
        $num=$DB->num_rows($result);
        // shouldn't reach here. If here, database is modifyed somehow.
        if($num == 0) {
                echo "您没有关注此人";
                exit;
        }elseif($num > 1){
                echo "重复的关注记录";
                exit;
        }

        $query="delete from ".$ftable." where user_id=".$uid." and follow_id=".$follow_uid;
        if(!$DB->query($query))
                echo "数据库表格删除记录失败:".$query;
}

function getFollowing($author_id)
{
        global $DB;
        global $ftable;
        //global $uid;

        $query="select follow_id from ".$ftable." where user_id=".$author_id;
        $results=$DB->query($query);
        if(!$results){
                echo "数据库查询失败:".$results;
                exit;
        }
        $followings=array();
        while($one_row=$DB->fetch_array($results)){
                $followings[]=$one_row[0];
        }
        return $followings;
}

function getFollower($author_id)
{
        global $DB;
        global $ftable;
        //global $uid;

        $query="select user_id from ".$ftable." where follow_id=".$author_id;
        $results=$DB->query($query);
        if(!$results){
                echo "数据库查询失败:".$resultsp;
                exit;
        }
        $followers=array();
        while($one_row=$DB->fetch_array($results)){
                $followers[]=$one_row[0];
        }
        return $followers;
}
?>
