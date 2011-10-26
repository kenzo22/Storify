<?php
include "../global.php";
include_once( '../weibo/config.php' );
include_once( '../weibo/sinaweibo.php' );

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

$content = "<div class='form_wrapper' style='padding-top:50px;'>
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
			  <div style='clear:both;'>
			  <h2>请选择关联帐号的方式</h2>
			  <div id='select_form' style='overflow:hidden;'>
			    <div class='left selected'>
			      <div><b>使用已有的帐号</b>以前注册过</div>
			    </div>
			    <div class='right unselected'>
			      <div><b>使用新的帐号</b>以前没有注册过</div>
			    </div>
			  </div>
			  <div id='form_1'>
			    <form method='post' action='account_associate.php'> 
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$weibo_uid."' name='weibo_uid' /></div>
				  <div><label>电子邮箱</label><input id='email' type='text' value='' size='50' name='email' maxlength='50' /><span class='form_tip' id='email_tip'></span></div>
				  <div><label>密码</label><input id='pwd' type='password' value='' size='50' name='pwd' maxlength='50' /><span class='form_tip' id='pwd_tip'></div>
				  <div class='aa_submit large blue awesome'><a>确定关联 &raquo;</a></div>
			    </form>
			  </div>
			  <div id='form_2' style='display:none;'>
			    <form method='post' action='account_associate.php'>
				  <div style='display:inline; margin:0;padding:0;' ><input type='hidden' value='".$weibo_uid."' name='weibo_uid' /></div>
				  <div><label>电子邮箱</label><input id='user_email' type='text' value='' size='50' name='user_email' maxlength='50' /><span class='form_tip' id='user_email_tip'></span></div>
				  <div><label>用户名</label><input id='user_name' type='text' value='' size='50' name='user_name' maxlength='50' /><span class='form_tip' id='user_name_tip'></span></div>  
				  <div><label>密码</label><input id='user_pwd' type='password' value='' size='50' name='user_pwd' maxlength='50' /><span class='form_tip' id='user_pwd_tip'></div>
				  <div><label>确认密码</label><input id='user_pwd_confirm' type='password' value='' size='50' name='user_pwd_confirm' maxlength='50' /><span class='form_tip' id='pwd_confirm_tip'></div> 
				  <div class='aa_submit large blue awesome'><a>创建帐号并关联 &raquo;</a></div>
			    </form>
			  </div>
			</div>";
			
echo $content;
include "../include/footer.htm";	
?>
<script>
$(function(){
$('.unselected').live('click', function(e){
  $('.selected').removeClass('selected').addClass('unselected');
  $(this).removeClass('unselected').addClass('selected');
  $("#form_1").toggle();
  $("#form_2").toggle();
});

$('#email').bind('focus', function(){
$('#email_tip').text('请输入您在口立方已经注册的邮箱').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#email_tip').text('邮箱不能为空').css('color', 'red');
}
else{
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#email_tip').text('邮箱格式不正确').css('color', 'red');
}
else
{
  var email  = $(this).val();
  var url = '/register/check_email.php?email='+email;
  $.get(url, function(data){
  if(data =='1')
  {
	$('#email_tip').text('您可以绑定该邮箱').css('color', '#666699').show();
  }
  else
  {
	$('#email_tip').text('抱歉，该邮箱还未注册').css('color', 'red').show();
  }
  return false;
  })
}
}
});

$('#user_email').bind('focus', function(){
$('#user_email_tip').text('绑定后可直接用来登录口立方').css('color', '#666699').show();
}).bind('blur', function(){
if(this.value=='')
{
  $('#user_email_tip').text('邮箱不能为空').css('color', 'red');
}
else{
if(!/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value))
{
  $('#user_email_tip').text('邮箱格式不正确').css('color', 'red');
}
else
{
  var email  = $(this).val();
  var url = '/register/check_email.php?email='+email;
  $.get(url, function(data){
  if(data =='1')
  {
	$('#user_email_tip').text('该邮箱已被注册').css('color', 'red').show();
  }
  else
  {
	$('#user_email_tip').text('该邮箱可以使用').css('color', '#666699').show();
  }
  return false;
  })
}
}
});

$('#pwd').bind('focus', function(){
$('#pwd_tip').text('请输入该邮箱的注册密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_tip').text('');
if(this.value=='')
{
  $('#pwd_tip').text('密码不能为空').css('color', 'red');
}
else
{
  var email_val  = $('#email').val();
  var pwd_val = $(this).val();
  var postdata={email: email_val, pwd: pwd_val};
  var url = 'check_credential.php';
  $.post(url, postdata, function(data){
  if(data =='0')
  {
	$('#pwd_tip').text('密码错误，请重新输入').css('color', 'red').show();
	this.value='';
  }
  else
  {
    $('#pwd_tip').text('密码输入正确').css('color', '#666699').show();
  }
  return false;
  })
}
})

$('#user_pwd').bind('focus', function(){
$('#user_pwd_tip').text('字母、数字或符号，最短四个字符，区分大小写').css('color', '#666699').show();
}).bind('blur', function(){
$('#user_pwd_tip').text('');
if(this.value=='')
{
  $('#user_pwd_tip').text('密码不能为空').css('color', 'red');
}
if(this.value!='' && this.value.length<4)
{
  $('#user_pwd_tip').text('密码长度不足四个字符').css('color', 'red');
}
})

$('#user_pwd_confirm').bind('focus', function(){
$('#pwd_confirm_tip').text('请您再次输入密码').css('color', '#666699').show();
}).bind('blur', function(){
$('#pwd_confirm_tip').text('');
if(this.value!=$('#user_pwd').val())
{
  $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
  this.value='';
}
})

$('#user_name').bind('focus', function(){
$('#user_name_tip').text('中、英文均可，最长14个英文或7个汉字').css('color', '#666699').show();
}).bind('blur', function(){
$('#user_name_tip').text('');
if(this.value=='')
{
  $('#user_name_tip').text('名号不能为空').css('color', 'red');
}
if(this.value.length>14)
{
  $('#user_name_tip').text('名号长度不能超过14个英文或7个汉字').css('color', 'red');
}
})

$('.aa_submit').click(function(e){
var submitFlag = true;
if($('#form_1').is(':hidden'))
{
  var email = $('#user_email').val();
  var username = $('#user_name').val();
  var pwd = $('#user_pwd').val();
  var pwd_confirm = $('#user_pwd_confirm').val();
  if(pwd != pwd_confirm)
  {
    $('#pwd_confirm_tip').text('两次输入密码不一致，请重新输入').css('color', 'red');
    $('#user_pwd_confirm').val('');
  }
  var tip_flag = ($('#user_email_tip').css('color') == 'red') || ($('#user_name_tip').css('color') == 'red') || ($('#user_pwd_tip').css('color') == 'red') || ($('#pwd_confirm_tip').css('color') == 'red');
  if(tip_flag || email == '' || username == '' || pwd == '' || pwd_confirm == '')
  submitFlag = false;
}
else if($('#form_2').is(':hidden'))
{
  var email = $('#email').val();
  var pwd = $('#pwd').val();
  var tip_flag = ($('#email_tip').css('color') == 'red') || ($('#pwd_tip').css('color') == 'red');
  if(tip_flag || email == '' || pwd == '')
  submitFlag = false;
}
if(submitFlag)
{
  $(this).closest('form').submit();
}
else
{
  e.preventDefault();
}
});

});
</script>