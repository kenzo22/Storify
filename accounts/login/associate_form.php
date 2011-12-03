<?php
$html_title = "微博帐号关联 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/config.php' );
include_once( $_SERVER['DOCUMENT_ROOT'].'/weibo/sinaweibo.php' );

if(islogin())
{
  header("location: /"); 
  exit;
}

$c = new WeiboClient( WB_AKEY , 
                      WB_SKEY , 
                      $_SESSION['last_wkey']['oauth_token'] , 
                      $_SESSION['last_wkey']['oauth_token_secret']);
					  
$msg = $c->verify_credentials();
if ($msg === false || $msg === null){
	echo "Error occured";
	return false;
}
if (isset($msg['error_code']) && isset($msg['error'])){
	echo ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
	return false;
}
if (isset($msg['id'])){
	$weibo_uid = $msg['id'];
	$weibo_nick = $msg['screen_name'];
	$photo = $msg['profile_image_url'];
	$fans_count = $msg['followers_count'];
	$follow_count = $msg['friends_count'];
	$status_count = $msg['statuses_count'];
}

$content = "<div class='form_wrapper'>
			  <div id='account_meta'>
			    <div class='account_title'>正在使用下面的微博帐号登录</div>
				<div style='margin:10px 0 0 10px; overflow:auto;'>
				  <img src='".$photo."' style='float:left; width:50px; height:50px;' />
			      <div class='meta_wrapper'>
			        <div><a href='http://weibo.com/".$weibo_uid."' target='_blank'>".$weibo_nick."</a></div>
					<div class='account_count'>
			          <span>粉丝:".$fans_count."</span>
			          <span>关注:".$follow_count."</span>
				      <span>微博:".$status_count."</span>
					</div>
					<div class='last_status'>".$msg['status']['text']."</div>
				  </div>
				</div>
			  </div>
			  <div style='clear:both;'></div>
			  <h2>请选择关联帐号的方式</h2>
			  <div id='select_form' style='overflow:hidden;'>
			    <div class='left selected'>
			      <div><b>使用已有的帐号</b>&nbsp以前注册过</div>
			    </div>
			    <div class='right unselected'>
			      <div><b>使用新的帐号</b>&nbsp以前没有注册过</div>
			    </div>
			  </div>
			  <div id='form_1'>
			    <form method='post' action='/accounts/associate'> 
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$weibo_uid."' name='weibo_uid' /></div>
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$photo."' name='weibo_photo' /></div>
				  <div><label>电子邮箱</label><input id='email' type='text' value='' size='50' name='email' maxlength='50' /><span class='form_tip' id='email_tip'></span></div>
				  <div><label>密码</label><input id='pwd' type='password' value='' size='50' name='pwd' maxlength='50' /><span class='form_tip' id='pwd_tip'></span></div>
				  <div class='aa_submit'><a class='cfm_awesome large blue awesome'>确定关联 &raquo;</a></div>
			    </form>
			  </div>
			  <div id='form_2' style='display:none;'>
			    <form method='post' action='/accounts/associate'>
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$weibo_uid."' name='weibo_uid' /></div>
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$photo."' name='weibo_photo' /></div>
				  <div><label>电子邮箱</label><input id='user_email' type='text' value='' size='50' name='user_email' maxlength='50' /><span class='form_tip' id='user_email_tip'></span></div>
				  <div><label>用户名</label><input id='user_name' type='text' value='' size='50' name='user_name' maxlength='50' /><span class='form_tip' id='user_name_tip'></span></div>  
				  <div><label>密码</label><input id='user_pwd' type='password' value='' size='50' name='user_pwd' maxlength='50' /><span class='form_tip' id='user_pwd_tip'></span></div>
				  <div><label>确认密码</label><input id='user_pwd_confirm' type='password' value='' size='50' name='user_pwd_confirm' maxlength='50' /><span class='form_tip' id='pwd_confirm_tip'></span></div> 
				  <div class='aa_submit'><a class='cfm_awesome large blue awesome'>创建帐号并关联 &raquo;</a></div>
			    </form>
			  </div>
			</div>";
			
echo $content;
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";	
?>
<script type='text/javascript' src='/js/associate.js'></script>
</body>
</html>
