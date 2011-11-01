<?php
include "global.php"; 
include "member/tagoperation.php";
?>
<link rel="stylesheet" type="text/css" href="css/skin.css" />
<link rel="stylesheet" href="css/orbit-1.2.3.css">
<!--[if IE]>
	<style type="text/css">
		 .timer { display: none !important; }
		 div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
	</style>
<![endif]-->
<div id='boxes'>  
<!-- Start of Login Dialog -->  
<div id='dialog' class='window' style='padding:0;'>
  <div style='background-color:#ababac; padding:5px;'><span>登录 koulifang.com</span> | <span><a href='register/register_form.php'/>还没有注册？</a><span> <span><a href='#' class='close'/>关闭</a></span></div>
  <form method='post' action='login/login.php'>
  <div>
    <div id='login_modal' class='float_l'>
      <div style='padding-left:5px;'><b> 邮 箱 &nbsp; </b><span><input type='text' name='email' id='email_login' onclick='this.value=""'/></span></div>
      <div style='padding-left:5px;'><b> 密 码 &nbsp; </b> <span><input type='password' name='passwd' id='pwd_login' onclick='this.value=""'/> </span></div>
      <div style='padding-left:2px;'><span> <input type='checkbox' name='autologin'>下次自动登录</span> | <span><a href='login/forget_form.php'/>忘记密码了？</a><span></div>
      <div style='padding-left:5px; margin-top:5px;'>
        <input type='submit' id='login_modal_btn' value='登录'/>
      </div>
    </div>
	<div class='float_l' style='border-left:1px solid #333; margin-top:15px; margin-left:70px; padding:0px 45px 24px 60px;'>
	  <div style='margin-bottom:5px;'>还没有口立方帐号?</div>
	  <a class='large green awesome register_awesome' href='/register/register_form.php'/>马上注册 &raquo;</a>
	  <div style='margin-top:15px;'><span align='center'>使用新浪微博帐号登录</span></div>
	  <div style='margin-top:5px;'><a id='connectBtn' href='#'><div class='sina_icon'></div><div class='sina_name'>新浪微博</div></a></div>  
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
	    <?php
		if(!islogin())
		{
		  $slider_content ="
	      <div id='featured_container'>
		    <div id='featured'> 
			  <img src='img/slide1.jpg' style='width:920px; height:320px;' />
			  <a href=''><img src='img/slide2.jpg' style='width:920px; height:320px;'/></a>
			  <img src='img/slide3.jpg' data-caption='#htmlCaption' style='width:920px; height:320px;'/>
			  <img src='img/slide4.jpg'  style='width:920px; height:320px;'/>
		    </div>
		    <span class='orbit-caption' id='htmlCaption'><strong>Badass Caption:</strong> I can haz <a href='#'>links</a>, <em>style</em> or anything that is valid markup :)</span>
		  </div>";
		  echo $slider_content;
		}
		else
		{
		  echo "<div style='height:20px;'></div>";
		}
		?>
		<div id='popular'>
		  <h3 style='color:#999999; padding-top:5px;'>最流行</h3>
		  <div id='pop_wrapper' style='height:290px;'>
		    <div id='time_wrapper'><a class='time_range'>三天内</a><a class='time_range selected'>一周内</a><a class='time_range'>一月内</a><a class='time_range'>365天内</a></div>
		    <ul id='pop_list'>
			<?php
			$story_content = '';
			$i_query = "select * from ".$db_prefix."posts where post_status = 'Published' and TO_DAYS(NOW())-TO_DAYS(post_date) <=7 order by post_digg_count desc limit 4";
			$result=$DB->query($i_query);
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
			  $story_content .= "<li><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='member/user.php?user_id=".$post_author."&post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='story_meta' 
			  ><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;' href='member/user.php?user_id=".$post_author."'>".$userresult['username']."</a><a style='float:right; vertical-align:top;'>".$post_date."</a></span></div></li>";
			}
			echo $story_content;
			?>
			</ul>
		  </div>
		  <div><a id='story_more'>换一组看看</a></div>
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

<?php
 include "./include/footer.htm";
?>
<script type="text/javascript" src="js/jquery.orbit-1.2.3.min.js"></script>	
<script>
$(document).ready(function() 
{	
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
		var imgloading = $("<span style='padding-left:180px;'><img src='/img/loading.gif' /></span>");
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
	  beforeSend:function() 
	  {
		var imgloading = $("<span style='padding-left:180px;'><img src='/img/loading.gif' /></span>");
		$('this').html(imgloading);
	  },
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
	  bullets: true
	});
});
</script>
