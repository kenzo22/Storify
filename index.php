<?php
$html_title = "口立方 - 新颖的社会媒体故事社区，帮助你用社会媒体讲故事";
include "global.php"; 
include "member/tagoperation.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php print $html_title; ?></title>
	<link type='text/css' rel='stylesheet' href="css/layout.css" />
    <link type="text/css" rel="stylesheet" href="css/orbit-1.2.3.css" />
	<link type="image/ico" rel="shortcut icon"  href="img/favicon.ico" /> 
	<script type="text/javascript" src="js/jquery.js"></script>
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
		$content="<ul class='user_console showborder'>
				    <li class='person_li display' style='display:block;'><a class='person_a person_a_display' href='/member/user.php?user_id=".$userresult['id']."'><img id='person_img' src='".$user_profile_img."'><span id='person_name'>".$_SESSION['username']."</span></a></li>
					<li class='person_li'><a class='person_a home_icon' href='/member/user.php?user_id=".$userresult['id']."'><img class='console_img' src='/img/home.png'/><span>我的主页</span></a></li>
					<li class='person_li'><a class='person_a setting_icon' href='/member/settings.php'><img class='console_img' src='/img/setting.png'/><span>设置</span></a></li>
					<li class='person_li'><a class='person_a quit_icon' href='/login/login.php?logout'><img class='console_img' src='/img/quit.png'/><span>退出<span></a></li>
		          </ul>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>
	  <span id='top_menu_a'><a class='edit_story_btn' href='/member'>创建故事</a></span>".$content."</div></div>";
    }
	else
	{
	  //select a random item from the publictoken pool
	  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
	  if($_SESSION['last_wkey']['oauth_token'] == '')
	  {
	    $_SESSION['last_wkey']['oauth_token'] = $token['weibo_access_token'];
	    $_SESSION['last_wkey']['oauth_token_secret'] = $token['weibo_access_token_secret'];
	  }
	  $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
	  $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
  
	  $content = "<span id='top_menu_b'><a class='register_top' href='/register/register_form.php'>注册</a><a class='login_top' href='/login/login_form.php?next=".urlencode($_SERVER['REQUEST_URI'])."'>登录</a><a class='edit_story_btn' href='/member'>创建故事</a></span>";
	  echo "<div id='top_bar'><div class='top_nav'><span id='logo'><a title='口立方' accesskey='h' href='/'><img src='/img/koulifangbeta.png' alt='口立方' /></a></span>".$content."</div></div>";
	}
   ?>
	<!--[if IE]>
		<style type="text/css">
			 .timer { display: none !important; }
			 div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
		</style>
	<![endif]-->
	<a id='user_feedback_tab' href='/contact/contactform.htm'></a>
	<div id='boxes'>  
	<div id='dialog' class='window' style='padding:0;'>
	  <div style='background-color:#ababac; padding:5px;'><span><a href='#' class='close'>关闭</a></span><span>登录 koulifang.com</span> | <span><a href='register/register_form.php'>还没有注册？</a><span></div>
	  <form method='post' action='login/login.php'>
	  <div style='overflow:auto;'>
		<div id='login_modal'>
		  <div><b> 邮 箱 &nbsp; </b><span><input type='text' name='email' id='email_login' onclick='this.value=""'/></span></div>
		  <div><b> 密 码 &nbsp; </b><span><input type='password' name='passwd' id='pwd_login' onclick='this.value=""'/> </span></div>
		  <div><span> <input type='checkbox' name='autologin' />下次自动登录</span> | <span><a href='login/forget_form.php'>忘记密码了？</a><span></div>
		  <div style='margin-top:5px;'>
			<input type='submit' id='login_modal_btn' value='登录'/>
		  </div>
		</div>
		<div class='login_right'>
		  <div style='margin-bottom:5px;'>还没有口立方帐号?</div>
		  <a class='large green awesome register_awesome' href='/register/register_form.php'>马上注册 &raquo;</a>
		  <div style='margin-top:15px;'><span>使用新浪微博帐号登录</span></div>
		  <div style='margin-top:5px;'><a id='connectBtn' href='#'><span class='sina_icon'></span><span class='sina_name'>新浪微博</span></a></div>  
		</div>
	  </div>
	  </form>
	</div>
	</div>

	<div id='main_content' class='inner'>
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
		$i_query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=7 order by post_digg_count desc limit 4";
		$result=$DB->query($i_query);
		while ($story_item = mysql_fetch_array($result))
		{
		  $post_author = $story_item['post_author'];
		  $post_pic_url = $story_item['post_pic_url'];
		  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
		  $user_profile_img = $userresult['photo'];
		  if($user_profile_img == '')
		  {
			$user_profile_img = '/img/douban_user_dft.jpg';
		  }
		  $post_title = $story_item['post_title'];
		  $post_date = $story_item['post_date'];
		  $temp_array = explode(" ", $story_item['post_date']);
		  $post_date = $temp_array[0];
		  $post_link = "/member/user.php?user_id=".$post_author."&post_id=".$story_item['ID'];
		  $story_content .= "<li>
							  <div class='story_wrap'>	
								<a href='".$post_link."'>
								  <img class='cover' src='".$post_pic_url."' alt='故事封面' />
								</a>
								<a class='title_wrap' href='".$post_link."'>
								  <span class='title'>".$post_title."</span>
								</a>
							  </div>
							  <div class='story_meta'>
								<span>
								  <a class='meta_date'>".$post_date."</a>
								  <img src='".$user_profile_img."'/>
								  <a class='meta_author' href='member/user.php?user_id=".$post_author."'>".$userresult['username']."</a>
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
		$tag_content = "<div class='category'>
						<div id='trendTopics'>
						  <h3 class='blue'>热门话题</h3>
						  <div class='topic_list'>
							<ul>";
		//need change to fetch the most popular topic from the database
		$tags=getPopularTags(10);
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
			$topic_link = "./topic/topic.php?topic=".$tag_name;
			
			if($used_story){
				foreach($used_story as $sid){
					$s_query = " and story_posts.id !=".$sid;
				}
			}
			//need to fetch the title of the most popular story which has this specific tag
			$query="select story_posts.id,".$db_prefix."posts.post_title,".$db_prefix."posts.post_pic_url from ".$db_prefix."tag_story,".$db_prefix."posts where tag_id=".$tag_id." and story_id=".$db_prefix."posts.id ".$s_query." and story_posts.post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_modified) <=$MAX_DAYS order by ".$db_prefix."posts.post_digg_count desc";
			$result=$DB->query($query);
			$item=$DB->fetch_array($result);
			if(!$item)
				continue;
			if(++$tag_i > 7)
				break;
			$used_story[] = $item['id'];
		
			$tag_content .="<li>
							  <div class='topic_meta'>
								<span class='story_count'>".$tag_count."</span>
								<a class='topic_title' href='".$topic_link."'>#".$tag_name."#</a>
							  </div>
							  <a href='".$topic_link."'>
								<img class='topic_cover' src='".$item['post_pic_url']."' />
							  </a>
							  <a class='title_wrap' href='".$topic_link."'><h1 class='title'>".$item['post_title']."</h1></a>
							</li>";
		}
		$tag_content .= "</ul></div></div></div>";
		echo $tag_content;
	}		  
	?>
	</div>

