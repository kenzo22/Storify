<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/connect_db.php"; 

$weibo_access_token =           array('3dded3c1a69e0e24609b04c3bc07d3ee', 'a5a036de79ad7bb7e71446366d9c69ab', '9a0db78eaffe82ee099f17c8937f29cf');
$weibo_access_token_secret =    array('4815f86a2f8dcbbca4a307535b1a82d8', 'ddd74ff5df9a06325822cefdec81e10e', '0175d039c755cc3b128c134f30b9af3c');

$tweibo_access_token =          array('1fce15f8b9d3449ea9a031adf9138f95', '4fc29d6f9721471fabfb38ce56298f48');
$tweibo_access_token_secret =   array('2a4a03d0dac0951f06d3e7b5b30a1ea0', '355354af7961e5bbc154238dca72a75a');


function islogin()
{
 global $_SESSION;
 global $DB;
 global $db_prefix;
 if(empty($_SESSION['uid']))
 {
   if($_COOKIE['email'] != '' && $_COOKIE['password'] != '')
   {
     $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
     if(!empty($userinfo['id']))
     {
       $_SESSION['uid']=intval($userinfo['id']);
       $_SESSION['username']=$userinfo['username'];
	   
	   getPublicToken();
	   if($userinfo['weibo_access_token'] != '')
	   {
		 $_SESSION['last_wkey']['oauth_token']=$userinfo['weibo_access_token'];
		 $_SESSION['last_wkey']['oauth_token_secret']=$userinfo['weibo_access_token_secret'];
	   }
	   if($userinfo['tweibo_access_token'] != '')
	   {
		 $_SESSION['last_tkey']['oauth_token']=$userinfo['tweibo_access_token'];
		 $_SESSION['last_tkey']['oauth_token_secret']=$userinfo['tweibo_access_token_secret'];
	   }
	  
	   $_SESSION['last_dkey']['oauth_token']=$userinfo['douban_access_token'];
	   $_SESSION['last_dkey']['oauth_token_secret']=$userinfo['douban_access_token_secret'];
	   $_SESSION['yupoo_token'] = $userinfo['yupoo_token'];
	   
	   return 1;
     }
   }
   return 0;
 }
 else
 {
   return 1;
 }
}

function getUserInfo($email, $password)
{
  global $DB;
  global $db_prefix;
  $email=(trim($email));
  $passwd=trim($password);
  $result = $DB->fetch_one_array("SELECT * FROM story_user WHERE email='".$email."' AND passwd='".$passwd."'");
  return $result;
}

function postDateFormat($oridate,$curdate)
{
    $ori_array = preg_split('/[-:\s]/',$oridate);
    $cur_array = preg_split('/[-:\s]/',$curdate);
    if(sizeof($cur_array) !=6 || (sizeof($ori_array) !=5 && sizeof($ori_array)!=6)) 
        return "原始或者当前的日期格式出错";
    $len = sizeof($ori_array);
    $me = array('年','个月','天','小时','分钟','秒');
	if($ori_array[0] != $cur_array[0] || $ori_array[1] != $cur_array[1])
	{
	  $temp_array = explode(" ", $oridate);
      return $temp_array[0];
	}
	else
	{
	  for($i=2; $i< $len-1; $i++)
      {
        if($ori_array[$i] != $cur_array[$i])
        {
          $diff = $cur_array[$i] - $ori_array[$i];
          return $diff.$me[$i]."前";
        }
      }
      return "1分钟前";
	}
}

function getUserPic($uid)
{
  global $DB;
  $userresult = $DB->fetch_one_array("SELECT photo FROM story_user where id='".$uid."'");
  if($userresult['photo'] == '')
  {
	$user_profile_img = '/img/douban_user_dft.jpg';
  }
  else
  {
	$user_profile_img =$userresult['photo'];
  }
  return $user_profile_img;
}

function getPublicToken()
{
  global $weibo_access_token, $tweibo_access_token, $weibo_access_token_secret, $tweibo_access_token_secret;
  global $_SESSION;
  if($_SESSION['last_wkey']['oauth_token'] == '')
  {
	$max = sizeof($weibo_access_token);
	$indx = rand(0,$max-1);
	$_SESSION['last_wkey']['oauth_token'] = $weibo_access_token[$indx];
	$_SESSION['last_wkey']['oauth_token_secret'] =  $weibo_access_token_secret[$indx];
  }

  if($_SESSION['last_tkey']['oauth_token'] == '')
  {
    $max = sizeof($tweibo_access_token);
	$indx = rand(0, $max-1);
	$_SESSION['last_tkey']['oauth_token'] =  $tweibo_access_token[$indx];
	$_SESSION['last_tkey']['oauth_token_secret'] = $tweibo_access_token_secret[$indx];
  }
}

