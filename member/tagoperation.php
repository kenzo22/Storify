<?php
function getPopularTags($n)
{
    global $DB;

    $tags_array=array();
    $tag_story_array=array();

    $query="select * from story_tag";
    $results=$DB->query($query);
    while($eachrow=$DB->fetch_array($results)){
        $tags_array[]=$eachrow[0];
    }

    foreach($tags_array as $tag_id){
        $query="select story_id from story_tag_story, story_posts where story_tag_story.story_id=story_posts.id and story_tag_story.tag_id=".$tag_id."
                and story_posts.posts_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_modified) <=7 ";
        $results=$DB->query($query);
        $num=$DB->num_rows($results);
        $tag_story_array[$tag_id]=$num;
    }
    arsort($tag_story_array);
    $i=0;
    $popularTags=array();
    foreach($tag_story_array as $key=>$value)
    {
        if(++$i > $n)
            break;
        $popularTags[]=$key;
    }
    return $popularTags;
}

?>