<?php
 include "./include/footer.htm";
?>	
<script type="text/javascript" src="js/jquery.orbit-1.2.3.min.js"></script>
<script type="text/javascript">
function addBookmark() 
{
    var title='口立方';
    var url='http://www.koulifang.com';
    if(window.sidebar)
	{
      window.sidebar.addPanel(title, url, "");
    }
	else if(document.all) 
	{
      window.external.AddFavorite(url, title);
    } 
	else
	{
      alert('请按 Ctrl + D 为你的浏览器添加书签！');
    }
}

$(function(){
  $('.person_li').bind('mouseover', function(e){
	e.preventDefault();
	$('.person_li').css('display', 'block');
  });
  
  $('.user_console').bind('mouseout', function(){
	$('.person_li').slice(1, 4).css('display', 'none');
  });
	
  $('.login_top').attr('name', 'modal').attr('href', '#dialog');
  
  $('#login_awesome').attr('name', 'modal').attr('href', '#dialog');
  
  var sequence_val = 0;
  
  $('#story_more').live('click', function(e){
	e.preventDefault();
	sequence_val = sequence_val+4;
	var selElem = $('.time_range.selected');	
	var flag_val = $('.time_range').index(selElem);
	var getData = {flag:flag_val, sequence:sequence_val};
	$.ajax({
	  type: 'GET',
	  url: '/member/shufflestory.php',
	  data: getData, 
	  beforeSend:function() 
	  {
		var imgloading = $("<span><img src='/img/loading.gif' alt='正在加载' /></span>");
		$('this').html(imgloading);
	  },
	  success: function(data)
	  {
		$('#pop_list').html(data);
	  }
	  });
  })
  
  $('.time_range').click(function(e){
	e.preventDefault();
	sequence_val = 0;
	$('.time_range').removeClass('selected');
	$(this).addClass('selected');
	var flag_val = $('.time_range').index(this);
	var getData = {flag:flag_val, sequence:0};
	$.ajax({
	  type: 'GET',
	  url: '/member/shufflestory.php',
	  data: getData, 
	  success: function(data)
	  {
		$('#pop_list').html(data);
	  }
	  });
  })
  
  $('#connectBtn').live('click', function(e)
  {
	e.preventDefault();
	$.post('login/sina_auth.php', {}, 		
	function(data, textStatus)
	{
	  $('.window').hide();
	  self.location=data;
	});
  });
	
	//select all the a tag with name equal to modal
	$('a[name=modal]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = $(this).attr('href');
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		//$('#mask').fadeIn(1000);	
		//$('#mask').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(1000); 
	
	});
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		
		$('#mask').hide();
		$('.window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});		
});

$(window).load(function() {
	$('#featured').orbit({
	  advanceSpeed: 8000,
	  bullets: true,
	  directionalNav: false
	});
});
</script>
