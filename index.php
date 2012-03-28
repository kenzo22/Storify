<?php
$html_title = "口立方 - 热点资讯，你来呈现";
include $_SERVER['DOCUMENT_ROOT'].'/global.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/member/tagoperation.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="keywords" content="口立方, 自媒体, 热点资讯, 新媒体, 社会化媒体, 社会化媒体整合, 社会化媒体聚合, koulifang.com"/>
    <meta name="description" content="口立方是一个新颖的自媒体平台, 帮助用户筛选整合社会化媒体信息, 创作分享优质内容" />
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

	$login_flag = islogin();
	if($login_flag)
    { 
		$user_profile_img;
		$userresult=$DB->fetch_one_array("SELECT id, photo, intro FROM ".$db_prefix."user WHERE id='".$_SESSION['uid']."'" );
		if($userresult['photo'] != '')
		{
		  $user_profile_img = $userresult['photo'];
		}
		else
		{
		  $user_profile_img = '/img/douban_user_dft.jpg';
		}
		$content="<ul class='user_console'>
				    <li class='person_li display'><a class='person_a person_a_display' href='/user/".$userresult['id']."'><img id='person_img' src='".$user_profile_img."' alt=''/><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a home_icon' href='/user/".$userresult['id']."/subscription'><img class='console_img' src='/img/home.png' alt=''/><span>我的订阅</span></a></li>
					<li class='person_li'><a class='person_a setting_icon' href='/accounts/setting'><img class='console_img' src='/img/setting.png' alt=''/><span>设置</span></a></li>
					<li class='person_li'><a class='person_a quit_icon' href='/accounts/logout'><img class='console_img' src='/img/quit.png' alt=''/><span>退出<span></a></li>
		          </ul>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
	  <span id='top_menu_a'><a class='edit_story_btn' href='/create'>开始创作</a></span>".$content."</div></div>";
    }
	else
	{
	  getPublicToken();
	  $content = "<span id='top_menu_b'><a class='register_top' href='/accounts/register'>注册</a><a class='login_top' href='/accounts/login?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='edit_story_btn' href='/create'>开始创作</a></span>";
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
	<div class='boxes'>  
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
	  if(!$login_flag)
	  {
	  ?>
	    <div class='feature'>
		  <div class='ad'>
			<div class='regBtnBox'><a class='regBtn' href='/accounts/register'></a></div>
			<div class='logBtnBox'><a id='sinaConnect' class='logBtn' href='#'></a></div>
			<div class='moreBox'><a class='moreBtn' href='/tour'></a></div>
		  </div>
		</div>
	  <?php
	  }
	  else
	  {
	    echo "<div id='subscription' class='t_category'>
	    <h3>我的订阅</h3>";
        $i_query = "SELECT story_posts.* FROM story_posts,story_follow WHERE user_id=".$_SESSION['uid']." AND follow_id=post_author AND post_status='Published' ORDER BY post_modified desc limit 4";
	    $result=$DB->query($i_query);
        if($DB->num_rows($result) == 0)
	    {
          echo "<div id='sub_imply'>订阅你喜欢的作者，他们的文章会显示在这里喔～</div>";
        }
	    else
	    {
	      $story_content ="<span id='more_sub'><a href='/user/".$userresult['id']."/subscription'>更多 &raquo;</a></span><ul class='sto_cover_list'>";
		  while($row=$DB->fetch_array($result)){
            $story_content.=printPureStory($row);
          }
		  echo $story_content."</ul>";	
	    }
	    echo "</div>";
	  }
	  ?>
	  <div id='popular' class='t_category'>
	    <h3>最流行</h3>
		<a id='more_pop' href='/all'>更多 &raquo;</a>
	    <ul class='sto_cover_list'>
		<?php
		$result = $DB->fetch_one_array("select post_str from ".$db_prefix."maintale where category='社会'");
		$post_str = $result['post_str'];
		$sql = "SELECT * FROM story_posts WHERE ID IN ($post_str) ORDER BY FIND_IN_SET(ID, '$post_str')";
		$result = mysql_query($sql);
		echo printStory($result);
		?>
	    </ul>
	  </div>
	<div id='left_main'>
	<?php
	$rec_user="<div id='recUsers' class='t_category'>
				   <h3>推荐用户</h3>
				   <ul>";
    $recomment_user = "64,95,54,1,74,117,77,80,76,53,58,72";
    //$query = "SELECT id, username, photo, intro from ".$db_prefix."user WHERE id IN ($recomment_user) ORDER BY FIND_IN_SET(id, '$recomment_user') limit 4";
    if($login_flag)
    {
	  $query = "SELECT id, username, photo, intro from ".$db_prefix."user WHERE id IN ($recomment_user) and id<>".$_SESSION['uid']." ORDER BY RAND() LIMIT 7";
    }
    else
    {
	  $query = "SELECT id, username, photo, intro from ".$db_prefix."user WHERE id IN ($recomment_user) ORDER BY RAND() LIMIT 7";
    }
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
	  $rec_user.="<li>
					  <a href='".$u_link."' title='".$u_name."'><img src='".$u_photo."' alt=''/></a>
					  <div><a href='".$u_link."' title='".$u_name."'>".$u_name."</a></div>
					</li>";
    }
    $rec_user.="</ul></div>";
	echo $rec_user;
	?>
	</div>
	<div id='right_main'>
	  <div id='follow_us' class='t_category'>
	    <h3>关注口立方</h3>
	    <iframe width="100%" height="84" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=100%&height=64&uid=2329577672&style=4&btn=red&dpc=1"></iframe>
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
