<?php
include "global.php"; 
//select a random item from the publictoken pool
$token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
/*$_SESSION['last_key']['oauth_token'] = '3dded3c1a69e0e24609b04c3bc07d3ee';
$_SESSION['last_key']['oauth_token_secret'] = '4815f86a2f8dcbbca4a307535b1a82d8';
$_SESSION['last_tkey']['oauth_token'] = '1fce15f8b9d3449ea9a031adf9138f95';
$_SESSION['last_tkey']['oauth_token_secret'] = '2a4a03d0dac0951f06d3e7b5b30a1ea0';*/
$_SESSION['last_key']['oauth_token'] = $token['weibo_access_token'];
$_SESSION['last_key']['oauth_token_secret'] = $token['weibo_access_token_secret'];
$_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
$_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];

?>
<div id='boxes'>
  
<!-- Start of Login Dialog -->  
<div id='dialog' class='window' style='padding:0;'>
  <div style='background-color:#ababac; padding:5px;'><span>登录 koulifang.com</span> | <span><a href='/storify/register/register_form.php'/>还没有注册？</a><span> <span><a href='#' class='close'/>关闭</a></span></div>
  <form method='post' action='/storify/login/login.php'>
  <div>
    <div id='login_modal' class='float_l' style='margin-top:10px;'>
      <div style='padding-left:5px;'><b> 邮 箱 &nbsp; </b><span><input type='text' name='email' id='email_login' onclick='this.value=""'/></span></div>
      <div style='padding-left:5px;'><b> 密 码 &nbsp; </b> <span><input type='password' name='passwd' id='pwd_login' onclick='this.value=""'/> </span></div>
      <div style='padding-left:5px;'><span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='/storify/login/forget_form.php'/>忘记密码了？</a><span></div>
      <div style='padding-left:5px;'>
        <input type='submit' id='login_modal_btn' value='登录'/>
      </div>
    </div>
	<div class='float_l' style='border-left:1px solid #333; margin-top:20px; margin-left:70px; padding:0px 45px 80px 60px;'>
	  <div><span align='center'>使用新浪微博帐号登录</span></div>
	  <!--<div style='height:30px; margin-top:17px;' align='center'>
		  <span><input id='weibo_btn' type='button' onclick='weibo_login()'></span>		  
	  </div>--> 
	  <div style='margin-top:17px;'><span id="connectBtn" style='margin-top:17px;'></span></div>
	  
	</div>
  </div>
  </form>
</div>
<!-- End of Login Dialog -->  

<!-- Mask to cover the whole screen -->
<!-- <div id='mask'></div> -->
</div>

