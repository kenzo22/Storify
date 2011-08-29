<?php
include "../config/global_config.php";
require_once "../connect_db.php";
require_once "../include/functions.php";
session_start();

$story_id=$_POST['story_id'];
$story_title=$_POST['story_title'];
$story_summary=$_POST['story_summary'];
$story_tag=$_POST['story_tag'];
$story_pic=$_POST['story_pic'];
$story_content=$_POST['story_content'];

$tag_table=$db_prefix."tag";
$tag_story_table=$db_prefix."tag_story";

$pulish_time=date("Y-m-d H:i:s");
$post_id = $story_id;
if(0 == $story_id)
{
    $DB->query("insert into ".$db_prefix."posts values
                         (null, '".$_SESSION['uid']."', '".$pulish_time."', '".$pulish_time."', '".$story_title."', '".$story_summary."', '".$story_pic."','".$story_content."', '".Published."', '".$pulish_time."', '".$pulish_time."', 0)");

//get the post_id
    $result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."' AND post_date='".$pulish_time."'" );
    $post_id = intval($result['ID']);
    $story_id=$post_id;

// insert tags into database
    $tag_array=preg_split('/[:;,\s；，]+/',$story_tag);
    foreach($tag_array as $element){
        $query="select id from ".$tag_table." where name='".$element."'";
        $result=$DB->query($query);
        if($DB->num_rows($result) == 0){
            $query="insert into ".$tag_table." (name) values ('".$element."')";
            $DB->query($query);
        }

        $query="select id from ".$tag_table." where name='".$element."'";
        $result=$DB->query($query);
        if($DB->num_rows($result) != 1)
            echo "标签必须是唯一的";
        $row=$DB->fetch_array($result);
        $tag_id=$row[0];

        // check, this tag and story_id shouldn't be binded in tag_story table.
        $results=$DB->query("select * from ".$tag_story_table." where tag_id=".$tag_id." and story_id=".$story_id);
        if($DB->num_rows($results) !=0)
            echo "故事".$story_id."已经有标签".$element;
        $query="insert into ".$tag_story_table." (tag_id, story_id) values ('".$tag_id."','".$story_id."')";
        $DB->query($query);
    }
}
else
{
    $result=$DB->query("update ".$db_prefix."posts set post_title='".$story_title."', post_summary='".$story_summary."', post_pic_url='".$story_pic."',post_content='".$story_content."', post_status='Published'  WHERE ID='".$post_id."'");


    // update tags in the database
    $tag_array=preg_split('/[:;,\s；，]+/',$story_tag);

    // get the array for current story_id
    $query="select tag_id from ".$tag_story_table. " where story_id=".$story_id;
    $results=$DB->query($query);
    $tag_id_array=array();
    while($row=$DB->fetch_array($results))
        $tag_id_array[]=$row[0];

    // proceed input tags
    /* exits?     tag_table             tag_story_table
    //              no                  add (double check)
    //              yes                (tag,story) exits?
    //                                      yes, remove from list
    //                                      no, add 
    //                                  remove obsolete (tag,story) 
    */
    foreach($tag_array as $element){
        $query="select id from ".$tag_table." where name='".$element."'";
        $result=$DB->query($query);
        if($DB->num_rows($result) == 0){
            
            if (in_array($element,$tag_id_array))
                echo "出错了";

            $query="insert into ".$tag_table." (name) values ('".$element."')";
            $DB->query($query);
      
            $query="insert into ".$tag_story_table." (tag_id, story_id) values ('".$tag_id."','".$story_id."')";
            $DB->query($query);
        
        }else{

            if($DB->num_rows($result) != 1)
                echo "出错了";
            $onerow=$DB->fetch_array($result);
            $tag_id=$onerow[0];

            $query="select * from ".$tag_story_table." where tag_id=".$tag_id." and story_id=".$story_id;
            $results=$DB->query($query);
            if($DB->num_rows($results)==0){
                $query="insert into ".$tag_story_table." (tag_id, story_id) values ('".$tag_id."','".$story_id."')";
                $DB->query($query);
            }else{
                $idx=array_search($tag_id,$tag_id_array);
                array_splice($tag_id_array,$idx,1);
            }

        }
    }
    // remove the obsolte tag for current story_id
    if(sizeof($tag_id_array) > 0){
        foreach ($tag_id_array as $element){
            $query="select * from ".$tag_story_table." where tag_id=".$element." and story_id=".$story_id;
            $results=$DB->query($query);
            if($DB->num_rows($results) ==0)
                echo "故事".$story_id."应该还有标签要删除";
            $query="delete from ".$tag_story_table." where tag_id=".$element." and story_id=".$story_id;
            $DB->query($query);
        }
    }
    
}
$redirect_url = "/storify/member/user.php?post_id=".$post_id;
echo $redirect_url;

?>
