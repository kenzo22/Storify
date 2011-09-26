<?php
include "global.php"; 
include "member/tagoperation.php";
//select a random item from the publictoken pool
if(!islogin())
{
  $token = $DB->fetch_one_array("select * from ".$db_prefix."publictoken where id='1'");
  $_SESSION['last_key']['oauth_token'] = $token['weibo_access_token'];
  $_SESSION['last_key']['oauth_token_secret'] = $token['weibo_access_token_secret'];
  $_SESSION['last_tkey']['oauth_token'] = $token['tweibo_access_token'];
  $_SESSION['last_tkey']['oauth_token_secret'] = $token['tweibo_access_token_secret'];
}

?>
<link rel="stylesheet" type="text/css" href="css/skin.css" />
<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/startstop-slider.js"></script>
<div id='boxes'>
  
<!-- Start of Login Dialog -->  
<div id='dialog' class='window' style='padding:0;'>
  <div style='background-color:#ababac; padding:5px;'><span>登录 koulifang.com</span> | <span><a href='register/register_form.php'/>还没有注册？</a><span> <span><a href='#' class='close'/>关闭</a></span></div>
  <form method='post' action='login/login.php'>
  <div>
    <div id='login_modal' class='float_l' style='margin-top:10px;'>
      <div style='padding-left:5px;'><b> 邮 箱 &nbsp; </b><span><input type='text' name='email' id='email_login' onclick='this.value=""'/></span></div>
      <div style='padding-left:5px;'><b> 密 码 &nbsp; </b> <span><input type='password' name='passwd' id='pwd_login' onclick='this.value=""'/> </span></div>
      <div style='padding-left:5px;'><span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='login/forget_form.php'/>忘记密码了？</a><span></div>
      <div style='padding-left:5px;'>
        <input type='submit' id='login_modal_btn' value='登录'/>
      </div>
    </div>
	<div class='float_l' style='border-left:1px solid #333; margin-top:20px; margin-left:70px; padding:0px 45px 80px 60px;'>
	  <div><span align='center'>使用新浪微博帐号登录</span></div>
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
	    <div id="page-wrap">
		  <div id="slider">
			<div id="mover">
			  <div id="slide-1" class="slide">
				<h1>Garden Rack</h1>
				<p>Donec gravida posuere arcu. Nulla facilisi. Phasellus imperdiet. Vestibulum at metus. Integer euismod. Nullam placerat rhoncus sapien. Ut euismod. Praesent libero. Morbi pellentesque libero sit amet ante. Maecenas tellus.</p>
				<a href="#"><img src="img/slide-1-image.png" alt="learn more" /></a>	
			  </div>
			  <div class="slide">
				<h1>Tulip Bulbs</h1>
				<p>Donec gravida posuere arcu. Nulla facilisi. Phasellus imperdiet. Vestibulum at metus. Integer euismod. Nullam placerat rhoncus sapien. Ut euismod. Praesent libero. Morbi pellentesque libero sit amet ante. Maecenas tellus.</p>
				<a href="#"><img src="img/slide-2-image.png" alt="learn more" /></a>	
			  </div>
			  <div class="slide">
				<h1>Garden Gloves</h1>
				<p>Donec gravida posuere arcu. Nulla facilisi. Phasellus imperdiet. Vestibulum at metus. Integer euismod. Nullam placerat rhoncus sapien. Ut euismod. Praesent libero. Morbi pellentesque libero sit amet ante. Maecenas tellus.</p>
				<a href="#"><img src="img/slide-3-image.png" alt="learn more" /></a>	
			  </div>
			</div>
		  </div>
	    </div>
	    <!--<div style='padding-top:30px' class='cols-b signup'>
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
	    </div>-->
		<div id='popular' style='height:300px;'>
		  <h3 style='color:#999999'>最流行</h3>
		  <div id='popularstory_list'>
		    <ul id='mycarousel' class='jcarousel-skin-tango'>
			<?php
			$story_content = '';
			$result=$DB->query("SELECT * FROM ".$db_prefix."posts order by post_digg_count desc limit 10");
			while ($story_item = mysql_fetch_array($result))
			{
			  //printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
			  $post_author = $story_item['post_author'];
			  $post_pic_url = $story_item['post_pic_url'];
			  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$post_author."'");
			  $user_profile_img = $userresult['photo'];
			  $post_title = $story_item['post_title'];
			  $post_date = $story_item['post_date'];
			  $temp_array = explode(" ", $story_item['post_date']);
			  $post_date = $temp_array[0];
			  $story_content .= "<li><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='member/user.php?post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='story_meta' 
			  ><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;'>".$userresult['username']."</a><a style='float:right; vertical-align:top;'>".$post_date."</a></span></div></li>";
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
			    <?php
				  //get the tag information from the tag table
				$tag_content = '';
				  //need change to fetch the most popular topic from the database
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
                    if(++$tag_i > 4)
                        break;
                    $used_story[] = $item['id'];
                
					$tag_content .= "<li><div class='topic_meta'><span class='topic_title'>#".$tag_name."#</span><span class='story_count'>".$tag_count."</span></div>
					<a class='topic_cover' style='background-image: url(".$item['post_pic_url'].");' href='./topic/topic.php?topic=".$tag_name."'><div class='title_wrap'><h1 class='title'>".$item['post_title']."</h1></div></a></li>";
				  }
				  echo $tag_content;
				?>
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
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

$(document).ready(function() 
{	
  $('.login_top').attr('name', 'modal').attr('href', '#dialog');
  
  $('#mycarousel').jcarousel({
        auto: 3,
        wrap: 'circular',
		scroll:1,
        initCallback: mycarousel_initCallback
    });
  
  /*WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
  {
    var cfg = 
	{
      key: '2417356638',
      xdpath: 'http://koulifang.com/html/xd.html'
    };
    WB.connect.init(cfg);
    WB.client.init(cfg);

    WB.widget.base.connectButton(document.getElementById('connectBtn'),
    {
      login:function(o)
	  {
	    var weibo_user_id_val = o.id;
	    var weibo_scree_name_val = o.screen_name;
	    $.post('login/weibo_login.php', {weibo_user_id: weibo_user_id_val, weibo_scree_name: weibo_scree_name_val}, 		
	    function(data, textStatus)
	    {
		  $('.window').hide();
		  $('.top_nav').replaceWith(data);
	    });
	  },
	  logout:function()
	  {
	    alert('logout');
	  }
    });
  });*/
  
  WB2.anyWhere(function(W){
	W.widget.connectButton({
			id: "connectBtn",
			callback : {
				login:function(o){
					var weibo_user_id_val = o.id;
					var weibo_scree_name_val = o.screen_name;
					$.post('login/weibo_login.php', {weibo_user_id: weibo_user_id_val, weibo_scree_name: weibo_scree_name_val}, 		
					function(data, textStatus)
					{
					  $('.window').hide();
					  $('.top_nav').replaceWith(data);
					});
				},
				logout:function(){
					alert('logout');
				}
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