function getPopularScore($post_id)
{
  global $DB;
  global $db_prefix;
  $view_count = 0;
  $comment_weight = 5;
  $digg_weight = 3;
  $view_weight = 2;
  $result = $DB->fetch_one_array("SELECT post_digg_count FROM ".$db_prefix."posts where id='".$post_id."'");
  $digg_count = $result['post_digg_count'];
  
  $query="select COUNT(*) as num from ".$db_prefix."comments where comment_post_id =".$post_id;
  $result = mysql_fetch_array(mysql_query($query));
  $comment_count = intval($result[num]);
  
  $query = "select view_count from ".$db_prefix."pageview where story_id=".$post_id;
  $result = $DB->query($query);
  if($DB->num_rows($result) > 0)
  {
	while($row = $DB->fetch_array($result)){
		$view_count += $row['view_count'];
	}
  }
  $popularScore = $comment_weight*$comment_count + $digg_weight*$digg_count + $view_weight*$view_count;
  return $popularScore; 
}

function printPureStory($story_item){
        global $DB;
        global $db_prefix;
	  $post_author = $story_item['post_author'];
	  $post_pic_url = $story_item['post_pic_url'];
	  $post_id = $story_item['ID'];
	  $sview_count = 0;
	  if($post_pic_url == '')
	  {
		$post_pic_url = '/img/event_dft.jpg';
	  }
	  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
	  $user_profile_img = $userresult['photo'];
	  $author_name = $userresult['username'];
	  if($user_profile_img == '')
	  {
		$user_profile_img = '/img/douban_user_dft.jpg';
	  }
	  $post_title = $story_item['post_title'];
	  $post_date = postDateFormat($story_item['post_date'],date("Y-m-d H:i:s"));
	  $post_link = "/user/".$post_author."/".$post_id;
	  $post_link = htmlspecialchars($post_link);
	  $count_query = "select view_count from ".$db_prefix."pageview where story_id=".$post_id;
	  $countResult = $DB->query($count_query);
	  if($DB->num_rows($countResult) > 0){
		while($count_result_row = $DB->fetch_array($countResult)){
			$sview_count += $count_result_row['view_count'];
		}
	  }
	  $story_content .= "<li>
						  <div class='story_wrap'>	
							<a href='".$post_link."'>
							  <img class='cover' src='".$post_pic_url."' alt='' />
							</a>
							<a class='title_wrap' href='".$post_link."'>
							  <span class='title'>".$post_title."</span>
							</a>
						  </div>
						  <div class='story_meta'>
							<div class='float_l'>
							  <img src='".$user_profile_img."' alt='' />
							</div>
							<div class='meta_info'>
							  <div>
								<a class='meta_author' href='/user/".$post_author."'>".$author_name."</a>
								<div class='meta_date'>".$post_date."</div>
								<div class='meta_view'>".$sview_count."</div>
							  </div>
							</div>
							<div class='clear'></div>
						  </div>
						</li>";

	return $story_content;
}


function printStory($result)
{
  global $DB;
  global $db_prefix;
  $story_content = '';
  while ($story_item = mysql_fetch_array($result))
	{
	  $sview_count = 0;
	  $post_author = $story_item['post_author'];
	  $post_pic_url = $story_item['post_pic_url'];
	  $post_id = $story_item['ID'];
	  if($post_pic_url == '')
	  {
		$post_pic_url = '/img/event_dft.jpg';
	  }
	  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
	  $user_profile_img = $userresult['photo'];
	  $author_name = $userresult['username'];
	  if($user_profile_img == '')
	  {
		$user_profile_img = '/img/douban_user_dft.jpg';
	  }
	  $post_title = $story_item['post_title'];
	  $post_date = postDateFormat($story_item['post_date'],date("Y-m-d H:i:s"));
	  $post_link = "/user/".$post_author."/".$post_id;
	  $post_link = htmlspecialchars($post_link);
	  $count_query = "select view_count from ".$db_prefix."pageview where story_id=".$post_id;
	  $countResult = $DB->query($count_query);
	  if($DB->num_rows($countResult) > 0){
		while($count_result_row = $DB->fetch_array($countResult)){
			$sview_count += $count_result_row['view_count'];
		}
	  }
	  $story_content .= "<li>
						  <div class='story_wrap'>	
							<a href='".$post_link."'>
							  <img class='cover' src='".$post_pic_url."' alt='".$post_title."' />
							</a>
							<a class='title_wrap' href='".$post_link."'>
							  <span class='title'>".$post_title."</span>
							</a>
						  </div>
						  <div class='story_meta'>
							<div class='float_l'>
							  <img src='".$user_profile_img."' alt='".$author_name."' />
							</div>
							<div class='meta_info'>
							  <div>
								<a class='meta_author' href='/user/".$post_author."'>".$author_name."</a>
								<div class='meta_date'>".$post_date."</div>
								<div class='meta_view'>".$sview_count."</div>
							  </div>
							</div>
							<div class='clear'></div>
						  </div>
						</li>";
	}
	return $story_content;
}

