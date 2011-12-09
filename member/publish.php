<?php
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/functions.php";
require ($_SERVER['DOCUMENT_ROOT'].'/include/secureGlobals.php');
session_start();

$action=$_POST['action'];
$story_id=$_POST['story_id'];
$story_title=$_POST['story_title'];
$story_summary=$_POST['story_summary'];
$story_tag=$_POST['story_tag'];
$story_pic=$_POST['story_pic'];
if($story_pic == '/img/story_dft.jpg')
{
  $story_pic = '';
}
$story_content=$_POST['story_content'];

$tag_table=$db_prefix."tag";
$tag_story_table=$db_prefix."tag_story";

$pulish_time=date("Y-m-d H:i:s");
$post_id = $story_id;

if($action == 'Publish')
    $post_status = 'Published';
else if($action == 'Preview' || $action == 'Draft')
    $post_status = 'Draft';

mb_regex_encoding("utf-8");

if(0 == $story_id)
{
    $embed_name_l = 12;
    $embed_name=produce_random_strdig($embed_name_l);
	$DB->query("insert into ".$db_prefix."posts values
                         (null, '".$_SESSION['uid']."', '".$pulish_time."', '".$pulish_time."', '".$embed_name."', '".$story_title."', '".$story_summary."', '".$story_pic."','".$story_content."', '".$post_status."', '".$pulish_time."', '".$pulish_time."', 0, 0)");

//get the post_id
    $result=$DB->fetch_one_array("SELECT ID FROM ".$db_prefix."posts where post_author='".$_SESSION['uid']."' AND post_title='".$story_title."' AND post_date='".$pulish_time."'" );
    $post_id = intval($result['ID']);
    $story_id=$post_id;

    if(preg_match('/^\s*$/',$story_tag) == 0)
    {
        $tag_array=mb_split('[:;,\s，：；]+',$story_tag);
        $tag_array = array_unique($tag_array);
        foreach($tag_array as $element)
	{
            $query="select id from ".$tag_table." where name='".$element."'";
            $result=$DB->query($query);
            if($DB->num_rows($result) == 0){
                $query="insert into ".$tag_table." (name) values ('".$element."')";
                $DB->query($query);
            }

            $query="select id from ".$tag_table." where name='".$element."'";
            $result=$DB->query($query);
            if($DB->num_rows($result) > 1)
                echo "标签必须是唯一的";
            elseif($DB->num_rows($result) < 1 )
                echo "插入标签失败: ".$element;

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
}
else
{
    $result=$DB->query("update ".$db_prefix."posts set post_title='".$story_title."', post_summary='".$story_summary."', post_pic_url='".$story_pic."',post_content='".$story_content."', post_status='".$post_status."'  WHERE ID='".$post_id."'");

    // get the array for current story_id
    $query="select tag_id from ".$tag_story_table. " where story_id=".$story_id;
    $results=$DB->query($query);
    $tag_id_array=array();
    while($row=$DB->fetch_array($results))
        $tag_id_array[]=$row[0];

    if(preg_match('/^\s*$/',$story_tag) == 0)
    {
        // update tags in the database
        $tag_array=mb_split('[:;,\s，：；]+',$story_tag);
    
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
                $query="insert into ".$tag_table." (name) values ('".$element."')";
                $DB->query($query);
    
                $result=$DB->fetch_one_array("select * from ".$tag_table." where name='".$element."'");
                $tag_id = $result['id'];
    
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
        }       // tag_array foreach;
    }    // preg_match;
    // remove the obsolte tag for current story_id
    if(sizeof($tag_id_array) > 0){
        foreach ($tag_id_array as $element){
            $query="select * from ".$tag_story_table." where tag_id=".$element." and story_id=".$story_id;
            $results=$DB->query($query);
            if($DB->num_rows($results) ==0)
                echo "故事".$story_id."应该还有标签要删除";
            $query="delete from ".$tag_story_table." where tag_id=".$element." and story_id=".$story_id;
            $DB->query($query);
            $query = "select * from ".$tag_story_table." where tag_id=".$element;
            $result= $DB->query($query);
            if($DB->num_rows($result) == 0)
                $query = "delete from ".$tag_table." where id=".$element;
                $DB->query($query);
        }
    }
    
}
if($action == 'Publish' || $action == 'Preview')
{
  $redirect_url = "/user/".$_SESSION['uid']."/".$post_id;
}
    
else if($action == 'Draft')
{
  $redirect_url = "/user/".$_SESSION['uid'];
}
echo $redirect_url;

?>
