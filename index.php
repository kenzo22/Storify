<?php
$html_title = "口立方 - 新颖的社会媒体故事社区，帮助你用社会媒体讲故事";
include $_SERVER['DOCUMENT_ROOT'].'/global.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/member/tagoperation.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php print $html_title; ?></title>
	<link type='text/css' rel='stylesheet' href="/css/layout.css" />
    <link type="text/css" rel="stylesheet" href="/css/orbit-1.2.3.css" />
	<link type="image/ico" rel="shortcut icon"  href="/img/favicon.ico" />
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-27514721-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>	
  </head>
  <body>
  <?php
  //unset($debug); //不允许调试
   session_start();
   $debug=1;
   $MAX_DAYS=30;
 
   if (!empty($_SERVER[HTTP_REFERER])) $url=htmlspecialchars($_SERVER[HTTP_REFERER]); 
   
   if (get_magic_quotes_gpc()) {  //magic_quotes_gpc开了会加"\" 先去掉
        $_GET = stripslashes_array($_GET);
        $_POST = stripslashes_array($_POST);
        $_COOKIE = stripslashes_array($_COOKIE); 
        $GLOBALS = stripslashes_array($GLOBALS);
   } 
   set_magic_quotes_runtime(0); //关闭magic_quotes_gpc

	if(islogin())
    { 
		$user_profile_img;
		$userresult=$DB->fetch_one_array("SELECT id, photo FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
		if($userresult['photo'] != '')
		{
		  $user_profile_img = $userresult['photo'];
		}
		else
		{
		  $user_profile_img = '/img/douban_user_dft.jpg';
		}
		$content="<ul class='user_console'>
				    <li class='person_li display'><a class='person_a person_a_display' href='/user/".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a home_icon' href='/user/".$userresult['id']."'><img class='console_img' src='/img/home.png'/><span>我的主页</span></a></li>
					<li class='person_li'><a class='person_a setting_icon' href='/accounts/setting'><img class='console_img' src='/img/setting.png'/><span>设置</span></a></li>
					<li class='person_li'><a class='person_a quit_icon' href='/accounts/logout'><img class='console_img' src='/img/quit.png'/><span>退出<span></a></li>
		          </ul>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
	  <span id='top_menu_a'><a class='edit_story_btn' href='/create'>创建故事</a></span>".$content."</div></div>";
    }
	else
	{
	  getPublicToken();
	  $content = "<span id='top_menu_b'><a class='register_top' href='/accounts/register'>注册</a><a class='login_top' href='/accounts/login?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='edit_story_btn' href='/create'>创建故事</a></span>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>".$content."</div></div>";
	}
   ?>
	<!--[if IE]>
		<style type="text/css">
			 .timer { display: none !important; }
			 div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
		</style>
	<![endif]-->
	<!--[if IE 6]>
	  <style type="text/css">
	    #user_feedback_tab{display:none;}
	  </style>
	<![endif]-->
	<div id='boxes'>  
	<div id='dialog' class='window'>
	  <div class='title_bar'><span><a href='#' class='close'>关闭</a></span><span>登录 koulifang.com</span></div>
	  <form method='post' action='/accounts/login/login'>
	  <div class='wrapper'>
		<div id='login_modal'>
		  <div class='form_div'><span class='form_label'>邮&nbsp;箱</span><span><input type='text' name='email' id='email_login' onclick='this.value=""'/></span></div>
		  <div class='form_div'><span class='form_label'>密&nbsp;码</span><span><input type='password' name='passwd' id='pwd_login' onclick='this.value=""'/> </span></div>
		  <div class='auto_login'><span><input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='/accounts/forget_password'>忘记密码了？</a></span></div>
		  <div>
			<input type='submit' id='login_modal_btn' value='登录'/>
		  </div>
		</div>
		<div class='login_right'>
		  <div>还没有口立方帐号?</div>
		  <a class='large green awesome register_awesome' href='/accounts/register'>马上注册 &raquo;</a>
		  <div><span>使用新浪微博帐号登录</span></div>
		  <div><a id='connectBtn' href='#'><span class='sina_icon'></span><span class='sina_name'>新浪微博</span></a></div>  
		</div>
	  </div>
	  </form>
	</div>
	</div>
    
	<div id='main_content' class='inner'>
	  <div><a id='user_feedback_tab' href='/contactus'>feedback</a></div>
	<?php
	if(!islogin())
	{
	  $slider_content ="
	  <div id='featured_container'>
		<div id='featured'> 
		  <img src='img/slide1.jpg' alt='口立方'/>
		  <img src='img/slide2.jpg' alt='口立方'/>
		  <img src='img/slide3.jpg' alt='口立方'/>
		  <img src='img/slide4.jpg' alt='口立方'/>
		</div>
	  </div>";
	  echo $slider_content;
	}
	?>
	<div id='popular'>
	  <h3>最流行</h3>
	  <div id='pop_wrapper'>
		<div id='time_wrapper'><a class='time_range'>三天内</a><a class='time_range selected'>一周内</a><a class='time_range'>一月内</a><a class='time_range'>365天内</a></div>
		<ul id='pop_list' class='sto_cover_list'>
		<?php
		$story_content = '';
		$i_query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=7 order by popular_count desc limit 4";
		$result=$DB->query($i_query);
		while ($story_item = mysql_fetch_array($result))
		{
		  $post_author = $story_item['post_author'];
		  $post_pic_url = $story_item['post_pic_url'];
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
		  $post_date = $story_item['post_date'];
		  $temp_array = explode(" ", $story_item['post_date']);
		  $post_date = $temp_array[0];
		  $post_link = "/user/".$post_author."/".$story_item['ID'];
		  //$post_link = htmlspecialchars($post_link, ENT_COMPAT, UTF-8);
		  $post_link = htmlspecialchars($post_link);
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
								<span>
								  <a class='meta_date'>".$post_date."</a>
								  <img src='".$user_profile_img."' alt='".$author_name."'/>
								  <a class='meta_author' href='/user/".$post_author."'>".$author_name."</a>
								</span>
							  </div>
							</li>";
		}
		echo $story_content;
		?>
		</ul>
	  </div>
	  <div><a id='story_more'>换一组看看</a></div>
	</div>
	<div class='category'>
	  <div id='trendTopics'>
	    <h3>热门话题</h3>
	    <div class='topic_list'>
		  <ul>
		<?php
		$tag_content='';
		$tags=getPopularTags(8);
		$used_story=array();
		$s_query='';
		$tag_i=0;
		foreach($tags as $tag_id)
		{
			$query = "select * from ".$db_prefix."tag where id=".$tag_id;
			$results=$DB->query($query);
			$tag_item=$DB->fetch_array($results);

			$tag_name = $tag_item['name'];
			$query = "select * from ".$db_prefix."tag_story,story_posts where tag_id='".$tag_id."' and story_id=story_posts.id and post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS";
			$relationresult = $DB->query($query);
			$tag_count = $DB->num_rows($relationresult);
			$topic_link = "/topic/".$tag_id;
			
			if($used_story){
				foreach($used_story as $sid){
					$s_query .= " and story_posts.id !=".$sid;
				}
			}
			//need to fetch the title of the most popular story which has this specific tag
			$query="select story_posts.id,".$db_prefix."posts.post_title,".$db_prefix."posts.post_pic_url from ".$db_prefix."tag_story,".$db_prefix."posts where tag_id=".$tag_id." and story_id=".$db_prefix."posts.id ".$s_query." and story_posts.post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS order by ".$db_prefix."posts.popular_count desc";
			$result=$DB->query($query);
			$item=$DB->fetch_array($result);
			if(!$item)
				continue;
			if(++$tag_i > 4)
				break;
			$used_story[] = $item['id'];
		    if($item['post_pic_url'] != '')
			{
			  $pic_url = $item['post_pic_url'];
			}
			else
			{
			  $pic_url = "/img/event_dft.jpg";
			}
			$tag_content .="<li>
							  <div class='topic_meta'>
								<span class='story_count'>".$tag_count."</span>
								<a class='topic_title' href='".$topic_link."'>#".$tag_name."#</a>
							  </div>
							  <a href='".$topic_link."'>
								<img class='topic_cover' src='".$pic_url."' />
							  </a>
							  <a class='title_wrap' href='".$topic_link."'><h1 class='title'>".$item['post_title']."</h1></a>
							</li>";
		}
		echo $tag_content;
	    ?>
	      </ul>
		</div>
	  </div>
	  <div id='topUsers' class='float_l'>
	    <h3>随便看看</h3>
	    <ul>
		<?php
		  $user_content='';
		  $query = "SELECT id, username, photo from ".$db_prefix."user ORDER BY RAND() LIMIT 10";
		  
		  
		  
		  $result=$DB->query($query);
		  while ($user_item = mysql_fetch_array($result))
		  {
		    $u_id = $user_item['id'];
			$u_name = $user_item['username'];
			$u_photo = $user_item['photo'];
			if(empty($u_photo))
			{
			  $u_photo = 'img/douban_user_dft.jpg';
			}
			$u_link = "/user/".$u_id;
			$user_content.="<li>
							  <a href='".$u_link."' title='".$u_name."'><img src='".$u_photo."' /></a>
							  <div><span><a href='".$u_link."' title='".$u_name."'>".$u_name."</a></span></div>
							</li>";
		  }
		  echo $user_content;
		?>
	    </ul>
	  </div>
	</div>
  </div>

<div id="footer">
  <div class='wrapper'>
    <ul>
      <li><a title="faq" href="http://www.koulifang.com/user/3/4">用户帮助</a></li>
      <li><a title="terms" href="/terms">使用协议</a></li>
      <li><a title="contact" href='/contactus'>联系我们</a></li>
      <li><span>书签: </span><a onclick="addBookmark();return false;" href="#">收藏我们</a></li>
      <li>关注我们：<a title="口立方微博" href="http://weibo.com/2329577672" target="_blank" class="twitter-anywhere-user">微博</a></li>
    </ul>
    <p>&copy; 2011 Koulifang.com. All rights reserved. 沪ICP备11038197号</p>
  </div>
</div>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.orbit-1.2.3.min.js"></script>
<script type="text/javascript" src="/js/frontpage.js"></script>
</body>
</html>
