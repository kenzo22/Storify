<?php
function getPopularTags($n)
{
    global $DB;
    global $MAX_DAYS;
    
    if($n <=0)
        return null;

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
    $sentinel = 0;
    $popularTags=array();
    $temp_key=array();
    $temp_array=array();
    foreach($tag_story_array as $key=>$value)
    {
        if(sizeof($popularTags) >= $n)
            break;

        if(!$sentinel) {
            $sentinel = $value;
            $temp_key[] = $key;
            $temp_array[$key] = $value;
            continue;
        }
        if($sentinel > $value){
            $sentinel = $value;
            if(sizeof($popularTags) + sizeof($temp_key) <= $n){
                foreach($temp_key as $item)
                    $popularTags[]=$item;
                $temp_key=array();
                $temp_array=array();
            }else{
                $left = $n - sizeof($popularTags); 
                $key_rr = array_rand($temp_array,$left);
                foreach($key_rr as $item)
                    $popularTags[]=$item;
                $temp_array=array();
            }
        }
        $temp_key[] = $key;
        $temp_array[$key] = $value;
    }
    return $popularTags;
}

?>
