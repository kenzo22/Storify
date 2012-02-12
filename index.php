<?php
$html_title = "口立方 - 自助报道，传递价值，构建影响力";
include $_SERVER['DOCUMENT_ROOT'].'/global.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/member/tagoperation.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="keywords" content="口立方, 新媒体, 自媒体, 自助报道, 社会化媒体, 社会化媒体整合, 社会化媒体聚合, 报道热点话题, koulifang.com"/>
    <meta name="description" content="口立方是新颖的自助报道媒体, 帮助用户筛选整合社会化媒体信息, 创作分享优质内容" />
	<title><?php print $html_title; ?></title>
	<link type='text/css' rel='stylesheet' href="/css/layout.css" />
	<link type="image/ico" rel="shortcut icon"  href="/img/favicon.ico" />
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-27514721-1']);
	  _gaq.push(['_trackPageview']);
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
	  <span id='top_menu_a'><a class='edit_story_btn' href='/create'>开始报道</a></span>".$content."</div></div>";
    }
	else
	{
	  getPublicToken();
	  $content = "<span id='top_menu_b'><a class='register_top' href='/accounts/register'>注册</a><a class='login_top' href='/accounts/login?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='edit_story_btn' href='/create'>开始报道</a></span>";
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
		  <div id='sprite-slide1'></div>
		  <div id='sprite-slide2'></div>
		  <div id='sprite-slide3'></div>
		  <div id='sprite-slide4'></div>
		</div>
		<div id='more_info'><a class='large blue awesome' href='/tour'>了解更多 &raquo;</a></div>
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
								  <img src='".$user_profile_img."' alt=''/>
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
	<?php
	if(islogin())
	{
	    $new_content = "<div id='new_wrapper'><h3>最新发布</h3><ul id='mycarousel' class='jcarousel-skin-tango sto_cover_list'>";
		$uid = $_SESSION['uid'];
		$follow_query="select follow_id from ".$db_prefix."follow, story_posts where user_id=".$uid." and follow_id = post_author and post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=7 group by follow_id";
		$fol_result = $DB->query($follow_query);
		$fol_array = array();
		$item__array = array();
		while($item = mysql_fetch_array($fol_result))
			$fol_array[] = $item['follow_id'];
		$len = sizeof($fol_array);
		if ($len >= 10){
			$ran_keys = array_rand($fol_array, 10);
			foreach($ran_keys as $idx){
				$query = "select * from story_posts where post_author=".$fol_array[$idx]." order by post_date desc limit 1";
				$story_result = $DB->query($query);
				$item_array[] = mysql_fetch_array($story_result);
			}
		}else if ($len > 0)
			foreach($fol_array as $fid){
				$query = "select * from story_posts where post_author=".$fid." order by post_date desc limit 1";
				$story_result = $DB->query($query);
				$item_array[] = mysql_fetch_array($story_result);
			}
		$left = 10 - $len;
		if( $left < 10 )
			$new_query="select post_author,post_pic_url,post_title,post_date,story_posts.ID from story_posts where post_author !=".$uid." and post_author not in (select follow_id from story_follow where user_id=".$uid.") and post_status = 'Published' order by post_date desc limit ".$left;
		else
			$new_query="select * from story_posts where post_author !=".$uid." and post_status = 'Published' order by post_date desc limit $left";
		$others_result = $DB->query($new_query);
        $cnt=array();
		while($item=$DB->fetch_array($others_result)){
            if(++$cnt[$item['post_author']] > 2)
                continue;
			$item_array[] = $item;
        }
		foreach($item_array as $story_item)
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
		$post_link = htmlspecialchars($post_link);
	    $new_content .= "<li>
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
							  <img src='".$user_profile_img."' alt=''/>
							  <a class='meta_author' href='/user/".$post_author."'>".$author_name."</a>
							</span>
						  </div>
						</li>";
	  }
	  $new_content.="</ul></div>";
      echo $new_content;	  
	}
	?>
	<div class='category'>
	  <div id='trendTopics'>
	    <h3>热门话题</h3>
	    <div class='topic_list'>
		  <ul>
		<?php
		$tag_content='';
		$tags=getPopularTags(16);
		foreach($tags as $tag_id)
		{
			$query = "select * from ".$db_prefix."tag where id=".$tag_id;
			$results=$DB->query($query);
			$tag_item=$DB->fetch_array($results);
			$tag_name = $tag_item['name'];
			$topic_link = "/topic/".$tag_id;
			$tag_content .="<li><a class='topic_title' href='".$topic_link."' title='".$tag_name."'>#".$tag_name."#</a></li>";
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
	  <li><a title="tour" href="/tour">了解口立方</a></li>
      <li><a title="faq" href="http://www.koulifang.com/user/3/4">用户帮助</a></li>
      <li><a title="terms" href="/terms">使用协议</a></li>
      <li><a title="contact" href='/contactus'>联系我们</a></li>
      <li><span id='footer_weibo'><a title="口立方微博" href="http://weibo.com/2329577672" target="_blank">@口立方</a></span></li>
    </ul>
    <p>&copy; 2011 Koulifang.com. 沪ICP备11038197号</p>
  </div>
</div>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.orbit-1.2.3.min.js"></script>
<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="/js/frontpage.js"></script>
<script type="text/javascript">
  (function() 
  {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
