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

	$login_flag = islogin();
	if($login_flag)
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
	<div id='left_main'>
	<?php
	if($login_flag)
	{
	  echo "<div id='subscription' class='t_category'>
	  <h3><a href='#'>我的订阅</a></h3>
	  <span id='more_sub'><a href='#'>更多 &raquo;</a></span>
	  <ul class='sto_cover_list'>";
	  $i_query = "select * from ".$db_prefix."posts ORDER BY RAND() LIMIT 3";
	  $result=$DB->query($i_query);
	  printStory($result);
	  echo "</ul></div>";
	}
	else
	{
	  ?>
	  <div id='featured_container'>
		<img src='/img/slide1.jpg'/>
	  </div>
	  <?php
	}
	?>
	<div id='society' class='t_category'>
	  <h3><a href='#'>社会</a></h3>
	  <ul class='category_list'>
	    <li><a href='#'>全部</a></li>
		<li><a href='#'>话题</a></li>
	    <li><a href='#'>文化</a></li>
		<li><a href='#'>万象</a></li>
		<li><a href='#'>更多 &raquo;</a></li>
	  </ul>
	  <ul class='sto_cover_list'>
	    <?php
		$list_content = '';
		$i_query = "select * from ".$db_prefix."posts ORDER BY RAND() LIMIT 3";
		$result=$DB->query($i_query);
		printStory($result);
		?>
	  </ul>
	</div>
	<div id='yule' class='t_category'>
	  <h3><a href='#'>娱乐</a></h3>
	  <ul class='category_list'>
	    <li><a href='#'>全部</a></li>
		<li><a href='#'>明星</a></li>
		<li><a href='#'>时尚</a></li>
		<li><a href='#'>美食</a></li>
		<li><a href='#'>旅游</a></li>
		<li><a href='#'>晒货</a></li>
		<li><a href='#'>电影</a></li>
		<li><a href='#'>音乐</a></li>
		<li><a href='#'>更多 &raquo;</a></li>
	  </ul>
	  <ul class='sto_cover_list'>
	    <?php
		$list_content = '';
		$i_query = "select * from ".$db_prefix."posts ORDER BY RAND() LIMIT 3";
		$result=$DB->query($i_query);
		printStory($result);
		?>
	  </ul>
	</div>
	<div id='tech' class='t_category'>
	  <h3><a href='#'>科技</a></h3>
	  <ul class='category_list'>
	    <li><a href='#'>全部</a></li>
		<li><a href='#'>互联网</a></li>
		<li><a href='#'>创业</a></li>
		<li><a href='#'>移动互联网</a></li>
		<li><a href='#'>数码</a></li>
		<li><a href='#'>游戏</a></li>
		<li><a href='#'>更多 &raquo;</a></li>
	  </ul>
	  <ul class='sto_cover_list'>
	    <?php
		$list_content = '';
		$i_query = "select * from ".$db_prefix."posts ORDER BY RAND() LIMIT 3";
		$result=$DB->query($i_query);
		printStory($result);
		?>
	  </ul>
	</div>
	<div id='sports' class='t_category'>
	  <h3><a href='#'>体育</a></h3>
	  <ul class='category_list'>
	    <li><a href='#'>全部</a></li>
		<li><a href='#'>国际足坛</a></li>
		<li><a href='#'>NBA</a></li>
		<li><a href='#'>综合</a></li>
		<li><a href='#'>更多 &raquo;</a></li>
	  </ul>
	  <ul class='sto_cover_list'>
	    <?php
		$list_content = '';
		$i_query = "select * from ".$db_prefix."posts ORDER BY RAND() LIMIT 3";
		$result=$DB->query($i_query);
		printStory($result);
		?>
	  </ul>
	</div>
	</div>
	<div id='right_main'>
	  <?php
	  if($login_flag)
	  {
	    $custom_content = "<ul>
		<li><a href='#'>我创作的 &raquo;</a></li>
		<li><a href='#'>我喜欢的 &raquo;</a></li>
	  </ul>
	  <div id='add_info'>
	    <h3><a href='#'>完善你的个人资料</a></h3>
	  </div>
	  <div id='invite_people'>
	    <h3><a href='#'>邀请好友加入口立方</a></h3>
	  </div>
	  <div id='rec_people'>
	    <h3><a href='#'>你可能感兴趣的人</a></h3>
	  </div>";
	  }	  
	  else
	  {
	    $custom_content = "<div id='login_form_right'>
						     <form method='post' action='/accounts/login/login'>
							   <div class='form_div'><div class='form_label'>邮&nbsp;箱</div><input type='text' name='email' id='email_login' size='26' /></div>
							   <div class='form_div'><div class='form_label'>密&nbsp;码</div><input type='password' name='passwd' id='pwd_login' size='26' /></div>
							   <div class='auto_login'><span><input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='/accounts/forget_password'>忘记密码了？</a></span></div>
							   <div><a class='medium blue awesome loginbtn'>登 录 &raquo;</a><a class='medium green awesome' href='/accounts/register'>注册 &raquo;</a></div>
							 </form>
						   </div>
						   <div id='recUsers' class='t_category'>
						     <h3>推荐用户</h3>
							 <ul>";
		$query = "SELECT id, username, photo, intro from ".$db_prefix."user ORDER BY RAND() LIMIT 4";
		$result=$DB->query($query);
		while ($user_item = mysql_fetch_array($result))
		{
		  $u_id = $user_item['id'];
		  $u_name = $user_item['username'];
		  $u_photo = $user_item['photo'];
		  $u_intro = $user_item['intro'];
		  $u_intro = "推荐用户推荐用户推荐用户推荐用户推荐用户推荐用户推荐用户";
		  if(empty($u_photo))
		  {
			$u_photo = 'img/douban_user_dft.jpg';
		  }
		  $u_link = "/user/".$u_id;
		  $custom_content.="<li>
							  <a href='".$u_link."' title='".$u_name."'><img src='".$u_photo."' /></a>
							  <div class='user_intro'>
							    <div><a href='".$u_link."' title='".$u_name."'>".$u_name."</a></div>
							    <div>".$u_intro."</div>
							  </div>
							</li>";
		}
		$custom_content.= "</ul>
						   </div>
						   <div id='topUsers' class='t_category'>
						     <h3>随便看看</h3>
							 <ul>";
		$query = "SELECT id, username, photo from ".$db_prefix."user ORDER BY RAND() LIMIT 16";
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
		  $custom_content.="<li>
							  <a href='".$u_link."' title='".$u_name."'><img src='".$u_photo."' /></a>
							</li>";
		}
		$custom_content.= "</ul></div>";
	  }
	  echo $custom_content;
	  ?>
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