<div class='content'>
    <div id='homepage' class='content-a'>
      <div class='inner'>
	    <div style='padding-top:30px' class='cols-b signup'>
	      <div id='demoVideo'>	 
			<img id='video_preview' src='/storify/img/storify.png' width='456' height='301'/>
	      </div>
		  <div id='intro'>
		    <h3 class='blue'>创建属于你的故事!<br/>微博,开心,人人,优酷</h3>
		    <p style='color:#438cc3'>一切都是那么简单，动动手，用无穷无尽的社交网
		    <br/>络资源创建你自己的故事。发布分享你的故事，每
		    <br/>个人都是见证新时代的媒体人。
		    </p>
		    <div id='sign_in'>
		      <h2 style='padding-top:20px;' class='blue' align='center'>立即开始你的口立方旅程</h2>
		      <div align='center'>
		        <span><a id='login_btn' href='#dialog' name='modal' align='center'>登录</a></span>
				<span><a style='margin-left:15px; line-height:2.4; color:#336699;' href='/storify/register/register_form.php'/>马上注册</a></span>
			  </div>
		      <form method='post' action='/storify/register/get_invitationcode.php'> 
				<div align='center'>
				  <span > <input type='text' value='请输入邮箱地址' name='email' id='email_invitation' size='30' maxlength='100' onclick='this.value = ""'> </span>
				  <span><input type='submit' id='btn_request_invitation' value='获得邀请码'></input></span>
				</div>
			  </form>
		    </div>
		  </div>
	    </div>
		<div id='popular' style='height:300px;'>
		  <h3>最流行</h3>
		  <div class='userstory_list'>
		    <ul>
			<?php
			$story_content = '';
			$result=$DB->query("SELECT * FROM ".$db_prefix."posts limit 3");
			while ($story_item = mysql_fetch_array($result))
			{
			  //printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
			  $post_author = $story_item['post_author'];
			  $userresult = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user where id='".$post_author."'");
			  $post_title = $story_item['post_title'];
			  $post_date = $story_item['post_date'];
			  $temp_array = explode(" ", $story_item['post_date']);
			  $post_date = $temp_array[0];
			  $story_content .= "<li><a class='cover' style='background-image: url(/Storify/img/greece.jpg);' href='/Storify/member/user.php?post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='story_meta' 
			  ><span><img border='0' style='position:relative; top:2px' src='/Storify/img/sina16.png'/><a style='margin-left:5px;'>".$userresult['username']."</a><a style='margin-left:65px;'>".$post_date."</a></span></div></li>";
			}
			echo $story_content;
			?>
			</ul>
		  </div>
		</div>
		<div class='category'>
	      <div id='trendTopics' class='' style='display:block; height:150px;'>
			<h3 class='blue'>大家都在说</h3>
			<div class='topic_list'>
			  <ul>
			    <li><div class='topic_meta'><span>新闻</span><span  style='margin-left:50px;'>20</span></div><a class='topic_cover' style='background-image: url(/Storify/img/iphone.jpg);' href='#'><div class='title_wrap'><h1 class='title'>测试</h1></div></a></li>
				<li><div class='topic_meta'><span>新闻</span><span  style='margin-left:50px;'>20</span></div><a class='topic_cover' style='background-image: url(/Storify/img/iphone.jpg);' href='#'><div class='title_wrap'><h1 class='title'>测试</h1></div></a></li>
				<li><div class='topic_meta'><span>新闻</span><span  style='margin-left:50px;'>20</span></div><a class='topic_cover' style='background-image: url(/Storify/img/iphone.jpg);' href='#'><div class='title_wrap'><h1 class='title'>测试</h1></div></a></li>
				<li><div class='topic_meta'><span>新闻</span><span  style='margin-left:50px;'>20</span></div><a class='topic_cover' style='background-image: url(/Storify/img/iphone.jpg);' href='#'><div class='title_wrap'><h1 class='title'>测试</h1></div></a></li>
			  </ul>
			</div>
		  </div>
	      <div id='topUsers' class='float_l' style='display:block;'>
			<h3 class='blue'>排行榜</h3>
			<ol>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			  <li>测试 22 stories</li>
			</ol>
		  </div>
		</div>
	  </div>
    </div>
</div>

<script>

$(document).ready(function() {	
	WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
	{
	  var cfg = {
      //key: '314237338',
	  key: '2417356638',
	  xdpath: 'http://story.com/storify/html/xd.html'
	};
    WB.connect.init(cfg);
    WB.client.init(cfg);
	
	WB.widget.base.connectButton(document.getElementById('connectBtn'),
							   {
							     
								 login:function(o)
								 {
								   //debugger;
								   //alert(o.id);
								   //self.location = '/storify/member/';
								   //debugger;
								   //alert(o.id);
								   var weibo_user_id_val = o.id;
								   var weibo_scree_name_val = o.screen_name;
								   $.post('/Storify/login/weibo_login.php', {weibo_user_id: weibo_user_id_val, weibo_scree_name: weibo_scree_name_val}, 		
								   function(data, textStatus)
								   {
								     console.log(data);
								   });
								   self.location = '/storify/member/user.php';
								   //self.location = '/storify/member/testweibo.php';
								 },
								 logout:function()
								 {
								   alert('logout');
								 }
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

	//weibo part
	
	
});

</script>

<?php
 include "./include/footer.htm";
?>