function printLikedStory($result,$login_uid)
{
  global $DB;
  global $db_prefix;
  $story_content = '';
  while ($story_item = mysql_fetch_array($result))
	{
	  $sview_count = 0;
	  $post_author = $story_item['post_author'];
	  $post_pic_url = $story_item['post_pic_url'];
	  $post_id = $story_item['ID'];
	  if($post_pic_url == '')
	  {
		$post_pic_url = '/img/event_dft.jpg';
	  }
	  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
	  $user_profile_img = $userresult['photo'];
	  $author_name = $userresult['username'];
	  if($user_profile_img == '')
	  {
		$user_profile_img = '/img/douban_user_dft.jpg';
	  }
	  $post_title = $story_item['post_title'];
	  $post_date = postDateFormat($story_item['post_date'],date("Y-m-d H:i:s"));
	  $post_link = "/user/".$post_author."/".$post_id;
	  $post_link = htmlspecialchars($post_link);
	  $count_query = "select view_count from ".$db_prefix."pageview where story_id=".$post_id;
	  $countResult = $DB->query($count_query);
	  if($DB->num_rows($countResult) > 0){
		while($count_result_row = $DB->fetch_array($countResult)){
			$sview_count += $count_result_row['view_count'];
		}
	  }
	  $story_content .= "<li>
						  <div class='story_wrap'>	
							<a href='".$post_link."'>
							  <img class='cover' src='".$post_pic_url."' alt='".$post_title."' />
							</a>
							<a class='title_wrap' href='".$post_link."'>
							  <span class='title'>".$post_title."</span>
							</a>";
	  if($login_uid != 0)
	  {
	    $story_content .= "<div class='del_wrapper'>
						     <a id='like_".$login_uid."_".$story_item['ID']."' class='del_like remove_item'><i></i><span>喜欢</span></a>
						   </div>";
	  }
	  $story_content .="</div>
						  <div class='story_meta'>
							<div class='float_l'>
							  <img src='".$user_profile_img."' alt='".$author_name."' />
							</div>
							<div class='meta_info'>
							  <div>
								<a class='meta_author' href='/user/".$post_author."'>".$author_name."</a>
								<div class='meta_date'>".$post_date."</div>
								<div class='meta_view'>".$sview_count."</div>
							  </div>
							</div>
							<div class='clear'></div>
						  </div>
						</li>";
	}
	return $story_content;
}

function printFollow($user_list)
{
  global $DB;
  global $db_prefix;
  $content = "";
  foreach($user_list as $user)
  {
	$query="select id, username, photo from ".$db_prefix."user where id=".$user;
	$result=$DB->query($query);
	$item=$DB->fetch_array($result);
	$usr_img = $item['photo'];
	if($usr_img == '')
	{
	  $usr_img = '/img/douban_user_dft.jpg';
	}
	$content .="<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/user/".$item['id']."'><img title='".$item['username']."' src='".$usr_img."' alt='".$item['username']."' /></a></li>";
  }
  return $content;
}

function getAvatarImg($userresult)
{
  if(substr($userresult['photo'], 0, 4) == 'http')
  {
	if(substr($userresult['photo'], 11, 4) == 'sina')
	{
	  $pattern = "/(\d+)\/50\/(\d+)/";
	  $user_profile_img = preg_replace($pattern,"$1/180/$2",$userresult['photo']);
	}
    else
	{
	  $pattern = "/50$/";
	  $user_profile_img = preg_replace($pattern,'100',$userresult['photo']);
	}
  }
  else
  {
	if($userresult['photo'] == '')
	{
	  $user_profile_img = '/img/douban_user_dft.jpg';
	}
	else
	{
	  $user_profile_img =$userresult['photo'];
	}
  }
  return $user_profile_img;
}

?>
