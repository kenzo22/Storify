<?php
function getPopularTags($n)
{
    global $DB;
    global $MAX_DAYS;
    
    if(!$MAX_DAYS)
        $MAX_DAYS=30;
    $tags_array=array();
    $tag_story_array=array();

    $query="select * from story_tag";
    $results=$DB->query($query);
    while($eachrow=$DB->fetch_array($results)){
        $tags_array[]=$eachrow[0];
    }

    foreach($tags_array as $tag_id){
        $query="select story_id from story_tag_story, story_posts where story_tag_story.story_id=story_posts.id and story_tag_story.tag_id=".$tag_id."
                and story_posts.post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS";
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
