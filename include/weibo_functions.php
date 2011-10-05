<?php

function binhex($str)
{
    $hex = ""; 
    $i = 0;
    do {
        $hex .= sprintf("%02x", ord($str{$i}));
        $i++;
    } while ($i < strlen($str));
    return $hex;
}

function subs_url($string)
{
    $pattern="/(http:\/\/[\/\.\w]+)/";
    preg_match_all($pattern,$string,$url_matches,PREG_SET_ORDER);
    if($url_matches){
        foreach($url_matches as $el){
            $replacement="<a href='".$el[1]."'>".$el[1]."</a>";
            $string = str_replace($el[1],$replacement,$string);
        }
    }
    return $string;
}


function subs_emotions($string,$img_parent_dir)
{
    $OS=php_uname('s');
    $cwd = getcwd();
    if(strstr($OS,'Windows'))
	$cwd = str_replace("\\",'/',$cwd);
    preg_match("/(.*?\/storify)/",$cwd,$abs_path_matches);
    $story_img_path="/img/";

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
				// utf-8 汉字都是3字节的
                for($i=3; $i<=strlen($element[1]); $i+=3){
		            $fn = substr($element[1],0,$i);
			        if(strstr($OS,"Windows"))
				        $fn = iconv("UTF-8","GBK",$fn);
                    $story_file =  $story_img_path."tweibo/".$fn.".gif";
                    $local_file = $abs_path_matches[1].$story_file;
                    if(is_readable($local_file)){
					    if(strstr($OS,'Windows')){
						    $fn = iconv('GBK','UTF-8',$fn);
						    $story_file =  $story_img_path."tweibo/".$fn.".gif";
					    }	
                        $img_replace = "<img src='".$story_file."'>";
                        $string= str_replace(substr($element[0],0,$i+1),$img_replace,$string);
                        break;
                    }
                }
            }elseif($img_parent_dir == "weibo"){
				$fn = $element[1];
				if(strstr($OS,"Windows"))
					$fn = iconv("UTF-8","GBK",$fn);		
                $story_file=$story_img_path."weibo/".$fn.".gif";
                $local_file=$abs_path_matches[1].$story_file;
                if(is_readable($local_file)){
					if(strstr($OS,'Windows')){
						$fn = iconv('GBK','UTF-8',$fn);
						$story_file =  $story_img_path."weibo/".$fn.".gif";
					}
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
