<?php

function subs_emotions($string,$img_parent_dir)
{
    preg_match("/(.*?)\/storify/",getcwd(),$abs_path_matches);
    $story_img_path="/storify/img/";

    // show emotions in text
    if($img_parent_dir == "weibo"){
        $pattern = "/\[(.*?)\]/";
    }elseif($img_parent_dir == "tweibo"){
        $pattern = "/\/([^\s]+)/";
    }
    preg_match_all($pattern,$string,$face_matches,PREG_SET_ORDER);
    if($face_matches){
        foreach($face_matches as $element){
            if($img_parent_dir == "tweibo"){
                for($i=1; $i<=strlen($element[1]); $i++){
                    $story_file =  $story_img_path."tweibo/".substr($element[1],0,$i).".gif";
                    $local_file = $abs_path_matches[1].$story_file;
                    if(is_readable($local_file)){
                        $img_replace = "<img src='".$story_file."'>";
                        $string= str_replace(substr($element[0],0,$i+1),$img_replace,$string);
                        break;
                    }
                }
            }elseif($img_parent_dir == "weibo"){
                $story_file=$story_img_path."weibo/".$element[1].".gif";
                $local_file=$abs_path_matches[1].$story_file;
                if(is_readable($local_file)){
                    $img_replace="<img src='".$story_file."'>";
                    $string=str_replace($element[0],$img_replace,$string);
                }
            }
        }
    }
    return $string;
}

function tweibo_show_nick($string,$nick_array)
{
    preg_match_all("/@([^@:\s]+)/",$string,$name_matches,PREG_SET_ORDER);
    if($name_matches)
        foreach ($name_matches as $element)
            if(array_key_exists($element[1],$nick_array))
                $string = str_replace($element[0],$nick_array[$element[1]]."(".$element[0].")",$string);

    return $string;
}


?>
