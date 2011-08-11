<?php
include "global.php"; 
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
		<div class='category'>
	      <div id='trendTopics' class='' style='display:block;'>
			<h3 class='blue'>大家都在说</h3>
		  </div>
	      <div id='topUsers' class='' style='display:block;'>
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