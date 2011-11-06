<?php
include "../global.php";
include_once "../include/weibo_functions.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );
include_once( '../tweibo/config.php' );
include_once( '../tweibo/txwboauth.php' );
include_once( '../douban/config.php' );
include_once( '../douban/doubanapi.php' );
include_once "userrelation.php";
?>
<link type="text/css" href="../css/jquery.ui.theme.css" rel="stylesheet" />

<?php
$date_t = date("Y-m-d H:i:s");

if(isset($_GET['user_id']) && isset($_GET['post_id']) && !isset($_GET['action']))
{
	$post_id = $_GET['post_id'];
	$user_id = $_GET['user_id'];
	//update the page view
	$selResult = $DB->fetch_one_array("SELECT id FROM ".$db_prefix."pageview WHERE story_id='".$post_id."' AND domain_name='koulifang.com'" );
	if(!empty($selResult))
	{
	  $viewresult=$DB->query("update ".$db_prefix."pageview set view_count=view_count+1  WHERE story_id='".$post_id."' AND domain_name='koulifang.com'" );
	}
	else
	{
	  $viewresult=$DB->query("insert into ".$db_prefix."pageview values(null, '".$post_id."', 'koulifang.com', '', 1)");
	}
	
	$c = new WeiboClient(WB_AKEY , WB_SKEY , $_SESSION['last_wkey']['oauth_token'] , $_SESSION['last_wkey']['oauth_token_secret']);
	$t = new TWeiboClient(MB_AKEY , MB_SKEY , $_SESSION['last_tkey']['oauth_token'] , $_SESSION['last_tkey']['oauth_token_secret']);
	$d = new DoubanClient(DB_AKEY , DB_SKEY , $_SESSION['last_dkey']['oauth_token'] , $_SESSION['last_dkey']['oauth_token_secret']);
	$result = $DB->fetch_one_array("select * from ".$db_prefix."posts where ID='".$post_id."'");
	if(!$result)
	{
	  throw new Exception('Could not execute query.');
	}
	$story_author = $result['post_author'];
	
	$userresult = $DB->fetch_one_array("SELECT username, intro, photo, weibo_user_id, tweibo_access_token FROM ".$db_prefix."user where id='".$_SESSION['uid']."'");
	$has_sina = false;
	$has_tencent = false;
	if($userresult['weibo_user_id'] != 0)
	{
	  $has_sina = true;
	}
	if($userresult['tweibo_access_token'] != '')
	{
	  $has_tencent = true;
	}
	if($story_author != $_SESSION['uid'])
	{
	  $userresult = $DB->fetch_one_array("SELECT username, intro, photo FROM ".$db_prefix."user where id='".$story_author."'");
	}
	$story_embed = $result['embed_name'];
	$story_time = dateFormatTrans($result['post_date'],$date_t);
	$story_title=$result['post_title'];
	$story_summary=$result['post_summary'];
	$story_pic=$result['post_pic_url'];
	$story_status=$result['post_status'];
	$story_content=$result['post_content'];
	$story_digg_count=$result['post_digg_count'];
	$embed_code = "<script src=\"http://koulifang.com/user/".$story_author."/".$story_embed.".js\"></script>";
	//get the profile image of the story author
	$user_profile_img;
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
	  $user_profile_img =$userresult['photo'];
    }
	
	$temp_array = json_decode($story_content, true);
	$items_perpage = 10;
	$story_content_array = array_slice($temp_array['content'], 0, $items_perpage, true);
	$weibo_id_array = array();
	$tweibo_id_array = array();
	$login_status = islogin();
	if($login_status)
	{
	  $extra_class = "";
	  if($has_sina)
	  {
	    $extra_class .=" sina";
	  }
	  if($has_tencent)
	  {
	    $extra_class .=" tencent";
	  }
	  $content = "<div id='boxes'>
				  <div id='weibo_dialog' class='window".$extra_class."'>
					<div style='background-color:#f3f3f3; padding:5px; margin-bottom:10px;'><span id='publish_title' style='color: #336699;'>发表微博</span><span><a href='#' class='close'/>关闭</a></span></div>
					<div id='pub_wrapper'>
					  <div class='float_r counter_wrapper'><span style='margin-left:28px; color: #B8B7B7;'>还可以输入</span><span class='word_counter'>140</span><span style='color: #B8B7B7;'>字</span></div>
					  <textarea class='publish-tweet'></textarea>
					  <a class='btn_w_publish large blue awesome'><span id='pub_text'>转发</span></a>
					</div>
					<div class='pub_imply_sina'><span style='margin-left:6px; margin-right:5px; color:#878787;'>发布到新浪微博需要绑定新浪微博帐号</span><a href='/member/source.php'>现在去绑定</a></div>
					<div class='pub_imply_tencent'><span style='margin-left:6px; margin-right:5px; color:#878787;'>发布到腾讯微博需要绑定腾讯微博帐号</span><a href='/member/source.php'>现在去绑定</a></div>
				  </div>
				  <div id='mask'></div>
				</div>";
	}
	else
	{
	  $content = "<div id='boxes'>
				  <div id='weibo_dialog' class='window disable'>
					<div style='background-color:#f3f3f3; padding:5px; margin-bottom:10px;'><span id='publish_title' style='color: #B8B7B7;'>发表微博</span><span><a href='#' class='close'/>关闭</a></span></div>
					<div class='imply_color' style='margin-bottom:10px;'>对不起，只有本站注册用户能使用该功能</div>
					<div class='imply_color'>请您<a href='/login/login_form.php?next'>登录</a>或<a href='/register/register_form.php'>注册</a></div>
				  </div>
				  <div id='mask'></div>
				</div>";
	}
	
	if(!$login_status|| $story_author != $_SESSION['uid'])
	{
	  $content .= "<div id='story_container'><div style='float:left;'><div id='publish_container' class='showborder'>";
	}
	else
	{
	  if(0 == strcmp($story_status, 'Published'))
	  {
		$content .= "<div id='story_container'>
					  <div style='float:left;'>
					  <div class='published-steps'>
						<div class='tabs'>
						  <button class='post-tab'>
							<div class='icon'></div>
							<h2>发布到您的网站上</h2>
							<span>嵌入故事，轻松简单</span>
						  </button>
						  <button class='notify-tab'>
							<div class='icon'></div>
							<h2>通告</h2>
							<span>喝水不忘挖井人</span>
						  </button>
						  <button class='share-tab'>
							<div class='icon'></div>
							<h2>分享</h2>
							<span>好故事要让更多人看见</span>
						  </button>
						</div>
						<div class='steps'>
						  <div class='post-content'>
						    <h2>轻松嵌入故事到你的网站中~</h2>
							<span>复制嵌入代码:</span><span><input type='text' value='".$embed_code."' class='sto_embed' size='72'></span><a title='如何嵌入' class='embed_how'></a>
						  </div>
						  <div class='notify-content'>
						    <h2>饮水思源，告诉作者你引用了他们的内容~</h2>";
		$weiboFlag=false;
		$tweiboFlag=false;
		$w_user_count = 0;
		$t_user_count = 0;
		
		//prepare the weibo author info to be notified
		$w_nic_array = array();
	    $msg = $c->verify_credentials();
	    if (isset($msg['id']))
	    {
		  $weibo_nick = $msg['screen_name'];
	    }
	    foreach($temp_array['content'] as $tempItem)
	    {
		  if(0 == strcmp($tempItem['type'], 'weibo'))
		  {
		    if($weibo_nick != $tempItem['content']['nic'])
		    {
			  $w_nic_array[] = $tempItem['content']['nic'];
		    }
		  }
	    }
	    $w_nic_array = array_unique($w_nic_array);
		$w_nic_array = array_merge($w_nic_array);
	    $w_array_length = count($w_nic_array);
		if($has_sina)
		{
		  if($w_array_length>0)
		  {
		    $weiboFlag=true;
		    $content.="<div id='weibo_card_area' class='sina_user'>";
		    for($i=0; $i<$w_array_length; $i++)
		    {
		      $w_user_count += utf8_strlen($w_nic_array[$i]);
			  $content.="<div class='notify-user'><input type='checkbox' value='mashable' name='to[]' checked='checked' /><span>@".$w_nic_array[$i]."</span></div>";
		    }
		    $content.="</div>";
			$w_user_count += 2*$w_array_length;
		  }
		}
		else
		{
		  if($w_array_length>0)
		  {
		    $content.="<div class='sina_user'>
					   <span style='margin-left:6px; margin-right:5px; color:#878787;'>发布到新浪微博需要绑定新浪微博帐号</span><a href='/member/source.php'>现在去绑定</a>
					</div>";
		  } 
		}
		
		$t_array = array();
	    $tmsg  =  $t->getinfo();
	    if (isset($tmsg['data']['nick']))
	    {
		  $tweibo_nick = $tmsg['data']['nick'];
	    }
	    foreach($temp_array['content'] as $tempItem)
	    {
		  if(0 == strcmp($tempItem['type'], 'tweibo'))
		  {
		    if($tweibo_nick != $tempItem['content']['nic'])
		    {
			  $t_array[$tempItem['content']['name']] = $tempItem['content']['nic'];
		    }
		  }
	    }
	    $t_array = array_unique($t_array);
	    $t_array_length = count($t_array);
		if($has_tencent)
		{
		  if($t_array_length>0)
		  {
		    $tweiboFlag=true;
		    $content.="<div class='tencent_user' style='clear:both;'>";
		    foreach($t_array as $tkey=>$tval)
		    {
		      $t_user_count += utf8_strlen($tkey);
			  $content.="<div class='notify-user'><input type='checkbox' value='mashable' name='to[]' checked='checked' /><span id='".$tkey."'><a href='http://t.qq.com/".$tkey."' target='_blank'>@".$tval."</a></span></div>";
		    }
		    $content.="</div>";
			$t_user_count += 2*$t_array_length;
		  }
		}
		else
		{
		  if($t_array_length>0)
		  {
		    $content.="<div class='tencent_user' style='clear:both;'>
					   <span style='margin-left:6px; margin-right:5px; color:#878787;'>广播到腾讯微博需要绑定腾讯微博帐号</span><a href='/member/source.php'>现在去绑定</a>
					</div>";
		  }
		}
		
		if($weiboFlag || $tweiboFlag)
		{
		  $user_count = ($w_user_count > $t_user_count)?$w_user_count:$t_user_count;
		  $weibo_dis = $weiboFlag?"":"disabled='disabled'";
		  $tweibo_dis = $tweiboFlag?"":"disabled='disabled'";
		  $weibo_check = $weiboFlag?"checked='checked'":" ";
		  $tweibo_check = $tweiboFlag?"checked='checked'":" ";
		  $current_page_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		  $url_result = $c->shorten_url($current_page_url);
		  $url_short = $url_result[0]['url_short'];
		  $base_txt = "我刚刚引用了你的微博，快来看一看吧：";
		  $word_remain = ceil(140-($user_count/2+strlen($url_short)/2+strlen($base_txt)/3));
		  $content.="<textarea class='notify-tweet' name='tweet'>".$base_txt.$url_short."</textarea>
		  <div class='tweet_control'><input id='weibo_f' type='checkbox' name='weibo_f'".$weibo_check.$weibo_dis." /><span>发布到新浪微博</span><input id='tweibo_f' type='checkbox' name='tweibo_f'".$tweibo_check.$tweibo_dis." /><span>发布到腾讯微博</span><span style='margin-left:28px; color: #B8B7B7;'>还可以输入</span><span class='word_counter'>".$word_remain."</span><span style='color: #B8B7B7;'>字</span><input class='tweet_btn' style='margin-left:15px; cursor:pointer;' type='submit' value='发布'></div>";
		}
		if($w_array_length == 0 && $t_array_length == 0)
		{
		  $content.="<div style='color:#878787'>您没有引用别人的微博内容</div>";
		}
	    $content.="</div>
				  <div class='share-content'>
				    <h2>好东西大家都来分享~</h2>
					<div id='jiathis_style_32x32'>
					  <a class='jiathis_button_qzone'></a><a class='jiathis_button_tsina'></a>
					  <a class='jiathis_button_tqq'></a>
					  <a class='jiathis_button_renren'></a><a class='jiathis_button_kaixin001'></a>
					  <a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a>
					  <a class='jiathis_counter_style'></a>
					</div>
				  </div>
				</div>
				<div class='spacer'></div>
			  </div>";
		$content .= "<div id='publish_container' class='showborder'>
			  <div id='story_action'><span><div class='publish_icon' title='已发布'></div>已发布</span><span class='float_r'><a id='".$post_id."_delete' class='delete redirect'></a>&nbsp<a class='edit' href='/member/user.php?user_id=".$user_id."&post_id=".$post_id."&action=edit'></a></span></div>";
	  }
	  else
	  {
	    $content .= "<div id='story_container'><div style='float:left;'><div id='publish_container' class='showborder'>
			  <div id='story_action'><span><div class='draft_icon' title='草稿'></div>草稿</span><span class='float_r'><a  class='publish' href='/member/user.php?user_id=".$user_id."&post_id=".$post_id."&action=publish'></a>&nbsp<a id='".$post_id."_delete' class='delete redirect'></a><a class='edit' href='/member/user.php?user_id=".$user_id."&post_id=".$post_id."&action=edit'></a></span></div>";
	  }	
	}

    // get tags for this story
    $tag_query = "select name from story_tag,story_tag_story where story_tag.id=tag_id and story_id=".$post_id;
    $tag_names = $DB->query($tag_query);
    if($DB->num_rows($tag_names) > 0){
        while($tag_name_row = $DB->fetch_array($tag_names)){
            $tags .= "<a class='tag_item' href='/topic/topic.php?topic=".$tag_name_row['name']."'>".$tag_name_row['name']."</a>";
        }
    }

	$content .="<div id='story_header'>
				  <div style='float:right; padding: 0 10px 0 0'><img src='".$story_pic."' style='width:60px; height:60px;' /></div>
				  <div id='story_meta' style='margin-top:10px;'>
				    <div class='story_title'>".$story_title."</div>
				    <div class='story_author'>by<a href='http://koulifang.com/member/user.php?user_id=".$user_id."'>".$userresult['username']."</a>, ".$story_time."</div>
				    <div class='story_sum'>".$story_summary."</div>";
			if($tags!='')
			{
			  $content .="<div class='story_tag'>标签:".$tags."</div>";
			}
			$content .="</div>
				  <div class='tool_wrapper'>
					  <div class='story_share'>
						<div class='digg_wrap'><a id='".$post_id."_act_digg' class='act_digg' title='赞一个'></a><span id='".$post_id."_digg_count' class='digg_counter' title='累计赞".$story_digg_count."次'>".$story_digg_count."</span></div>
						<div id='ckepop'>
							<span class='jiathis_txt'>分享到：</span>
							<a class='jiathis_button_qzone'></a>
							<a class='jiathis_button_tsina'></a>
							<a class='jiathis_button_tqq'></a>
							<a class='jiathis_button_renren'></a>
							<a class='jiathis_button_kaixin001'></a>
							<a href='http://www.jiathis.com/share?uid=1542042' class='jiathis jiathis_txt jtico jtico_jiathis' target='_blank'></a>
							<a class='jiathis_counter_style'></a>
						</div>
						<div id='story_embed'>
						  <a href='#' id='embed_a'>嵌入故事<span class='arrow_down'></span><span class='arrow_up'></span></a>
					    </div>
					  </div>
					  <div id='embed_bar'><span style='margin-left:20px;'>复制嵌入代码:</span><span><input type='text' class='sto_embed' value='".$embed_code."' size='71'></span><a title='如何嵌入' class='embed_how'></a></div>
				  </div>
				</div><ul id='weibo_ul' style='padding:0;'>";
	
	foreach($story_content_array as $key=>$val)
	{
	  switch($val['type'])
	  {
	    case "weibo":{
		$weibo_meta_data = $val['content'];
		$weibo_per_id = $weibo_meta_data['id'];
		$single_weibo  = $c->show_status($weibo_per_id );
		
		if ($single_weibo === false || $single_weibo === null){
		echo "<br/><br/><br/><br/><br/>Error occured";
		//return false;
		}
		if (isset($single_weibo['error_code']) && isset($single_weibo['error'])){
            // skip deleted weibo
            $content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>此微博已被删除</span></div>";
			//$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'><div class='story_wrapper'><div><span class='weibo_text'>errorcode:".$single_weibo['error_code']."error".$single_weibo['error']."</span></div>";
            continue;
		}
		if (isset($single_weibo['id']) && isset($single_weibo['text'])){
            
            // show emotions in text
            $single_weibo['text'] = subs_emotions($single_weibo['text'],"weibo");

            $single_weibo['text'] = subs_url($single_weibo['text'],'weibo');

			$createTime = dateFormatTrans(dateFormat($single_weibo['created_at']),$date_t);
			$content .="<li class='weibo_drop sina' id='$weibo_per_id' style='border:none;'>";
    		if (isset($single_weibo['retweeted_status'])){
                
                $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div>
				<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
				// show emotions in text
                $single_weibo['retweeted_status']['text']=subs_emotions($single_weibo['retweeted_status']['text'],"weibo");

                $single_weibo['retweeted_status']['text']=subs_url($single_weibo['retweeted_status']['text']);

                $content .="//@".$single_weibo['retweeted_status']['user']['name'].":".$single_weibo['retweeted_status']['text'];
                if(isset($single_weibo['retweeted_status']['bmiddle_pic'])){
                    $content .= "</span><div class='weibo_retweet_img' style='text-align:center;'><img src='".$single_weibo['retweeted_status']['bmiddle_pic']."' width='280px;' /></div>";
                }
				else
				{
				  $content .= "</span>";
				}
            }
			else{
			  $content .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f sina'><span>转发</span></a><a href='#weibo_dialog' name='modal' class='comment_f sina'><span>评论</span></a></div><div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$single_weibo['text'];
			}
            if (isset($single_weibo['bmiddle_pic']))
			{
			  $content .= "<div class='weibo_img' style='text-align:center;'><img src='".$single_weibo['bmiddle_pic']."' width='280px;' /></div>";
			}
            $content .= "</div>";
            $content .= "<div class='story_signature'><span style='float:right;'><a href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'><img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
			.$single_weibo['user']['profile_image_url']."' alt='".$single_weibo['user']['screen_name']."' border=0 /></a></span><span class='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px;'>
			<span><a class='weibo_from_drop' href='http://weibo.com/".$single_weibo['user']['id']."' target='_blank'>".$single_weibo['user']['screen_name']."</a></span></div><div class='weibo_date_drop'>".$createTime."</div></span> </div></div></li>";
		}
		break;}
		 
		case "tweibo":{
		$tweibo_meta_data = $val['content'];
		$tweibo_per_id = $tweibo_meta_data['id'];
		$tweibo_id_array[] = $tweibo_per_id;
		$content .="<li id='$tweibo_per_id'></li>"; 
		break;}
		 
		case "douban":{
		$douban_save_per_id = $val['content']['item_id'];
		if($val['content']['item_type'] == 'event')
		{
		  $doubanElement = $d->get_event($douban_save_per_id);
		  $eventImg = getItemPic($doubanElement['link']);
		  $eventLink = getItemLink($doubanElement['link']);
		  $eventInitiator_url = getAuthorLink($doubanElement['author']['link']);
		  $eventInitiator_name = $doubanElement['author']['name']['$t'];
		  $eventInitiator_pic = getAuthorPic($doubanElement['author']['link']);
		  
		  $content .=
		 "<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
		    <div class='douban_wrapper'>
			  <div class='quote_sign'>“</div>
			  <div class='content_wrapper'>
			  <div class='event_summary'>".$doubanElement['summary'][0]['$t']."</div>
			  <div style='margin-top:10px; overflow:auto;'>
			    <a href='".$doubanElement['link'][1]['@href']."' target='_blank'>
				  <img class='item_img' src='".$eventImg."' style='float:left;' />
				</a>
				<div class='item_meta' style='margin-left:220px;'>
				  <div class='event_title'>活动：<a href='".$eventLink."' target='_blank'>".$doubanElement['title']['$t']."</a></div>
				  <div class='event_initiator'>发起人：<a href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a></div>
				  <div class='start_time'>".$doubanElement['gd:when']['startTime']."</div>
				  <div class='end_time'>".$doubanElement['gd:when']['endTime']."</div>
				  <div class='event_city'>".$doubanElement['db:location']['$t']."</div>
				  <div class='event_location'>".$doubanElement['gd:where']['@valueString']."</div>
				</div>
			  </div>
			  </div>
			  <div id='douban_signature' style='overflow:auto;'>
			    <span style='float:right;'>
				  <a href='".$eventInitiator_url."' target='_blank'>
				    <img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$eventInitiator_pic."' alt='".$eventInitiator_name."' border=0 />
				  </a>
				</span>
				<span class='signature_text' style=' margin-right:5px; float:right;' >
				  <div style='text-align:right; height:16px;'>
				    <span >
					  <a class='douban_from_drop' href='".$eventInitiator_url."' target='_blank'>".$eventInitiator_name."</a>
					</span>
				  </div>
				  <div class='douban_date_drop'></div>
				</span> 
			  </div>
			</div>
	      </li>";
		}
		else
		{
		  if($val['content']['item_type'] == 'bookReviews' || $val['content']['item_type'] == 'movieReviews' || $val['content']['item_type'] == 'musicReviews')
		  {
		    $douban_item_meta;
		    $douban_item_date;
		    $douban_item_author;
		    $doubanElement = $d->get_comment($douban_save_per_id);
			
			$comment_author_link = getAuthorLink($doubanElement['author']['link']);
			$comment_author_pic = getAuthorPic($doubanElement['author']['link']);
			$itemPic = getItemPic($doubanElement['db:subject']['link']);
			
		    $douban_per_url = getItemLink($doubanElement['db:subject']['link']);
		    $url_array  = explode("/", $douban_per_url);
		    $douban_item_per_id = $url_array[4];
		    if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_meta = $d->get_book($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_meta = $d->get_movie($douban_item_per_id);
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_meta = $d->get_music($douban_item_per_id);
			}
			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$author = getAuthors($douban_item_meta['author']);
			
			if($val['content']['item_type'] == 'bookReviews')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movieReviews')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'musicReviews')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
			}
			$comment_rating = 2*$doubanElement['gd:rating']['@value'];
			$time_array = explode("T", $doubanElement['updated']['$t']);
			$content .=
			"<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
			  <div class='douban_wrapper'>
			    <div class='quote_sign'>“</div>
				<div class='content_wrapper'>
				<div>
				  <div class='comment_title' style='font-weight:bold;'>".$doubanElement['title']['$t']."</div>
				  <div class='comment_summary'>".$doubanElement['summary']['$t']."</div>
				  <div style='text-align:right;'>
					<a href='".$doubanElement['link'][1]['@href']."' target='_blank'>查看评论全文</a>
				  </div>
				</div>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$douban_per_url."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$douban_per_url."' target='_blank'>".$doubanElement['db:subject']['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class=item_rating>".$doubanElement['author']['name']['$t']."评分:".$comment_rating."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				</div>
				<div id='douban_signature' style='overflow:auto;'>
				  <span style='float:right;'>
					<a href='".$comment_author_link."' target='_blank'>
					  <img class='profile_img' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='".$comment_author_pic."' alt='".$doubanElement['author']['name']['$t']."' border=0 />
					</a>
				  </span>
				  <span class='signature_text' style=' margin-right:5px; float:right;' >
					<div style='text-align:right; height:16px;'>
					  <span >
						<a class='douban_from_drop' href='".$doubanElement['author']['link'][1]['@href']."' target='_blank'>".$doubanElement['author']['name']['$t']."</a>
					  </span>
					</div>
					<div class='douban_date_drop'>".$time_array[0]."</div>
				  </span> 
				</div>
			  </div>
			</li>";
		  }
		  else if($val['content']['item_type'] == 'book' || $val['content']['item_type'] == 'movie' || $val['content']['item_type'] == 'music')
		  {
		    if($val['content']['item_type'] == 'book')
			{
			  $douban_item_meta = $d->get_book($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_meta = $d->get_movie($douban_save_per_id);
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_meta = $d->get_music($douban_save_per_id);
			}
			
			$pubDate = getPubDate($douban_item_meta['db:attribute']);
			$itemPic = getItemPic($douban_item_meta['link']);
			$itemLink = getItemLink($douban_item_meta['link']);
			$author = getAuthors($douban_item_meta['author']);
			
			if($val['content']['item_type'] == 'book')
			{
			  $douban_item_author = "作者：".$author;
			  $douban_item_date = "出版年：".$pubDate;
			}
			else if($val['content']['item_type'] == 'movie')
			{
			  $douban_item_author = "导演：".$author;
			  $douban_item_date = "上映日期：".$pubDate;
			}
			else if($val['content']['item_type'] == 'music')
			{
			  $douban_item_author = "表演者：".$author;
			  $douban_item_date = "发行时间：".$pubDate;
			}
			$content .=
			"<li class='douban_drop douban' id='$douban_save_per_id' style='border:none;'>
			  <div class='douban_wrapper'>
			    <div class='content_wrapper'>
				<div class='item_info' style='overflow:auto;'>
				  <a href='".$itemLink."' target='_blank'><img class='item_img' src='".$itemPic."' style='float:left;' /></a>
				  <div class='item_meta' style='margin-left:100px;'>
					<div><a class='item_title' href='".$itemLink."' target='_blank'>".$douban_item_meta['title']['$t']."</a></div>
					<div class='item_author'>".$douban_item_author."</div>
					<div class='item_date'>".$douban_item_date."</div>
					<div class='average_rating'>豆瓣评分:".$douban_item_meta['gd:rating']['@average']."&nbsp&nbsp&nbsp&nbsp共".$douban_item_meta['gd:rating']['@numRaters']."人参与投票</div>
				  </div>
				</div>
				</div>
				<div class='douban_signature'></div>
			  </div>
			</li>";
		  }
		}
		break;}
		 
		case "comment":{
		$comment_text = $val['content'];
		$content .="<li class='textElement'><div class='commentBox'>".$comment_text."</div></li>";	
		break;}
		 
		case "video":{
		$video_meta = $val['content'];
		$video_title = $video_meta['title'];
		$video_src = $video_meta['src'];
		$video_url = $video_meta['url'];
		$content .="<li class='video_element'><div><a class='videoTitle' target='_blank' href='".$video_url."'>".$video_title."</a></div><div class='embed'>
		<embed src='".$video_src."' quality='high' width='420' height='340' align='middle' allowscriptaccess='always' allowfullscreen='true' mode='transparent' type='application/x-shockwave-flash' wmode='opaque'></embed></div></li>";
		break;}
		 
		case "photo":{
		$photo_meta_data = $val['content'];
		$photo_title = $photo_meta_data['title'];
		$photo_author = $photo_meta_data['author'];
		$photo_per_url = $photo_meta_data['url'];
		$photo_id = $photo_meta_data['id'];
		$author_nic = $photo_meta_data['nic'];
		$photo_link = "http://www.yupoo.com/photos/".$photo_author."/".$photo_id."/";
		$content .="<li class='photo_element'><div class='yupoo_wrapper'><a target='_blank' href='".$photo_link."'><img src='"
				.$photo_per_url."'/></a><div style='line-height:1.5;'><a class='pic_title' target='_blank' href='".$photo_link."'>".$photo_title."</a></div><div style='line-height:1.5;'><a class='pic_author' target='_blank' href='http://www.yupoo.com/photos/".$photo_author."/'>".$author_nic."</a></div><div class='yupoo_sign'></div></div></li>";	 
		break;}
		 
		default:
		break;
	  }
	}
	if(count($tweibo_id_array) > 0)
	{
	  $tweibo_ids = implode(",", $tweibo_id_array);
	  $tweibo  = $t->t_list($tweibo_ids);
	  $info = $tweibo['data']['info'];
	  $tweiboContent = "";
	  foreach( $info as $item )
	  {
		$time = getdate($item['timestamp']);
		$create_time = $time[year]."-".$time[mon]."-".$time[mday]." ".$time[hours].":".$time[minutes];
		$create_time = dateFormatTrans($create_time, $date_t);
		$profileImgUrl = $item['head']."/50";
		
		$item['text'] = tweibo_show_nick($item['text'],$tweibo[data][user]);
		$item['text'] = subs_emotions($item['text'],"tweibo");

		$tweiboContent .="<li id='".$item['id']."'>";

		if(isset($item['source'])){
			$tweiboContent .="<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f is_repost tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
			<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text'];
			$item['source']['text'] = tweibo_show_nick($item['source']['text'],$tweibo[data][user]);
			$item['source']['text'] = subs_emotions($item['source']['text'],"tweibo");

			if($item['source']['text'] == null)
				$item['source']['text'] = "此微博已被原作者删除。";
			$tweiboContent .="||".$item['source']['nick']."(@".$item['source']['name']."):".$item['source']['text']."</span></div>";
			if(isset($item['source']['image'])){
				foreach($item['source']['image'] as $re_img_url){
					$tweiboContent .="<div class='weibo_retweet_img_drop'><img src='".$re_img_url."/240' /></div>";
				}
			}
		}else{
			$tweiboContent .= "<div class='item_action'><a href='#weibo_dialog' name='modal' class='repost_f tencent'><span>转播</span></a><a href='#weibo_dialog' name='modal' class='comment_f tencent'><span>评论</span></a></div>
			<div class='story_wrapper'><div class='quote_sign'>“</div><div class='content_wrapper'><span class='weibo_text_drop'>".$item['text']."</span></div>";
			if(isset($item['image'])){
				foreach($item['image'] as $img_url){
					$tweiboContent .="<div class='weibo_img_drop'><img src='".$img_url."/240' /></div>";
				}
			}
		}
		$tweiboContent .= "<div class='story_signature'><span style='float:right;'><a href='http://t.qq.com/".$item['name']."' target='_blank'><img class='profile_img_drop' style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='"
		.$profileImgUrl."' alt='".$item['nick']."' border=0 /></a></span><span class='signature_text' style=' margin-right:5px; float:right;' ><div style='text-align:right; height:16px; '>
		<span ><a class='weibo_from_drop' href='http://t.qq.com/".$item['name']."' target='_blank'>".$item['nick']."</a></span></div><div class='weibo_date_drop'>".$create_time."</div></span></div></div></li>tweibo_sep";
	  }
	  $tweibo_array = explode("tweibo_sep", $tweiboContent);
	  $tweibo_array_len = count($tweibo_array);
	  $tweibo_array_asoc = array();
	  for($i=0; $i<$tweibo_array_len-1; $i++)
	  {
	    $temp_t = $tweibo_array[$i];
		$first_q = strpos($temp_t, "'");
		$second_q = strpos(substr($temp_t, $first_q+1), "'");
		$t_per_id = substr($temp_t, $first_q+1, $second_q);
		$first_t = strpos($temp_t, ">");
		$second_t = strpos($temp_t, "</li>");
		$tweibo_array_asoc[$t_per_id] = substr($temp_t, $first_t+1, $second_t-$first_t-1);
	  }
	  foreach($tweibo_array_asoc as $tkey=>$tval)
	  {
	    $content = str_replace("<li id='$tkey'>","<li class='weibo_drop tencent' id='$tkey' style='border:none;'>".$tval, $content);
	  }
	}
	
	if(count($temp_array['content']) > $items_perpage)
	{
	  $content .="</ul><div id='more' style='text-align:center;'><a id='".$items_perpage."' class='load_more' href='#'>更多</a></div>";
	}
	else
	{
	  $content .="</ul>";
	}
	
	$content .="<div class='kou_signature'><span>Powered by</span><a title='口立方' name='poweredby' target='_blank' href='http://koulifang.com'></a></div></div>
	<div class='spacer'></div>
	</div>
	<div id='userinfo_container'>
	  <div class='user_profiles'>
	    <div class='user_box'>
		  <div class='user_info'>
		    <div class='avatar'><a href='/member/user.php?user_id=".$story_author."'><img style='' width='80px' height='80px' src='".$user_profile_img."'></a></div>
			<div class='user_name'><a href='/member/user.php?user_id=".$story_author."'><span>".$userresult['username']."</span></a></div>
		  </div>
		  <div class='clear'></div>";
	if(islogin() && $story_author != $_SESSION['uid'])
	{
	  $login_user_id = $_SESSION['uid'];
	  
	  $query="select * from ".$db_prefix."follow where user_id=".$_SESSION[uid]." and follow_id=".$story_author;
      $relationresult=$DB->query($query);
      $num=$DB->num_rows($relationresult);
	  if($num > 0)
	  {
	    $content .="<a href='#' class='follow_btn'>已关注</a><a href='#' class='follow_btn' style='display:none;'>关注</a>";
	  }
	  else
	  {
	    $content .="<a href='#' class='follow_btn'>关注</a><a href='#' class='follow_btn' style='display:none;'>已关注</a>";
	  }
	  
	}
    // get the following and follower info
    $following_list = getFollowing($story_author);
    $follower_list=getFollower($story_author);

	$content .="<P class='user-bio'>".$userresult['intro']."</P>
				  <div class='usersfollowers'>
					<div><span class='side_title'>粉丝</span><span style='vertical-align:top' class='count'>".sizeof($follower_list)."</span></div>
					  <ul class='follower_list'>";
    $usr_img;
	foreach($follower_list as $fower){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fower;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
		if($usr_img == '')
		{
		  $usr_img = '/img/douban_user_dft.jpg';
		}
        $content .="<li id='follower_id_".$item['id']."'><a class='follow_mini_icon' href='/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
    }
    $content .= "</ul>
                </div>
		  <div class='usersfollowing'>
		    <div><span class='side_title'>关注</span><span style='vertical-align:top' class='count'>".sizeof($following_list)."</span></div>
			<ul class='following_list'>";
    foreach($following_list as $fowing){
        $query="select id, username, photo from ".$db_prefix."user where id=".$fowing;
        $result=$DB->query($query);
        $item=$DB->fetch_array($result);
		$usr_img = $item['photo'];
        $content .="<li id='following_id_".$item['id']."'><a class='follow_mini_icon' href='/member/user.php?user_id=".$item['id']."'><img title='".$item['username']."' src='".$usr_img."'></a></li>";
    }
	$total_count = 0;
	$count_query = "select domain_name, refer_url, view_count from ".$db_prefix."pageview where story_id=".$post_id;
	$countResult = $DB->query($count_query);
	if($DB->num_rows($countResult) > 0){
	    $pageview_asoc = array();
        while($count_result_row = $DB->fetch_array($countResult)){
			$pageview_asoc[$count_result_row['domain_name']] = $count_result_row['view_count']."-".$count_result_row['refer_url'];
			$total_count += $count_result_row['view_count'];
        }
		foreach($pageview_asoc as $pkey=>$pval)
	    {
	      $pval_array = explode("-", $pval);
		  $view_content .= "<div class='view_count'><a target='_blank' href='".$pval_array[1]."'>".$pkey."</a><span>浏览了<strong>".$pval_array[0]."</strong>次</span></div>";
	    }
    }	
	
    $content .= "
			</ul>
		  </div>
		</div>
	  </div>
	  <div class='story_stats'>
	    <div class='total_view_count'>总浏览次数: <span>".$total_count."</span></div>".$view_content."
	  </div>
	</div>
	</div>";
	echo $content;
	echo "<script language='javascript' >
			$(function()
			{			  
			  $('.follow_btn').click(function(){
				  var userid = $story_author;
				  var operation_val = $(this).text();
				  if('关注' == operation_val)
				  {
				    operation_val = 'follow';
				  }
				  else
				  {
				    operation_val = 'unfollow';
				  }
				  var postdata = {operation: operation_val, uid: userid};
				  $.post('useroperation.php', postdata,
					  function(data, textStatus)
					  {
						if('success'==textStatus)
						{
						  if(operation_val == 'follow')
						  {
						    var temp = $('.usersfollowers .count').text();
							$('.usersfollowers .count').text(parseInt(temp)+1);
							$('.follower_list').append(data);
						  }
						  else
						  {
							var user_id='$login_user_id';
							$(\"#follower_id_\"+user_id).remove();
							var temp = $('.usersfollowers .count').text();
							$('.usersfollowers .count').text(parseInt(temp)-1);
						  }
						  $('.follow_btn').toggle();
						}
						console.log(data);						
					  });
				}).hover(function(){
				  if($(this).text() == '已关注')
				  {
				    $(this).text('取消关注');
				  }
				},
				function(){
				  if($(this).text() == '取消关注')
				  {
				    $(this).text('已关注');
				  }
				});
			  $('.load_more').live('click',function(e)
				{
				  e.preventDefault();
				  var post_id_val = $post_id;
				  var first_item_val = $(this).attr('id');
				  var temp = first_item_val - 1;
				  var postdata = {post_id: post_id_val, first_item: first_item_val};			  
				  
				  $.ajax({
					type: 'POST',
					url: 'loadstoryitem.php',
					data: postdata, 
					beforeSend:function() 
					{
					  var imgpath = '../img/loading.gif';
					  var imgloading = $(\"<img src='../img/loading.gif' />\");
					  $('.load_more').html(imgloading);
					},
					success: function(data){
						$('#more').remove();
						$('#weibo_ul').append(data);
					    $('#weibo_ul li:gt('+temp+')').each(function()
						  {
							if($(this).hasClass('sina'))
							{
							  var id_val = $(this).attr('id');
							  WB2.anyWhere(function(W){
							  W.widget.hoverCard({
								id: id_val,
								search: true
								}); 
							  });
							}
						  });
					}
					});
				});
				
			  WB2.anyWhere(function(W){
				W.widget.hoverCard({
					id: 'weibo_card_area',
					search: true
					}); 
				});
				
			  $('#weibo_ul li.sina').each(function()
			  {
				var id_val = $(this).attr('id');
				WB2.anyWhere(function(W){
				W.widget.hoverCard({
					id: id_val,
					search: true
					}); 
				});
			  });
			  
			  //select all the a tag with name equal to modal
				$('a[name=modal]').live('click', function(e){
					e.preventDefault();
					$('.publish-tweet').val('');
					$('#weibo_dialog .word_counter').text('140');
					if($(this).hasClass('sina'))
					{
					  if($('#boxes #weibo_dialog').hasClass('sina'))
					  {
						$('#pub_wrapper').show();
						$('.pub_imply_sina, .pub_imply_tencent').hide();
						if($(this).hasClass('repost_f'))
					    {
					      $('#pub_text').text('转发').removeClass().addClass('sina');
						  $('#publish_title').text('转发微博');
						  if($(this).hasClass('is_repost'))
					      {
						    var weibo_li = $(this).closest('li');
						    var repost_txt = ('//@'+ weibo_li.find('.weibo_from_drop').text() + ': ' + weibo_li.find('.weibo_text_drop').text());
						    repost_txt = repost_txt.substr(0, repost_txt.lastIndexOf('//@'));
						    var repost_len=(280-repost_txt.len())/2;
					        $('.publish-tweet').val(repost_txt);
							if(repost_len<0)
							{
							  var pub_tweet = $('.publish-tweet');
							  var i_max_len = pub_tweet.val().length+repost_len;
							  pub_tweet.attr('maxlength', i_max_len);
							  var i_cut_txt = pub_tweet.val().substr(0, i_max_len);
							  pub_tweet.val(i_cut_txt);
							  repost_len = 0;
							}
						    $('#weibo_dialog .word_counter').text(Math.floor(repost_len));
					      } 
					    }
					    else
					    {
					      $('#pub_text').removeClass().addClass('sina');
						  $('#pub_text').text('评论');
					      $('#publish_title').text('评论微博');
					    }
					  }
					  else if(!$('#boxes #weibo_dialog').hasClass('disable'))
					  {
					    $('#pub_wrapper, .pub_imply_tencent').hide();
						$('.pub_imply_sina').show();
						if($(this).hasClass('repost_f'))
						{
						  $('#publish_title').text('转发微博');
						}
						else
						{
						  $('#publish_title').text('评论微博');
						}
					  }
					}
					else if($(this).hasClass('tencent'))
					{
					  if($('#boxes #weibo_dialog').hasClass('tencent'))
					  {
					    $('#pub_wrapper').show();
						$('.pub_imply_sina, .pub_imply_tencent').hide();
						if($(this).hasClass('repost_f'))
					    {
					      $('#pub_text').text('转播').removeClass().addClass('tencent');
						  $('#publish_title').text('转播微博');
						  if($(this).hasClass('is_repost'))
					      {
							var weibo_li = $(this).closest('li');
						    var repost_txt = ('||'+ weibo_li.find('.weibo_from_drop').text() + '(@' + weibo_li.find('.weibo_from_drop').attr('href').replace(/http:\/\/t.qq.com\//,'') +'): ' + weibo_li.find('.weibo_text_drop').text());
						    var match_array=repost_txt.match(/\|\|.*?\(@.*?\):[^|]+/g);
						    repost_txt = repost_txt.replace(match_array[match_array.length-1],'')
						    var repost_len=(280-repost_txt.len())/2;
						    $('.publish-tweet').val(repost_txt);
							if(repost_len<0)
							{
							  var pub_tweet = $('.publish-tweet');
							  var i_max_len = pub_tweet.val().length+repost_len;
							  pub_tweet.attr('maxlength', i_max_len);
							  var i_cut_txt = pub_tweet.val().substr(0, i_max_len);
							  pub_tweet.val(i_cut_txt);
							  repost_len = 0;
							}
						    $('#weibo_dialog .word_counter').text(Math.floor(repost_len));
					      } 
					    }
					    else
					    {
					      $('#pub_text').removeClass().addClass('tencent');
						  $('#pub_text').text('评论');
					      $('#publish_title').text('评论微博');
					    }
					  }
					  else if(!$('#boxes #weibo_dialog').hasClass('disable'))
					  {
					    $('#pub_wrapper, .pub_imply_sina').hide();
						$('.pub_imply_tencent').show();
						if($(this).hasClass('repost_f'))
						{
						  $('#publish_title').text('转播微博');
						}
						else
						{
						  $('#publish_title').text('评论微博');
						}
					  }
					}
					var w_id = 'w_'+ $(this).closest('li').attr('id');
					$('.publish-tweet').attr('id', w_id);
					
					//Get the A tag
					var id = $(this).attr('href');

					//Get the screen height and width
					var maskHeight = $(document).height();
					var maskWidth = $(window).width();

					//Set heigth and width to mask to fill up the whole screen
					$('#mask').css({'width':maskWidth,'height':maskHeight});	
					$('#mask').show().css('opacity', '0.7');
					//$('#mask').fadeTo('slow',0.8);	

					//Get the window height and width
					var winH = $(window).height();
					var winW = $(window).width();
					var scrollTop = $(document).scrollTop();
					var scrollLeft = $(document).scrollLeft();
						  
					//Set the popup window to center
					$(id).css('top',  winH/2-$(id).height()/2+scrollTop-100);
					$(id).css('left', winW/2-$(id).width()/2+scrollLeft);

					$(id).show(); 

				});

				$('.window .close').click(function (e) {
					e.preventDefault();
					$('#mask').hide();
					$('.window').hide();
				});		

				$('#mask').click(function () {
					$(this).hide();
					$('.window').hide();
				});	
			  
			});
			</script>";
}

else if(isset($_GET['user_id']) && isset($_GET['post_id']) && isset($_GET['action']))
{
	$user_id = $_GET['user_id'];
	$story_id = $_GET['post_id'];
	$story_action = $_GET['action'];
	if(0 == strcmp($story_action, 'edit'))
	{
	  go("/member/index.php?user_id=".$user_id."&post_id=".$story_id);
	}
	else if(0 == strcmp($story_action, 'publish'))
	{
	  $result=$DB->query("update ".$db_prefix."posts set post_status='Published'  WHERE ID='".$story_id."'");
	  go("/member/user.php?user_id=".$user_id."&post_id=".$story_id);
	}
	else
	{
	  throw new Exception('Undefined story action.');
	}
}

else if(isset($_GET['user_id']) && !isset($_GET['post_id']))
{
  $tbl_name="story_posts";
  // How many adjacent pages should be shown on each side?
  $adjacents = 3;
  $user_id = $_GET['user_id'];
  $story_content = "<div id='userstory_container' class='inner'><div class='userstory_list'><ul>";
  $userresult = $DB->fetch_one_array("SELECT username, photo FROM ".$db_prefix."user where id='".$user_id."'");
  $user_profile_img = $userresult['photo'];
  
    $query = "SELECT COUNT(*) as num FROM $tbl_name where post_author='".$user_id."'";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	$targetpage = "user.php?user_id=".$user_id; 
	$limit = 12; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM $tbl_name where post_author='".$user_id."'LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);	
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&page=$prev\">« 前页</a>";
		else
			$pagination.= "<span class=\"disabled\">« 前页</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&page=$next\">后页 »</a>";
		else
			$pagination.= "<span class=\"disabled\">后页 »</span>";
		$pagination.= "</div>\n";		
	}
  
  while ($story_item = mysql_fetch_array($result))
  {
    //printf ("title: %s  summary: %s", $story_item['post_title'], $story_item['post_summary']);
	$post_id = $story_item['ID'];
	$post_title = $story_item['post_title'];
	$post_pic_url = $story_item['post_pic_url'];
	$post_status = $story_item['post_status'];
	if(0 == strcmp($post_status, 'Published'))
	{
	  $post_status_txt = '已发布';
	  $icon_type = 'publish_icon';
	}
	else
	{
	  $post_status_txt = '草稿';
	  $icon_type = 'draft_icon';
	}
	$post_date = $story_item['post_date'];
	$temp_array = explode(" ", $story_item['post_date']);
	$post_date = $temp_array[0];
    $story_content .= "<li><div class='story_wrap'><a class='cover' style='background: url(".$post_pic_url.") no-repeat; background-size: 100%;' href='/member/user.php?user_id=".$user_id."&post_id=".$story_item['ID']."'><div class='title_wrap'><h1 class='title'>".$post_title."</h1></div></a><div class='editable'>
  <div class='status'>
    <div class='".$post_status."'>
	  <div class='".$icon_type."'></div>
	  <span>".$post_status_txt."</span>
	</div>
  </div>";
  if(islogin() && $user_id == $_SESSION['uid'])
  {
    $story_content .="
    <div class='actions'>
      <a id='".$post_id."_delete' class='icon delete' title='删除' href='#'></a>
	  <a class='icon edit' title='编辑' href='/member/index.php?user_id=".$user_id."&post_id=".$post_id."'></a>
    </div>";
  }
   $story_content .="<div class='clear'></div></div></div>
	<div class='story_meta'><span><img border='0' style='position:relative; top:3px; width: 20px; height:20px;' src='".$user_profile_img."'/><a style='margin-left:5px; vertical-align:top;'>".$userresult['username']."</a><a style='float:right; vertical-align:top;'>".$post_date."</a></span></div></li>";
  }

  $story_content .="</ul></div>".$pagination."</div>";
  echo $story_content;
}

else
{
  if(!islogin())
  {
    header("location: /login/login_form.php"); 
    exit;
  }
}
include "../include/footer.htm";
?>

<script type="text/javascript">
Array.prototype.getUnique = function()
{
  var o = {};
  var i, e;
  for (i=0; e=this[i]; i++) {o[e]=1};
  var a=new Array();
  for (e in o)
  {a.push (e)};
  return a;
} 

String.prototype.len=function()
{
  return this.replace(/[^\x00-\xff]/g,"**").length;
}

$(function(){
	$('#embed_a').toggle(function(e){
	  e.preventDefault();
	  $('#embed_bar').slideDown("slow");
	  $('.arrow_up').css('display', 'inline-block');
	  $('.arrow_down').hide();
	  $('#embed_bar span .sto_embed').select();
	},
	function(e){
	  e.preventDefault();
	  $('#embed_bar').slideUp("slow");
	  $('.arrow_down').show();
	  $('.arrow_up').hide();
	  $('#embed_bar span .sto_embed').select();
	});
	
	$('.sto_embed').click(function(){
	  $(this).select();
	});
	
	$('#user_action').css('display', 'inline');
	
	$('.delete').click(function(e){
	  e.preventDefault();
	  var r=confirm("确定删除这个故事吗?");
	  if (r==true)
	  {
	    var post_id_val = $(this).attr('id').replace(/_delete/, "");
	    var getData = {post_id: post_id_val};
	    $.get('removestory.php', getData,
	    function(data, textStatus)
	    {
		  if(textStatus == 'success')
		  {
            if($('#'+post_id_val+'_delete').hasClass('redirect'))
			{
			  self.location = '/member/user.php?user_id='+data;
			}
			else
			{
			  $('#'+post_id_val+'_delete').closest('li').remove();
			}
		  }
	    });
	  }
	});
	
	$('.act_digg').click(function(e)
	{
	  e.preventDefault();
	  var temp_array = $(this).attr('id').split('_');
	  var post_id_val = temp_array[0]; 
	  var getData = {post_id: post_id_val};
	  $.get('diggoperation.php', getData,
	  function(data, textStatus)
	  {
		if(textStatus == 'success')
		{
		  if(data == 0)
		  {
		    alert('您已经投票过了');
		  }
		  else
		  {
			var temp = $('#'+post_id_val+'_digg_count').text();
			var digg_count = 1+parseInt(temp);
		    $('#'+post_id_val+'_digg_count').text(digg_count).attr('title', '累计赞'+digg_count+'次');
		  }
		}
	  });
	});
	
	$('.published-steps .tabs').click(function(e)
	{
	  if ($(e.target).is('.post-tab'))
	  {
	    $('.steps .notify-content').css('display', 'none');
		$('.steps .share-content').css('display', 'none');
		$('.steps .post-content').toggle();
		$('.post-content .sto_embed').select();
	  }
	  else if ($(e.target).is('.notify-tab'))
	  {
	    $('.steps .post-content').css('display', 'none');
		$('.steps .share-content').css('display', 'none');
		$('.steps .notify-content').toggle();
	  }
	  else if ($(e.target).is('.share-tab'))
	  {
	    $('.steps .post-content').css('display', 'none');
		$('.steps .notify-content').css('display', 'none');
		$('.steps .share-content').toggle();
	  }
	});
	
	$('.notify-tweet').live('keyup', function(e){
	  var w_user_count = 0;
	  var t_user_count = 0;
	  $('.sina_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  w_user_count += $(this).next().text().len() +1;
		}
	  });
	  $('.tencent_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  t_user_count += $(this).next().attr('id').len() +2;
		}
	  });
	  var user_count = (w_user_count > t_user_count)?w_user_count:t_user_count;
      var word_remain=(280-$(this).val().len() - user_count)/2;
	  if(word_remain == 0)
	  {
		var max_len = $(this).val().length;
		$(this).attr('maxlength', max_len);
	  }
	  if(word_remain < 0)
	  {
		var max_len = $(this).val().length+word_remain;
		$(this).attr('maxlength', max_len);
		var cut_txt = $(this).val().substr(0, max_len);
		$(this).val(cut_txt);
		word_remain = 0;
	  }
	  $('.tweet_control .word_counter').text(Math.floor(word_remain));
	});
	
	$('.publish-tweet').live('keyup', function(e){
      var word_remain=(280-$(this).val().len())/2;
	  if(word_remain == 0)
	  {
		var max_len = $(this).val().length;
		$(this).attr('maxlength', max_len);
	  }
	  if(word_remain < 0)
	  {
		var max_len = $(this).val().length+word_remain;
		$(this).attr('maxlength', max_len);
		var cut_txt = $(this).val().substr(0, max_len)
		$(this).val(cut_txt);
		word_remain = 0;
	  }
	  $('#weibo_dialog .word_counter').text(Math.floor(word_remain));
	});
	
	//publish and repost part
	$('.btn_w_publish').live('click', function(e){
	  var w_content_val = $('.publish-tweet').val();
	  var id_val = $('.publish-tweet').attr('id').replace(/w_/,"");
	  var ope_val;
	  if($('#pub_text').text() == '评论')
	  {
	    ope_val = 'comment';
	  }
	  else
	  {
	    ope_val = 'repost';
	  }
	  var postUrl;
	  var postData;
	  if($('#pub_text').hasClass('sina'))
	  {
	    postUrl = '../weibo/postweibo.php';
	  }
	  else
	  {
	    postUrl = '../tweibo/posttweibo.php';
	  }
	  postData = {operation: ope_val, id: id_val, weibo_content: w_content_val};

	  $.ajax({
	  type: 'POST',
	  url: postUrl,
	  data: postData, 
	  success: function(data)
	  {
		$('#mask').hide();
		$('.window').hide();
	  }
	  });
	});
	
	$('.tweet_btn').live('click', function(e){
	  e.preventDefault();
	  var weibo_content_val = '';
	  var tweibo_content_val = '';
	  $('.sina_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  weibo_content_val += $(this).next().text()+' ';
		}
	  });
	  $('.tencent_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  tweibo_content_val += '@'+$(this).next().attr('id')+' ';
		}
	  });
	  
	  if(($('#tweibo_f').attr('checked')) && (tweibo_content_val != ''))
	  {
	      tweibo_content_val += $('.notify-tweet').val();
		  var postUrl;
		  var postData;
		  postUrl = '../tweibo/posttweibo.php';
		  postData = {operation: 'publish', weibo_content: tweibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	  
	  if(($('#weibo_f').attr('checked')) && (weibo_content_val != ''))
	  {
	      weibo_content_val += $('.notify-tweet').val();
		  var postUrl;
		  var postData;
		  postUrl = '../weibo/postweibo.php';
		  postData = {operation: 'publish', weibo_content: weibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	  if($('.steps .notify-content').is(':visible'))
	  {
	    $('.steps .notify-content').css('display', 'none');
	  }
	});
});
	
</script>

<script type='text/javascript' src='../js/jquery-ui-1.8.12.custom.min.js'></script>
<script type="text/javascript" src="../js/jquery.embedly.min.js"></script>
<script type="text/javascript">var jiathis_config = {data_track_clickback:true};</script>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js?uid=1542042" charset="utf-8"></script>
